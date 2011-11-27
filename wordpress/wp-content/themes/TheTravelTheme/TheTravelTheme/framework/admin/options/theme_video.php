<?php 
$options = array(
	array(
		"name" => __("Video Sizes",'striking_admin'),
		"desc" => __("The options listed below determine the dimensions in pixels to use in the shortcode of videos.",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Html5 video",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "html5_width",
			"default" => 630,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "html5_height",
			"default" => 355,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Flash",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "flash_width",
			"default" => 630,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "flash_height",
			"default" => 355,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("YouTube",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "youbube_width",
			"default" => 630,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "youbube_height",
			"default" => 380,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Vimeo",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "vimeo_width",
			"default" => 630,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "vimeo_height",
			"default" => 355,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Dailymotion",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "dailymotion_width",
			"default" => 630,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "dailymotion_height",
			"default" => 355,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'video',
	'options' => $options
);
