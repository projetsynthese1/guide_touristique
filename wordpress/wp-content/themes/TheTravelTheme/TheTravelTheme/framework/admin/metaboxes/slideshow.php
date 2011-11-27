<?php
$config = array(
	'title' => __('SlideShow Item Options','striking_admin'),
	'id' => 'slideshow',
	'pages' => array('slideshow'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$options = array(
	array(
		"name" => __("Description (optional)",'striking_admin'),
		"desc" => __("The description of the slider item.",'striking_admin'),
		"id" => "_description",
		"default" => "",
		"rows" => "2",
		"type" => "textarea"
	),
	array(
		"name" => __("URL (optional)",'striking_admin'),
		"desc" => __("The url that the slider item linked to.",'striking_admin'),
		"id" => "_link_to",
		"default" => "",
		"type" => "superlink"		
	),
);
new metaboxesGenerator($config,$options);
