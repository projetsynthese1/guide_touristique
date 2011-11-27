<?php
if (! function_exists("theme_homepage_shortcode_generator")){
	function theme_homepage_shortcode_generator(){
		require_once (THEME_HELPERS . '/homepageShortcodesGenerator.php');
		$shortcodes = include(THEME_ADMIN_METABOXES . '/shortcode_options.php');
		echo '<tr colspan="2"><td>';
		echo '<table cellspacing="0" class="widefat homepage-shortcode"><thead><tr><th scope="row">'.__('Shortcode Generator','striking_admin').'</th></tr></thead><tbody><tr><td>';
		new homepageShortcodesGenerator($shortcodes);
		echo '</td></tr></tbody></table>';
		echo '</td></tr>';
	}
}
$options = array(
	array(
		"name" => __("Homepage",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Homepage General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Home Page",'striking_admin'),
			"desc" => __("The page you choose here will display in the homepage. You do not needed to specify a page for homepage unless you want multi-language support.",'striking_admin'),
			"id" => "home_page",
			"target" => 'page',
			"default" => "",
			"prompt" => __("None",'striking_admin'),
			"type" => "select",
		),
		array(
			"name" => __("Layout",'striking_admin'),
			"desc" => "",
			"id" => "layout",
			"default" => 'right',
			"options" => array(
				"full" => __('Full Width','striking_admin'),
				"right" => __('Right Sidebar','striking_admin'),
				"left" => __('Left Sidebar','striking_admin'),
			),
			"type" => "select",
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Homepage Content Editor",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Homepage Content Editor",'striking_admin'),
			"desc" => __("The text you enter here will display on the homepage",'striking_admin'),
			"id" => "content",
			"default" => '[blog count="10" nopaging="false"]',
			"type" => "editor"
		),
		array(
			"id" => __("shortcode_generator",'striking_admin'),
			"layout" => false,
			"function" => "theme_homepage_shortcode_generator",
			"default" => false,
			"type" => "custom"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'homepage',
	'options' => $options
);
