<?php
$options = array(
	array(
		"name" => __("Portfolio",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Portfolio General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Display Title",'striking_admin'),
			"desc" => "",
			"id" => "display_title",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Display Description",'striking_admin'),
			"desc" => "",
			"id" => "display_excerpt",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Display More Button",'striking_admin'),
			"desc" => "",
			"id" => "display_more_button",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("More Button Text",'striking_admin'),
			"desc" => "",
			"size" => 30,
			"id" => "more_button_text",
			"default" => 'Read More »',
			"type" => "text",
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Height of Thumbnail",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("One Column",'striking_admin'),
			"desc" => sprintf(__("%s in width",'striking_admin'),'600px'),
			"id" => "1_column_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "350",
			"type" => "range"
		),
		array(
			"name" => __("Two Columns",'striking_admin'),
			"desc" => sprintf(__("%s in width",'striking_admin'),'450px'),
			"id" => "2_columns_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "250",
			"type" => "range"
		),
		array(
			"name" => __("Three Columns",'striking_admin'),
			"desc" => sprintf(__("%s in width",'striking_admin'),'292px'),
			"id" => "3_columns_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "180",
			"type" => "range"
		),
		array(
			"name" => __("Four Columns",'striking_admin'),
			"desc" => sprintf(__("%s in width",'striking_admin'),'217px'),
			"id" => "4_columns_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "150",
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Featured Image",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Featured Image",'striking_admin'),
			"desc" => __("If this option is on, Featured Image will appear in Portfolio Item page.",'striking_admin'),
			"id" => "featured_image",
			"default" => 0,
			"type" => "toggle"
		),
		array(
			"name" => __("Adaptive Height",'striking_admin'),
			"desc" => __("If this option is on, the height of the featured image depand on the scale of the image.",'striking_admin'),
			"id" => "adaptive_height",
			"default" => 0,
			"type" => "toggle"
		),
		array(
			"name" => __("Fixed Height",'striking_admin'),
			"desc" => __("If the option above is off, it will take effect.",'striking_admin'),
			"id" => "fixed_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "250",
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Single Portfolio Item",'striking_admin'),
		"type" => "start"
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
			"name" => __("Previous & Next Navigation",'striking_admin'),
			"desc" => "",
			"id" => "single_navigation",
			"default" => 0,
			"type" => "toggle"
		),
		array(
			"name" => __("Document Type Navigation",'striking_admin'),
			"desc" => "If this option is on, the Previous & Next Navigation will only apply to Document type of Portfolio",
			"id" => "single_doc_navigation",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Enable Comment",'striking_admin'),
			"desc" => "",
			"id" => "enable_comment",
			"default" => 0,
			"type" => "toggle"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'portfolio',
	'options' => $options
);
