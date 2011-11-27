<?php
$config = array(
	'title' => sprintf(__('%s Page General Options','striking_admin'),THEME_NAME),
	'id' => 'page_general',
	'pages' => array('page','portfolio'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
function get_sidebar_options(){
	$sidebars = theme_get_option('sidebar','sidebars');
	if(!empty($sidebars)){
		$sidebars_array = explode(',',$sidebars);
		$options = array();
		foreach ($sidebars_array as $sidebar){
			$options[$sidebar] = $sidebar;
		}
		return $options;
	}else{
		return array();
	}
}
$options = array(
	array(
		"name" => __("Header Introduce Text Type",'striking_admin'),
		"desc" => __("Here you can override the general header Introduce text options on a post by post basis.",'striking_admin'),
		"id" => "_introduce_text_type",
		"options" => array(
			"default" => "Default",
			"title" => "Title only",
			"custom" => "Custom text",
			"title_custom" => "Title & custom text",
			"disable" => "Disable",
		),
		"default" => "default",
		"type" => "radio"
	),
	array(
		"name" => __("Custom Header Introduce Text",'striking_admin'),
		"desc" => __('If the "custom text" option is selected above any text you enter here will override your general custom header teaser text option.','striking_admin'),
		"id" => "_custom_introduce_text",
		"rows" => "2",
		"default" => "",
		"type" => "textarea"
	),
	array(
		"name" => __("Feature Background Color",'striking_admin'),
		"desc" => __("If you specify a color below, this will override the global configuration. Set transparent to disable this.",'striking_admin'),
		"id" => "_introduce_background_color",
		"default" => "",
		"type" => "color"		
	),
	array(
		"name" => __("Disable Breadcrumbs",'striking_admin'),
		"desc" => __('Here you can disable breadcrumbs on a post by post basis. Alternatively you can globally disable breadcrumbs under the "General Settings" tab in your theme\'s option panel.','striking_admin'),
		"id" => "_disable_breadcrumb",
		"label" => "Check to disable breadcrumbs on this post",
		"default" => "",
		"type" => "toggle"
	),
	array(
		"name" => __("Custom Sidebar",'striking_admin'),
		"desc" => __("Select the custom sidebar that you'd like to be displayed on this.<br />Note: you will need to first create a custom sidebar in your themes option panel before it will show up here.",'striking_admin'),
		"id" => "_sidebar",
		"prompt" => __("Choose one..",'striking_admin'),
		"default" => '',
		"options" => get_sidebar_options(),
		"type" => "select",
	),
);
new metaboxesGenerator($config,$options);
