<?php
$config = array(
	'title' => __('Portfolio Item Options','striking_admin'),
	'id' => 'portfolio',
	'pages' => array('portfolio'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$options = array(
	array(
		"name" => __("Layout",'striking_admin'),
		"desc" => __("It will override the global portfolio item layout setting."),
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
		"name" => __("Portfolio Type",'striking_admin'),
		"desc" => sprintf(__("%s supports image and video for demonstrating the portfolio in the Lightbox. If the type is document, the thumbnail image is link to page of portfolio",'striking_admin'),THEME_NAME),
		"id" => "_type",
		"default" => 'image',
		"options" => array(
			"image" => __('Image','striking_admin'),
			"video" => __('Video','striking_admin'),
			"doc" => __('Document','striking_admin'),
			"link" => __('Link','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Fullsize Image for Lightbox (optional)",'striking_admin'),
		"desc" => __("The fullsize images you would like to use for the portfolio lightbox pop-up demonstrate.If not assigned, it will use featured image instead.",'striking_admin'),
		"id" => "_image",
		"button" => "Insert Image",
		"default" => '',
		"type" => "Upload",
	),
	array(
		"name" => __("Video Link for Lightbox",'striking_admin'),
		"desc" => __("Paste the full url of the Flash(YouTube or Vimeo etc).Only necessary when the lightbox type is video.",'striking_admin'),
		"size" => 30,
		"id" => "_video",
		"default" => '',
		"class" => 'full',
		"type" => "text",
	),
	array(
		"name" => __("Link for Portfolio item",'striking_admin'),
		"desc" => __("The url that the portfolio item linked to. It only available if Portfolio type set to Link.",'striking_admin'),
		"id" => "_link",
		"default" => "",
		"shows" => array('page','cat','post','manually'),
		"type" => "superlink"	
	),
	array(
		"name" => __("Thumbnail Icon",'striking_admin'),
		"desc" => __("It will override portfolio type's defualt icon setting.",'striking_admin'),
		"id" => "_icon",
		"default" => 'default',
		"options" => array(
			"default" => __('Default','striking_admin'),
			"zoom" => __('Image','striking_admin'),
			"play" => __('Video','striking_admin'),
			"doc" => __('Document','striking_admin'),
			"link" => __('Link','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Enable Read More",'striking_admin'),
		"desc" => __("if this is on, the read more button will show.",'striking_admin'),
		"id" => "_more",
		"default" => "1",
		"type" => "toggle"
	),
	array(
		"name" => __("Link for Read More",'striking_admin'),
		"id" => "_more_link",
		"default" => "",
		"shows" => array('page','cat','post','manually'),
		"type" => "superlink"
	),
);
new metaboxesGenerator($config,$options);
