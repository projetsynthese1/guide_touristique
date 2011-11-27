<?php
$options = array(
	array(
		"name" => __("Blog",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Blog General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Blog Page",'striking_admin'),
			"desc" => __("The page you choose here will display the blog",'striking_admin'),
			"id" => "blog_page",
			"target" => 'page',
			"default" => "",
			"prompt" => __("Choose page..",'striking_admin'),
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
			"name" => __("Featured Image Type",'striking_admin'),
			"desc" => "",
			"id" => "featured_image_type",
			"default" => 'full',
			"options" => array(
				"full" => __('Full Width','striking_admin'),
				"left" => __('Left Float','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Display Full Blog Posts",'striking_admin'),
			"desc" => __("This option determinate whether to display full blog posts in index page.",'striking_admin'),
			"id" => "display_full",
			"default" => 0,
			"type" => "toggle"
		),
		array(
			"name" => __("Exclude Categories",'striking_admin'),
			"desc" => __("The blog Page usually displays all Categorys, since sometimes you want to exclude some of these categories you can exclude multiple categories here:",'striking_admin'),
			"id" => "exclude_categorys",
			"default" => array(),
			"target" => "cat",
			"prompt" => __("Choose category..",'striking_admin'),
			"type" => "multidropdown"
		),
		array(
			"name" => __("Gap Between Posts",'striking_admin'),
			"desc" => "",
			"id" => "posts_gap",
			"min" => "0",
			"max" => "200",
			"step" => "1",
			"unit" => 'px',
			"default" => "80",
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Single Blog",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Layout",'striking_admin'),
			"desc" => "",
			"id" => "single_layout",
			"default" => 'right',
			"options" => array(
				"full" => __('Full Width','striking_admin'),
				"right" => __('Right Sidebar','striking_admin'),
				"left" => __('Left Sidebar','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Featured Box",'striking_admin'),
			"desc" => __("If this option is on, Featured Box will appear in Single Blog page.",'striking_admin'),
			"id" => "featured_box",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Feature Box Height",'striking_admin'),
			"desc" => __("Height of Feature Box.",'striking_admin'),
			"id" => "feature_box_height",
			"min" => "60",
			"max" => "440",
			"step" => "1",
			"unit" => 'px',
			"default" => "440",
			"type" => "range"
		),
		array(
			"name" => __("About Author Box",'striking_admin'),
			"desc" => "",
			"id" => "author",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Related & Popular Post Module",'striking_admin'),
			"desc" => "",
			"id" => "related_popular",
			"default" => 0,
			"type" => "toggle"
		),
		array(
			"name" => __("Related Posts Module",'striking_admin'),
			"desc" => "",
			"id" => "related_posts",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Related Posts Module Type",'striking_admin'),
			"desc" => "",
			"id" => "related_posts_type",
			"default" => 'tags',
			"options" => array(
				"category" => __('Category','striking_admin'),
				"tags" => __('Tags','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Previous & Next Navigation",'striking_admin'),
			"desc" => "",
			"id" => "entry_navigation",
			"default" => 0,
			"type" => "toggle"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Meta informations",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Category",'striking_admin'),
			"desc" => "",
			"id" => "meta_category",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Tags",'striking_admin'),
			"desc" => "",
			"id" => "meta_tags",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Author",'striking_admin'),
			"desc" => "",
			"id" => "meta_author",
			"default" => 0,
			"type" => "toggle"
		),
		array(
			"name" => __("Date",'striking_admin'),
			"desc" => "",
			"id" => "meta_date",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Comment",'striking_admin'),
			"desc" => "",
			"id" => "meta_comment",
			"default" => 1,
			"type" => "toggle"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Full Width Featured Image",'striking_admin'),
		"type" => "start"
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
		"name" => __("Left Float Featured Image",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"id" => "left_width",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "200",
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"id" => "left_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"default" => "200",
			"type" => "range"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'blog',
	'options' => $options
);
