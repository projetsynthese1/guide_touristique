=== Plugin Name ===
Contributors: brownoxford
Donate link: http://www.chrisabernethy.com/donate/
Tags: access, posts, pages, restrict, admin, user, members
Requires at least: 2.8.4
Tested up to: 3.0.1
Stable tag: 1.1.3

Member Access is a WordPress plugin that allows an administrator to require that users be logged-in in order to view certain posts and pages.

== Description ==

Please [report any bugs](http://www.chrisabernethy.com/contact/ "Report Bugs") and feel free to [suggest new features](http://www.chrisabernethy.com/contact/ "Suggest Features").

Member Access allows a WordPress administrator to make individual posts and pages accessible only to logged-in members. Member Access allows global configuration so that all posts or pages can be viewable by everyone (the default) or only by members, and it also allows each post and page to override the global setting.

WordPress pages which display multiple posts, such as search results, archives and RSS feeds, can be configured to either omit entirely content that is only available to members or to include an excerpt for that content to entice non-members to sign-up.

Template developers can take advantage of the `members_access_is_private` template tag to make custom template modifications to further configure the display of content that is viewable only to members.

Non-members can be redirected to the WordPress login page, or to a page of the administrators choosing, when they access content intended for members. Redirection can also be configured to occur if generated archive or search result pages contain only member content.

More info:

* [Member Access](http://www.chrisabernethy.com/wordpress-plugins/member-access/ "Member Access") plugin.
* Check out the other [WordPress plugins](http://www.chrisabernethy.com/wordpress-plugins/ "Other WordPress Plugins by Chris Abernethy") by the same author.
* To be notified of plugin updates, [follow me on Twitter](http://twitter.com/brownoxford "Follow me on Twitter") or [join the mailing list!](http://eepurl.com/HsV3 "Subscribe to the mailing list!")

== Installation ==

Installing Member Access is easy:

* Download and unzip the plugin.
* Copy the member_access folder to the plugins directory of your blog.
* Enable the plugin in your admin panel.
* An options panel will appear under Plugins.
* Choose the settings you want.

== Screenshots ==

1. This screenshot shows the Member Access options screen.
2. This screenshot shows the Write Post interface where global settings can be overridden for a single post.
3. This screenshot is from the Manage Posts interface. The 'Visibility' column shows the visibility status for each listed post.

== Localization ==

**NOTE:** If you have previously sent me a translation file, [please resend it](mailto:wordpress@chrisabernethy.com "Resend localization files"). All stored translation files were lost!

If you are interested in contributing to the localization of Member Access, please [contact me](http://www.chrisabernethy.com/contact/ "Help Localize Member Access") and let me know which language you would like to translate. All help is greatly appreciated!

Localization Contributors:

* German Translation: [Felix Triller](http://felixtriller.de/ "Felix Triller")
* Danish Translation: [Jakob Smith](http://omkalfatring.dk/ "Jakob Smith")

Some resources on localization:

* [I18n for WordPress Developers](http://codex.wordpress.org/I18n_for_WordPress_Developers "I18n for WordPress Developers")
* [How to Internationalize Your WordPress Plugin](http://www.symbolcraft.com/blog/how_to_i18n_your_wordpress_plugin/29/ "How to Internationalize Your WordPress Plugin")

== Template Developers ==

This plugin provides the template tag `member_access_is_private()` that can be used to determine whether or not a post should be visible only to members. You can use this tag in your templates to add custom styles to posts that are not available to the general public. For example:

`<?php if (have_posts()): while (have_posts()): the_post() ?>
    <?php if (function_exists(member_access_is_private) && member_access_is_private(get_the_ID())): ?>
    <div class="members-only">
    <?endif;?>
        <h1 class="post_title"><?php the_title(); ?></h1>
        <?php the_content(); ?>
    <?php if (function_exists(member_access_is_private) && member_access_is_private(get_the_ID())): ?>
    </div>
    <?endif;?>
<?php endwhile; endif; ?>`

You should also keep in mind that calls to `the_content()` from within the loop may instead function as though `the_excerpt()` was called if the administrator has configured the plugin to show excerpts for non-public content.

== More Information ==

* For more info, version history, etc. check out the page on my site about the [Member Access plugin](http://www.chrisabernethy.com/wordpress-plugins/member-access/ "Member Access"). 
* To check out the other WordPress plugins I wrote, visit my [WordPress plugins](http://www.chrisabernethy.com/wordpress-plugins/ "Other WordPress Plugins by Chris Abernethy") page.
* For updates about this plugin and the other plugins that I maintain, read my [consulting blog](http://www.chrisabernethy.com/ "Chris Abernethy"), [follow me on Twitter](http://twitter.com/brownoxford "Follow me on Twitter!"), or [join the mailing list!](http://eepurl.com/HsV3 "Subscribe to the mailing list!")

== Changelog ==

= 0.1 =
* Initial Release.

= 0.1.1 =
* Code still includes use of 'self' keyword
* Call to a member function query() on a non-object

= 0.1.2 =
* Settings link on plugin page doesn't display
* Object overloading does not work by default in PHP4

= 0.1.3 =
* Plugin still broken for some PHP4 installations

= 0.1.4 =
* Implemented i18n hooks and added default messages.pot for translators.
* Fixed problem with quick-edit nuking per-post plugin settings.
* Validated for use with WordPress 2.7

= 1.0 =
* Added uninstall functionality
* Validated for use with WordPress 2.7.1

= 1.1 =
* Added German translation by [Felix Triller](http://felixtriller.de/ "Felix Triller")

= 1.1.1 =
* Tested to WordPress v2.8.4
* Added Changelog
* Removed filter loop which cased script execution to halt, resulting in blank pages. Thanks [James Turner](http://www.jamesturner.co.nz/ "James Turner").
* Only private posts display excerpts on archive pages (instead of all posts). Thanks [James Turner](http://www.jamesturner.co.nz/ "James Turner").
* Posts Page redirect pulldown was not correctly showing current option. Thanks [maestrobob](http://wordpress.org/support/profile/1139184 "maestrobob").
* Version bump to 1.1.1

= 1.1.2 =
* Verified compatibility with WordPress 3.0

= 1.1.3 =
* Added Danish translation from Jakob Smith
* Fix localization issue preventing use of configured WP_LANG language
* Move settings option from "Plugins" to "Settings" menu in admin panel
* Replace use of deprecated wp_specialchars with esc_html
* Replace use of deprecated attribute_escape with esc_attr

== Upgrade Notice ==

= 1.1.2 =
Compatibility testing up to WordPress 3.0. NOTE: If you sent me a translation file, it was lost. Please resend!
