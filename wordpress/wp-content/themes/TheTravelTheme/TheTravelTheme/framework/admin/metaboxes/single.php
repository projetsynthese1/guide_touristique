<?php
$config = array(
	'title' => __('Blog Single Options','striking_admin'),
	'id' => 'single',
	'pages' => array('post'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$options = array(
	array(
		"name" => __("Layout",'striking_admin'),
		"desc" => __("It will override the global blog single layout setting."),
		"id" => "_layout",
		"default" => 'default',
		"options" => array(
			"default" => __('Default','striking_admin'),
			"full" => __('Full Width','striking_admin'),
			"right" => __('Right Sidebar','striking_admin'),
			"left" => __('Left Sidebar','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Featured Box",'striking_admin'),
		"desc" => __("Whether to dispaly Featured Box in Single Blog page. This will override the global configuration",'striking_admin'),
		"id" => "_featured_box",
		"default" => 'default',
		"options" => array(
			"default" => __('Default','striking_admin'),
			"true" => __('Visible','striking_admin'),
			"false" => __('Hidden','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Featured Video",'striking_admin'),
		"desc" => __("Youtube video. e.g. (http://www.youtube.com/watch?v=xxxxxx)",'striking_admin'),
		"id" => "_featured_video",
		"default" => '',
		"class" => 'full',
		"type" => "text",
	),
);
new metaboxesGenerator($config,$options);
