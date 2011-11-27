<?php
class Theme_admin {
	function init(){
		/* Load functions for admin. */
		$this->functions();
		/* Create theme options menu */
		add_action('admin_menu', array(&$this,'menus'));
		/* Manage custom post type */
		$this->types();
		/* Create post type meta box. */
		$this->metaboxes();
	}
	function functions(){
		require_once(THEME_ADMIN_FUNCTIONS .'/common.php');
		require_once(THEME_ADMIN_FUNCTIONS .'/head.php');
		//enable option image uploader support
		require_once(THEME_ADMIN_FUNCTIONS .'/option-media-upload.php');
	}
	/**
	 * Create theme options menu
	 */
	function menus(){
		add_menu_page(THEME_NAME, THEME_NAME, 10, 'theme_general', array(&$this,'_load_option_page'),THEME_ADMIN_ASSETS_URI .'/images/striking_hover.png');
		add_submenu_page('theme_general', 'General', 'General', 10, 'theme_general', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Color', 'Color', 10, 'theme_color', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Font', 'Font', 10, 'theme_font', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Slider Show', 'Slideshow', 10, 'theme_slideshow', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Sidebar', 'Sidebar', 10, 'theme_sidebar', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Image', 'Image', 10, 'theme_image', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Video', 'Video', 10, 'theme_video', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Homepage', 'Homepage', 10, 'theme_homepage', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Blog', 'Blog', 10, 'theme_blog', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Portfolio', 'Portfolio', 10, 'theme_portfolio', array(&$this,'_load_option_page'));
		add_submenu_page('theme_general', 'Footer', 'Footer', 10, 'theme_footer', array(&$this,'_load_option_page'));
	}
	/**
	 * call and display the requested options page
 	 */
	function _load_option_page(){
		include_once (THEME_HELPERS . '/optionGenerator.php');
		$page = include(THEME_ADMIN_OPTIONS . "/" . $_GET['page'] . '.php');
		if($page['auto']){
			new optionGenerator($page['name'],$page['options']);
		}
	}
	/**
	 * Manage custom post type.
	 */
	function types(){
		require_once (THEME_ADMIN_TYPES . '/portfolio.php');
		//require_once (THEME_ADMIN_TYPES . '/slideshow.php');
		//require_once (THEME_ADMIN_TYPES . '/gallery.php');
	}
	/**
	 * Create post type metabox.
	 */
	function metaboxes(){
		require_once (THEME_HELPERS . '/metaboxesGenerator.php');
		require_once (THEME_ADMIN_METABOXES . '/shortcode.php');
		require_once (THEME_ADMIN_METABOXES . '/page_general.php');
		require_once (THEME_ADMIN_METABOXES . '/slideshow.php');
		require_once (THEME_ADMIN_METABOXES . '/anything_slider.php');
		require_once (THEME_ADMIN_METABOXES . '/portfolio.php');
		//require_once (THEME_ADMIN_METABOXES . '/gallery.php');
		require_once (THEME_ADMIN_METABOXES . '/single.php');
		//require_once (THEME_ADMIN_METABOXES . '/footerlink.php');
	}
}
