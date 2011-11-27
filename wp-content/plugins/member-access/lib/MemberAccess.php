<?php
/**
 * Copyright 2008 Chris Abernethy
 *
 * This file is part of Member Access.
 *
 * Member Access is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Member Access is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Member Access.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Member Access is a WordPress plugin that allows an administrator to require that users be logged-in in order to view certain posts and pages.
 */
class MemberAccess
{

    /**
     * An instance of the options structure containing all options for this
     * plugin.
     *
     * @var MemberAccess_Structure_Options
     */
    var $_options = null;

    /**************************************************************************/
    /*                         Singleton Functionality                        */
    /**************************************************************************/

    /**
     * Retrieve the instance of this class, creating it if necessary.
     *
     * @return MemberAccess
     */
    function instance()
    {
        static $instance = null;
        if (null == $instance) {
            $c = __CLASS__;
            $instance = new $c;
        }
        return $instance;
    }

    /**
     * The constructor initializes the options object for this plugin.
     */
    function MemberAccess()
    {
        $this->_options = new MemberAccess_Structure_Options('member_access_options');
    }

    /**************************************************************************/
    /*                     Plugin Environment Management                      */
    /**************************************************************************/

    /**
     * This initialization method instantiates an instance of the plugin and
     * performs the initialization sequence. This method is meant to be called
     * statically from the plugin bootstrap file.
     *
     * Example Usage:
     * <pre>
     * MemberAccess::run(__FILE__)
     * </pre>
     *
     * @param string $plugin_file The full path to the plugin bootstrap file.
     */
    function run($plugin_file)
    {
        $plugin = MemberAccess::instance();

        // Configure localization.
        // Use correct plugin directory name, not plugin_label
        load_plugin_textdomain('member_access', false, 'member-access/locale');

        // Activation and deactivation hooks have special registration
        // functions that handle sanitization of the given filename. It
        // is recommended that these be used rather than directly adding
        // an action callback for 'activate_<filename>'.

        register_activation_hook  ($plugin_file, array(&$plugin, 'hookActivation'));
        register_deactivation_hook($plugin_file, array(&$plugin, 'hookDeactivation'));

        // Set up action callbacks.
        add_action('admin_menu'                 , array(&$plugin, 'registerOptionsPage'));
        add_action('do_meta_boxes'              , array(&$plugin, 'registerMetaBoxes'), 10, 3);
        add_action('manage_pages_custom_column' , array(&$plugin, 'renderPostsColumns'), 10, 2);
        add_action('manage_posts_custom_column' , array(&$plugin, 'renderPostsColumns'), 10, 2);
        add_action('wp_insert_post'             , array(&$plugin, 'updatePost'));
        add_action('member_access_save_options', array(&$plugin, 'saveOptionsPage'));

        // Set up filter callbacks.
        add_filter('the_posts'                  , array(&$plugin, 'filterPosts'));
        add_filter('the_content'                , array(&$plugin, 'filterContent'), 10);
        add_filter('manage_pages_columns'       , array(&$plugin, 'registerPostsColumns'));
        add_filter('manage_posts_columns'       , array(&$plugin, 'registerPostsColumns'));
        add_filter('plugin_action_links'        , array(&$plugin, 'renderOptionsLink'), 10, 2);
    }

    /**
     * This is the plugin activation hook callback. It performs setup actions
     * for the plugin and should be smart enough to know when the plugin has
     * already been installed and is simply being re-activated.
     */
    function hookActivation()
    {
        // If 'version' is not yet set in the options array, this is a first
        // time install scenario. Perform the initial database and options
        // setup.
        if (null === $this->getOption('version')) {
            $this->_install();
            return;
        }

        // If the plugin version stored in the options structure is older than
        // the current plugin version, initiate the upgrade sequence.
        if (version_compare($this->getOption('version'), '1.1.3', '<')) {
            $this->_upgrade();
            return;
        }
    }

    /**
     * This is the plugin deactivation hook callback, it performs teardown
     * actions for the plugin.
     */
    function hookDeactivation()
    {
    }

    /**
     * This method is called when the plugin needs to be installed for the first
     * time.
     */
    function _install()
    {
        global $wpdb;

        // Create a field in the posts table to hold the visibility status
        // of each post.
        $wpdb->query(sprintf(
            "ALTER TABLE %s ADD COLUMN %s enum('public','private','default') DEFAULT 'default'"
          , $wpdb->posts
          , $wpdb->escape('member_access_visibility')
        ));

        // Set the default options.
        $this->setOption('version'                , '1.1.3');

        $this->setOption('pages_private'          , false);
        $this->setOption('pages_redirect'         , false);
        $this->setOption('pages_redirect_page'    , 0);

        $this->setOption('posts_private'          , false);
        $this->setOption('posts_redirect'         , false);
        $this->setOption('posts_redirect_page'    , 0);

        $this->setOption('postspage_excerpts'     , false);
        $this->setOption('postspage_redirect'     , false);
        $this->setOption('postspage_redirect_page', 0);

        $this->setOption('archive_excerpts'       , false);
        $this->setOption('archive_redirect'       , false);
        $this->setOption('archive_redirect_page'  , 0);

        $this->setOption('search_excerpts'        , false);
        $this->setOption('search_redirect'        , false);
        $this->setOption('search_redirect_page'   , 0);

        $this->setOption('rss_excerpts'           , false);

        $this->_options->save();
    }

    /**
     * This method is called when the internal plugin state needs to be
     * upgraded.
     */
    function _upgrade()
    {
        // Upgrade Example
        //$old_version = $this->getOption('version');
        //if (version_compare($old_version, '3.5', '<')) {
        //    // Do upgrades for version 3.5
        //    $this->setOption('version', '3.5');
        //}
        $this->setOption('version', '1.1.3');
        $this->_options->save();
    }

    /**************************************************************************/
    /*                          Action Hook Callbacks                         */
    /**************************************************************************/

    /**
     * This is a filter callback for the 'the_posts' filter, which is invoked
     * after the posts have been loaded in the WP_Query object.
     *
     * @param array $posts
     */
    function filterPosts($posts)
    {
        // If the user is logged in, or there are no posts to filter, return
        // the posts array as no further action is necessary.
        if (is_user_logged_in() || empty($posts)) {
            return $posts;
        }

        $filtered_posts = array();
        foreach ($posts as $the_post) {

            // Determine what to do with private posts for each of the relevant
            // conditional tags. If we have not opted to show excerpts on any
            // multi-post pages, or we are viewing a single-post page, simply
            // filter out the post.
            if (MemberAccess::isPrivate($the_post->ID)) {
                switch(true) {
                    case is_page():
                    case is_single():
                    case is_home()    && !$this->getOption('postspage_excerpts');
                    case is_archive() && !$this->getOption('archive_excerpts'):
                    case is_search()  && !$this->getOption('search_excerpts'):
                    case is_feed()    && !$this->getOption('rss_excerpts'):
                        continue 2;
                }
            }

            // Keep the current post.
            $filtered_posts[] = $the_post;

        }

        // If we have removed all posts from an otherwise valid list of posts,
        // check our settings to see whether we should redirect the user or
        // allow the flow to continue as though no posts were found.
        if (empty($filtered_posts)) {

            if (is_page() && $this->getOption('pages_redirect')) {
                $this->_redirect($this->getOption('pages_redirect_page'));
            }

            if (is_single() && $this->getOption('posts_redirect')) {
                $this->_redirect($this->getOption('posts_redirect_page'));
            }

            if (is_home() && $this->getOption('postspage_redirect')) {
                $this->_redirect($this->getOption('postspage_redirect_page'));
            }

            if (is_archive() && $this->getOption('archive_redirect')) {
                $this->_redirect($this->getOption('archive_redirect_page'));
            }

            if (is_search() && $this->getOption('search_redirect')) {
                $this->_redirect($this->getOption('search_redirect_page'));
            }

        }

        return $filtered_posts;
    }

    /**
     * This filter replaces the normal post content with the content of the post
     * excerpt in archive, search and rss views when those views have been
     * configured to display excerpts.
     *
     * @param string $content
     * @return string
     */
    function filterContent($content)
    {
    	global $post;

    	// If the user is logged in, return the content unfiltered.
        if (is_user_logged_in()) {
            return $content;
        }

        // If we are on a page where excerpts are warranted based on page
        // type and plugin configuration, and the post is private, replace
        // the content with the excerpt. This method must be temporarily
        // removed from the 'the_content' filter to prevent a loop resulting
        // in the script aborting.

        switch(true) {
            case is_home()    && $this->getOption('postspage_excerpts'):
            case is_archive() && $this->getOption('archive_excerpts'):
            case is_search()  && $this->getOption('search_excerpts'):
            case is_feed()    && $this->getOption('rss_excerpts'):
            	if ($this->isPrivate($post->ID)) {
			        remove_filter('the_content', array(&$this, 'filterContent'), 10);
	            	$content = get_the_excerpt();
			        add_filter('the_content', array(&$this, 'filterContent'), 10);
            	}
                break;
        }

        return $content;
    }

    /**
     * This is the admin_menu activation hook callback, it adds a sub-menu
     * navigation item for this plugin to the plugins.php page and links it to
     * the renderOptionsPage() method.
     *
     * Plugins wishing to change this default behavior should override this
     * method to create the appropriate options pages.
     */
    function registerOptionsPage()
    {
        $page = add_submenu_page(
            'options-general.php'              // parent
          , esc_html('Member Access')          // page_title
          , esc_html('Member Access')          // menu_title
          , 'manage_options'                   // access_level
          , 'member_access'                   // file
          , array(&$this, 'renderOptionsPage') // function
        );

        // Get our admin javascript and css into the page header. We only want
        // it for this plugin's option page, which is why this is action hook
        // is registered here and not as a global hook.
        add_action( "admin_print_scripts-$page", array(&$this, 'renderAdminScripts'));
    }

    /**
     * Render the meta-boxes for this plugin in the advanced section of both
     * the post and page editing screens.
     *
     * @param string $page The type of page being loaded (page, post, link or comment)
     * @param string $context The context of the meta box (normal, advanced)
     * @param StdClass $object The object representing the page type
     */
    function registerMetaBoxes($page, $context, $object)
    {
        if (in_array($page, array('page', 'post'))) {
            $callback = 'render' . ucfirst($page) . 'MetaBox';
            add_meta_box(
                esc_attr('member_access') // id attribute
              , esc_html('Member Access')   // metabox title
              , array(&$this, $callback)           // callback function
              , $page                              // page type
            );
        }
    }

    /**
     * Render the contents for a custom column with the given column name for
     * the given post ID.
     *
     * @param string $column_name The custom column tag
     * @param integer $post_id The ID of the post
     */
    function renderPostsColumns($column_name, $post_id)
    {
        global $post;

        if ('member_access_visibility' === $column_name) {
            switch($post->{'member_access_visibility'}) {
                case 'public':  $visibility = __('Everyone'); break;
                case 'private': $visibility = __('Members');  break;
                case 'default':
                    $visibility = __('Default (Everyone)');
                    if (MemberAccess::isPrivate($post_id)) {
                        $visibility = __('Default (Members)');
                    }
                    break;
            }
            echo $visibility;
        }
    }

    /**
     * This action hook callback is called after a post or page is created or
     * updated.
     *
     * @param integer $post_id
     */
    function updatePost($post_id)
    {
        global $wpdb;

        // Don't update the wp_posts fields if this is a quick-edit.
        if (@$_POST['action'] == 'inline-save') {
            return;
        }

        // Validate that the form input value is present and one of 'public'
        // or 'private'. If the input is missing, or contains some other value,
        // use 'default'.

        $visibility = 'default';
        $key        = 'member_access_visibility';
        if (array_key_exists($key, $_POST)) {
            if (in_array($_POST[$key], array('public', 'private'))) {
                $visibility = $_POST[$key];
            }
        }

        $wpdb->query(sprintf(
           "UPDATE %s SET %s = '%s' WHERE ID = %d"
         , $wpdb->posts
         , $wpdb->escape($key)
         , $wpdb->escape($visibility)
         , $post_id
        ));
    }

    /**************************************************************************/
    /*                          Filter Hook Callbacks                         */
    /**************************************************************************/

    /**
     * This filter hook callback allows the columns displayed on the posts and
     * page management tabs to be altered. In this case, an additional column
     * is added.
     *
     * @param array $defaults The array of posts columns.
     */
    function registerPostsColumns($defaults)
    {
        $defaults['member_access_visibility'] = esc_html(__('Visibility', 'member_access'));
        return $defaults;
    }

    /**
     * This is the 'plugin_action_links' hook callback, it adds a single link
     * to the options page that was registered by the registerOptionsPage()
     * method. The link is titled 'Settings', and will appear as the first link
     * in the list of plugin links.
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    function renderOptionsLink($links, $file)
    {
        static $plugin_dir = null;
        if(null === $plugin_dir) {
            $plugin_dir = plugin_basename(__FILE__);
            $plugin_dir = substr($plugin_dir, 0, stripos($plugin_dir, '/'));
        }

        if (dirname($file) == $plugin_dir) {
            $view = new MemberAccess_Structure_View('options-link.phtml');
            $view->set('link_href' , 'plugins.php?page=member_access');
            $view->set('link_title', sprintf(__('%s Settings', 'member_access'), 'Member Access'));
            $view->set('link_text' , __('Settings', 'member_access'));
            ob_start();
            $view->render();
            array_unshift($links, ob_get_clean());
        }
        return $links;
    }

    /**
     * Save the results of a post from the options page.
     */
    function saveOptionsPage()
    {
        global $wpdb;

        if (isset($_POST['action']) && 'update' == $_POST['action']) {

            check_admin_referer('update-options');

            // Clear all post overrides.
            if (isset($_POST['member_access_clear_post_overrides'])) {
                $this->_clearOverrides('post');
                return;
            }

            // Clear all page overrides.
            if (isset($_POST['member_access_clear_page_overrides'])) {
                $this->_clearOverrides('page');
                return;
            }

            // Non-Booleans
            $this->setOption('pages_redirect_page'    , $_POST['member_access_pages_redirect_page']);
            $this->setOption('posts_redirect_page'    , $_POST['member_access_posts_redirect_page']);
            $this->setOption('postspage_redirect_page', $_POST['member_access_postspage_redirect_page']);
            $this->setOption('archive_redirect_page'  , $_POST['member_access_archive_redirect_page']);
            $this->setOption('search_redirect_page'   , $_POST['member_access_search_redirect_page']);

            // Booleans
            $this->setOption('pages_private'          , isset($_POST['member_access_pages_private']));
            $this->setOption('pages_redirect'         , isset($_POST['member_access_pages_redirect']));
            $this->setOption('posts_private'          , isset($_POST['member_access_posts_private']));
            $this->setOption('posts_redirect'         , isset($_POST['member_access_posts_redirect']));
            $this->setOption('postspage_excerpts'     , isset($_POST['member_access_postspage_excerpts']));
            $this->setOption('postspage_redirect'     , isset($_POST['member_access_postspage_redirect']));
            $this->setOption('archive_excerpts'       , isset($_POST['member_access_archive_excerpts']));
            $this->setOption('archive_redirect'       , isset($_POST['member_access_archive_redirect']));
            $this->setOption('search_excerpts'        , isset($_POST['member_access_search_excerpts']));
            $this->setOption('search_redirect'        , isset($_POST['member_access_search_redirect']));
            $this->setOption('rss_excerpts'           , isset($_POST['member_access_rss_excerpts']));

            $this->_options->save();

            // Render the header message partial
            $this->_messageHelper(__('Settings have been saved.', 'member_access'));

        }
    }

    /**************************************************************************/
    /*                           Indirect Callbacks                           */
    /**************************************************************************/

    /**
     * This method fires the custom <label>_save_options action hook and registers
     * the renderAdminFooter() method as an 'in_admin_footer' action hook before
     * rendering the actual options page.
     */
    function renderOptionsPage()
    {
        // Invoke the action hook for saving the options page.
        do_action('member_access_save_options');

        // Register the in_admin_footer action hook. This is done here so that
        // it only gets registered for the options page for this plugin, and
        // not every plugin.
        add_action('in_admin_footer', array(&$this, 'renderAdminFooter'));

        $view = new MemberAccess_Structure_View('options-page.phtml');
        $view->set('heading'                , sprintf(__('%s Settings', 'member_access'), 'Member Access'));
        $view->set('nonce_action'           , 'update-options');
        $view->set('plugin_label'           , 'member_access');

        $view->set('pages_private'          , $this->getOption('pages_private'));
        $view->set('pages_redirect'         , $this->getOption('pages_redirect'));
        $view->set('pages_redirect_page'    , $this->getOption('pages_redirect_page'));

        $view->set('posts_private'          , $this->getOption('posts_private'));
        $view->set('posts_redirect'         , $this->getOption('posts_redirect'));
        $view->set('posts_redirect_page'    , $this->getOption('posts_redirect_page'));

        $view->set('postspage_excerpts'     , $this->getOption('postspage_excerpts'));
        $view->set('postspage_redirect'     , $this->getOption('postspage_redirect'));
        $view->set('postspage_redirect_page', $this->getOption('postspage_redirect_page'));

        $view->set('archive_excerpts'       , $this->getOption('archive_excerpts'));
        $view->set('archive_redirect'       , $this->getOption('archive_redirect'));
        $view->set('archive_redirect_page'  , $this->getOption('archive_redirect_page'));

        $view->set('search_excerpts'        , $this->getOption('search_excerpts'));
        $view->set('search_redirect'        , $this->getOption('search_redirect'));
        $view->set('search_redirect_page'   , $this->getOption('search_redirect_page'));

        $view->set('rss_excerpts'           , $this->getOption('rss_excerpts'));

        $view->render();
    }

    /**
     * Render the metabox content for this plugin on the Page editing interface.
     *
     * @param StdClass $object The object representing the page type
     * @param array $box An array containing the id, title and callback used when
     *                   registering the meta box being displayed.
     */
    function renderPageMetaBox($object, $box)
    {
        $view = new MemberAccess_Structure_View('metabox-page.phtml');
        $view->set('plugin_label', 'member_access');
        $view->set('visibility'  , $object->{'member_access_visibility'});

        if ($this->getOption('pages_private')) {
            $view->set('current_state_message', __(
                'By default, pages are currently visible only to members. You '
              . 'can override that here or continue to have this page honor the '
              . 'default visibility settings.'
            , 'member_access'));
        } else {
            $view->set('current_state_message', __(
                'By default, pages are currently visible to everyone. You '
              . 'can override that here or continue to have this page honor the '
              . 'default visibility settings.'
            , 'member_access'));
        }

        $view->render();
    }

    /**
     * Render the metabox content for this plugin on the Post editing interface.
     *
     * @param StdClass $object The object representing the page type
     * @param array $box An array containing the id, title and callback used when
     *                   registering the meta box being displayed.
     */
    function renderPostMetaBox($object, $box)
    {
        $view = new MemberAccess_Structure_View('metabox-post.phtml');
        $view->set('plugin_label', 'member_access');
        $view->set('visibility'  , $object->{'member_access_visibility'});

        if ($this->getOption('posts_private')) {
            $view->set('current_state_message', __(
                'By default, posts are currently visible only to members. You '
              . 'can override that here or continue to have this post honor the '
              . 'default visibility settings.'
            , 'member_access'));
        } else {
            $view->set('current_state_message', __(
                'By default, posts are currently visible to everyone. You '
              . 'can override that here or continue to have this post honor the '
              . 'default visibility settings.'
            , 'member_access'));
        }

        $view->render();
    }

    /**
     * Action hook callback meant to be used with 'admin_print_scripts*' hooks.
     * This callback renders any javascript and css needed for the options page
     * of this plugin.
     */
    function renderAdminScripts()
    {
        $view = new MemberAccess_Structure_View('options-scripts.phtml');
        $view->set('plugin_label', 'member_access');
        $view->render();
    }

    /**
     * Action hook callback meant to be used with the 'in_admin_footer' hook.
     * This callback renders plugin author information into the admin footer.
     * Whenever possible, this should only be used on the admin page for this
     * plugin.
     */
    function renderAdminFooter()
    {
        $view = new MemberAccess_Structure_View('options-footer.phtml');
        $view->set('plugin_href'   , 'http://www.chrisabernethy.com/wordpress-plugins/member-access/');
        $view->set('plugin_text'   , 'Member Access');
        $view->set('plugin_version', '1.1.3');
        $view->set('author_href'   , 'http://www.chrisabernethy.com/');
        $view->set('author_text'   , 'Chris Abernethy');
        $view->render();
    }

    /**************************************************************************/
    /*                            Utility Methods                             */
    /**************************************************************************/

    /**
     * Render the given message using the message.phtml partial. This is typically
     * used to render confirmation messages in the admin area.
     *
     * @param string $message The message to display.
     */
    function _messageHelper($message)
    {
        $view = new MemberAccess_Structure_View('message.phtml');
        $view->set('message', $message);
        $view->render();
    }

    /**
     * Send the user to the redirection target. If the redirection target is
     * not available (it's private, or it no longer exists), send the user to
     * the wp-login.php page.
     *
     * This method is intended to be used when an anonymous user attempts to
     * access a page or post that is only available to logged-in users.
     *
     * param integer $page_id The ID of the redirection target page.
     */
    function _redirect($page_id)
    {
        $proto       = is_ssl() ? 'https://' : 'http://';
        $redirect_to = urlencode($proto . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        $page = get_page($page_id);
        if (null === $page || MemberAccess::isPrivate($page)) {
            wp_redirect(site_url("wp-login.php?redirect_to=$redirect_to"));
            exit;
        }

        wp_redirect(get_permalink($page->ID));
        exit;
    }

    /**
     * Clear the privacy override settings for the given post type.
     *
     * @param string $post_type
     */
    function _clearOverrides($post_type)
    {
        global $wpdb;

        $result = $wpdb->query(sprintf(
            "UPDATE %s SET %s = NULL WHERE post_type = '%s'"
          , $wpdb->posts
          , $wpdb->escape('member_access_visibility')
          , $wpdb->escape($post_type)
        ));

        if (false === $result) {
            $this->_messageHelper(__('A database error was encountered, overrides have not been cleared.', 'member_access'));
        } else {
            $this->_messageHelper(__('Overrides have been cleared.', 'member_access'));
        }
    }

    /**
     * Determine whether or not the current visibility for the given post is
     * set to 'private'. This method takes into account both the default
     * privacy setting for the type of post given, as well as any override
     * setting on the post itself.
     *
     * If there is not override setting on the given post, and no default has
     * been established for the post type, the post is considered 'public' and
     * false will be returned. This can happen if the post is a type that is not
     * managed by this plugin, either by design or following the introduction of
     * a new post type by WordPress.
     *
     * This method is meant to be called as a static method.
     *
     * @param integer $post_id
     * @return boolean
     */
    function isPrivate($post_id)
    {
        $post = get_post($post_id);
        if (null === $post) {
            return false;
        }

        $plugin = MemberAccess::instance();

        $visibility = $post->{'member_access_visibility'};
        if ('default' === $visibility) {
            if ('post' == $post->post_type && $plugin->getOption('posts_private')) {
                $visibility = 'private';
            }
            if ('page' == $post->post_type && $plugin->getOption('pages_private')) {
                $visibility = 'private';
            }
        }
        return 'private' === $visibility;
    }

    /**
     * This accessor grants read access to the internal options object so that
     * the isPrivate method can check option values when it is called as a
     * static method.
     *
     * @param string $option_name
     * @return Mixed
     */
    function getOption($option_name)
    {
        return $this->_options->get($option_name);
    }

    /**
     * This accessor grants write access to the internal options object so that
     * option values can be changed.
     *
     * @param string $option_name
     * @param mixed $option_value
     */
    function setOption($option_name, $option_value)
    {
        $this->_options->set($option_name, $option_value);
    }

};

/**
 * This function allows template developers to determine whether or not a post
 * is viewable only to members, and take appropriate action. It is declared as
 * a stand-alone function so that function_exists() can be used to check for it.
 *
 * @param integer $post_id
 * @return boolean
 */
function member_access_is_private($post_id)
{
    return MemberAccess::isPrivate($post_id);
}

/* EOF */