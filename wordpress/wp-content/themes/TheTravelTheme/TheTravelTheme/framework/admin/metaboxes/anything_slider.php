<?php
$config = array(
	'title' => __('Anything-Slider Options','striking_admin'),
	'id' => 'anything',
	'pages' => array('slideshow'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$options = array(
	array(
		"name" => __("Type",'striking_admin'),
		"id" => "_anything_type",
		"default" => 'image',
		"options" => array(
			"image" => __('Image with caption','striking_admin'),
			"sidebar" => __('Sidebar','striking_admin'),
			"html" => __('Html','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Image Caption Position",'striking_admin'),
		"id" => "_image_caption_position",
		"default" => 'disable',
		"options" => array(
			"top" => __('Top','striking_admin'),
			"bottom" => __('Bottom','striking_admin'),
			"left" => __('Left','striking_admin'),
			"right" => __('Right','striking_admin'),
			"disable" => __('Disable','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Sidebar Position",'striking_admin'),
		"id" => "_sidebar_position",
		"default" => 'left',
		"options" => array(
			"left" => __('Left','striking_admin'),
			"right" => __('Right','striking_admin'),
		),
		"type" => "select",
	),
	array(
		"name" => __("Stop autoPlay",'striking_admin'),
		"desc" => __("if this is on, anything slider will stop until manual change the slide.",'striking_admin'),
		"id" => "_anything_stop",
		"default" => "0",
		"type" => "toggle"
	),
);
new metaboxesGenerator($config,$options);
