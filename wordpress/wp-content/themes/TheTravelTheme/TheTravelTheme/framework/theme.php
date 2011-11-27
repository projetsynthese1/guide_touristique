<?php
/**
 * Used for the theme's initialization.
 */
class Theme {
	/**
	 * Initializes the theme framework, loads the required files, and calls the
	 * functions needed to run the theme.
	 */
	function init($options) {
		/* Define theme's constants. */
		$this->constants($options);
		/* Add language support. */
		add_action('init',array(&$this, 'language'));
		/* Add theme support. */
		add_action('after_setup_theme', array(&$this, 'supports'));
		/* Load theme's functions. */
		$this->functions();
		/* Register theme's custom post type. */
		$this->types();
		/* Load theme's plugin. */
		$this->plugins();
		$this->shortcodes();
		/* Initialize the theme's widgets. */
		add_action('widgets_init',array(&$this, 'widgets'));
		/* Load admin files. */
        $this->admin();
		//Long posts should require a higher limit, see http://core.trac.wordpress.org/ticket/8553
		@ini_set('pcre.backtrack_limit', 500000);
	}
	/**
	 * Defines the constant paths for use within the theme.
	 */
	function constants($options) {
		define('THEME_NAME', $options['theme_name']);
		define('THEME_SLUG', $options['theme_slug']);
		define('THEME_DIR', get_template_directory());
		define('THEME_URI', get_template_directory_uri());
		define('THEME_FRAMEWORK', THEME_DIR . '/framework');
		define('THEME_PLUGINS', THEME_FRAMEWORK . '/plugins');
		define('THEME_HELPERS', THEME_FRAMEWORK . '/helpers');
		define('THEME_FUNCTIONS', THEME_FRAMEWORK . '/functions');
		define('THEME_TYPES', THEME_FRAMEWORK . '/types');
		define('THEME_WIDGETS', THEME_FRAMEWORK . '/widgets');
		define('THEME_SHORTCODES', THEME_FRAMEWORK . '/shortcodes');
		define('THEME_FONT_URI', THEME_URI . '/fonts');
		define('THEME_FONT_DIR', THEME_DIR . '/fonts');
		define('THEME_INCLUDES', THEME_URI . '/includes');
		define('THEME_IMAGES', THEME_URI . '/images');
		define('THEME_CSS', THEME_URI . '/css');
		define('THEME_JS', THEME_URI . '/js');
		define('THEME_ADMIN', THEME_FRAMEWORK . '/admin');
		define('THEME_ADMIN_TYPES', THEME_ADMIN . '/types');
		define('THEME_ADMIN_AJAX', THEME_ADMIN . '/ajax');
		define('THEME_ADMIN_ASSETS_URI', THEME_URI . '/framework/admin/assets');
		define('THEME_ADMIN_FUNCTIONS', THEME_ADMIN . '/functions');
		define('THEME_ADMIN_OPTIONS', THEME_ADMIN . '/options');
		define('THEME_ADMIN_METABOXES', THEME_ADMIN . '/metaboxes');
	}
	/**
	 * Add theme support.
	 */
	function supports() {
		if (function_exists('add_theme_support')) {
			add_theme_support('custom-header');
			add_theme_support('custom-background');
			//This enables post-thumbnail support for a theme.
			add_theme_support('post-thumbnails', array('post', 'page', 'portfolio', 'slideshow'));
			//This enables the naviagation menu ability. 
			add_theme_support('menus');
			register_nav_menus(array(
				'primary-menu' => __(THEME_NAME . ' Navigation', 'striking_admin' ), 
				'footer-menu' => __(THEME_NAME . ' Footer Menu', 'striking_admin' )
			));
			//This enables post and comment RSS feed links to head. This should be used in place of the deprecated automatic_feed_links.
			add_theme_support('automatic-feed-links');
			// reference to: http://codex.wordpress.org/Function_Reference/add_editor_style
			add_theme_support('editor-style');
		}
	}
	/**
	 * Register the custom post type for the theme.
	 */
	function types() {
		require_once (THEME_TYPES . '/portfolio.php');
		//require_once (THEME_TYPES . '/slideshow.php');
		//require_once (THEME_TYPES . '/gallery.php');
	}
	/**
	 * Loads the core theme functions.
	 */
	function functions() {
		require_once (THEME_FUNCTIONS . '/common.php');
		/* Load theme's options. */
		$this->options();
		require_once (THEME_FUNCTIONS . '/head.php');
		require_once (THEME_FUNCTIONS . '/filter.php');
		require_once (THEME_FUNCTIONS . '/wpml-integration.php');
		require_once (THEME_FUNCTIONS . '/wpml-string.php');
		require_once (THEME_HELPERS . '/themeGenerator.php');
		require_once (THEME_HELPERS . '/sidebarGenerator.php');
	}
	/**
	 * Loads the theme options.
	 */
	function options() {
		global $theme_options;
		$theme_options = array();
		$option_files = array(
			'theme_blog',
			'theme_color',
			'theme_font',
			'theme_footer',
			'theme_general',
			'theme_homepage',
			'theme_image',
			'theme_video',
			'theme_portfolio',
			'theme_sidebar',
			'theme_slideshow',
		);
		foreach($option_files as $file){
			$page = include (THEME_ADMIN_OPTIONS . "/" . $file.'.php');
			$theme_options[$page['name']] = array();
			foreach($page['options'] as $option) {
				if (isset($option['default'])) {
					$theme_options[$page['name']][$option['id']] = $option['default'];
				}
			}
			$theme_options[$page['name']] = array_merge((array) $theme_options[$page['name']], (array) get_option(THEME_SLUG . '_' . $page['name']));
		}
	}
	/**
	 * Load plugins integrated in a theme.
	 */
	function plugins() {
		require_once (THEME_PLUGINS . '/breadcrumbs-plus/breadcrumbs-plus.php');
		require_once (THEME_PLUGINS . '/wp-pagenavi/wp-pagenavi.php');
	}
	/**
	 * Register theme's extra widgets.
	 */
	function widgets() {
		/* Load each widget file. */
		require_once (THEME_WIDGETS . '/subnav.php');
		require_once (THEME_WIDGETS . '/flickr.php');
		require_once (THEME_WIDGETS . '/twitter.php');
		require_once (THEME_WIDGETS . '/social.php');
		require_once (THEME_WIDGETS . '/recent.php');
		require_once (THEME_WIDGETS . '/popular.php');
		require_once (THEME_WIDGETS . '/related.php');
		require_once (THEME_WIDGETS . '/contactform.php');
		require_once (THEME_WIDGETS . '/contactinfo.php');
		require_once (THEME_WIDGETS . '/advertisement-125.php');
		/* Register each widget. */
		register_widget('Theme_Widget_SubNav');
		register_widget('Theme_Widget_Flickr');
		register_widget('Theme_Widget_Twitter');
		register_widget('Theme_Widget_Social');
		register_widget('Theme_Widget_Recent_Posts');
		register_widget('Theme_Widget_Popular_Posts');
		register_widget('Theme_Widget_Related_Posts');
		register_widget('Theme_Widget_Contact_Form');
		register_widget('Theme_Widget_Contact_Info');
		register_widget('Theme_Widget_Advertisement_125');
		if(theme_get_option('general','enable_gmap')){
			require_once (THEME_WIDGETS . '/gmap.php');
			register_widget('Theme_Widget_Gmap');
		}
	}
	/**
	 * Register theme's shortcodes.
	 */
	function shortcodes() {
		require_once (THEME_SHORTCODES . '/columns.php');
		require_once (THEME_SHORTCODES . '/typography.php');
		require_once (THEME_SHORTCODES . '/dividers.php');
		require_once (THEME_SHORTCODES . '/tabs.php');
		require_once (THEME_SHORTCODES . '/boxes.php');
		require_once (THEME_SHORTCODES . '/images.php');
		require_once (THEME_SHORTCODES . '/buttons.php');
		require_once (THEME_SHORTCODES . '/tables.php');
		require_once (THEME_SHORTCODES . '/blog.php');
		require_once (THEME_SHORTCODES . '/portfolios.php');
		require_once (THEME_SHORTCODES . '/widgets.php');
		require_once (THEME_SHORTCODES . '/video.php');
		require_once (THEME_SHORTCODES . '/lightbox.php');
		require_once (THEME_SHORTCODES . '/chart.php');
		require_once (THEME_SHORTCODES . '/sitemap.php');
		if(theme_get_option('general','enable_gmap')){
			require_once (THEME_SHORTCODES . '/gmap.php');
		}
	}
	/**
	 * Load admin files.
	 */
	function admin() {
		if (is_admin()) {
			require_once (THEME_ADMIN . '/admin.php');
			$admin = new Theme_admin();
			$admin->init();
		}
	}
	/**
	 * Make theme available for translation
	 */
	function language(){
		$locale = get_locale();
		if (is_admin()) {
			load_theme_textdomain( 'striking_admin', THEME_ADMIN . '/languages' );
			$locale_file = THEME_ADMIN . "/languages/$locale.php";
		}else{
			load_theme_textdomain( 'striking_front', THEME_DIR . '/languages' );
			$locale_file = THEME_DIR . "/languages/$locale.php";
		}
		if ( is_readable( $locale_file ) ){
			require_once( $locale_file );
		}
	}
}
