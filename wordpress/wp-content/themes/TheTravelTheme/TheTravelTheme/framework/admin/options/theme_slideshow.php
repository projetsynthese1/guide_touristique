<?php
$options = array(
	array(
		"name" => __("Slide Show",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Slide Show Settings",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Disable Slide Show",'striking_admin'),
			"desc" => __("If you do not want a home page slide show, turn on the button.",'striking_admin'),
			"id" => "disable_slideshow",
			"default" => 0,
			"type" => "toggle"
		),				
		array(
			"name" => __("Posts Count",'striking_admin'),
			"desc" => "",
			"id" => "count",
			"default" => 5,
			"min" => 1,
			"max" => 10,
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => __("Height of slider.",'striking_admin'),
			"id" => "height",
			"min" => "60",
			"max" => "440",
			"step" => "1",
			"unit" => 'px',
			"default" => "440",
			"type" => "range"
		),
		array(
			"name" => __("Animation Speed",'striking_admin'),
			"desc" => __("Define the duration of the animations.",'striking_admin'),
			"id" => "animSpeed",
			"min" => "200",
			"max" => "3000",
			"step" => "100",
			'unit' => 'miliseconds',
			"default" => "1000",
			"type" => "range"
		),
		array(
			"name" => __("Pause Time",'striking_admin'),
			"desc" => __("Define the delay which each slide will have to wait to be played",'striking_admin'),
			"id" => "pauseTime",
			"min" => "1000",
			"max" => "30000",
			"step" => "500",
			"unit" => 'miliseconds',
			"default" => "5000",
			"type" => "range"
		),
		array(
			"name" => __("Pause On Hover",'striking_admin'),
			"desc" => __("If you want stop animation while hovering, turn on the button.",'striking_admin'),
			"id" => "pauseOnHover",
			"default" => "1",
			"type" => "toggle"
		),
		array(
			"name" => __("AutoPlay",'striking_admin'),
			"desc" => __("If you want slider play automatically, turn on the button.",'striking_admin'),
			"id" => "autoplay",
			"default" => "1",
			"type" => "toggle"
		),
	array(
		"type" => "end"
	),
	
);
return array(
	'auto' => true,
	'name' => 'slideshow',
	'options' => $options
);