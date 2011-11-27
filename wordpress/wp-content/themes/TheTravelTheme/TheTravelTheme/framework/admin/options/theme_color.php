<?php
$options = array(
	array(
		"name" => __("Color Setting",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("General Setting",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Header Background Color",'striking_admin'),
			"desc" => "",
			"id" => "header_bg",
			"default" => "#fefefe",
			"type" => "color"
		),
		array(
			"name" => __("Feature Area Background Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_area_bg",
			"default" => "#fefefe",
			"type" => "color"
		),
		array(
			"name" => __("Feature Title Background Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_bg",
			"default" => "#7f7f7f",
			"type" => "color"
		),
		array(
			"name" => __("Feature Title Background Opacity",'striking_admin'),
			"id" => "feature_bg_Opacity",
			"min" => "0",
			"max" => "1",
			"step" => "0.1",
			"default" => "0.8",
			"type" => "range"
		),
		array(
			"name" => __("Page Background Color",'striking_admin'),
			"desc" => "",
			"id" => "page_bg",
			"default" => "#fefefe",
			"type" => "color"
		),
		array(
			"name" => __("Footer Background Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_bg",
			"default" => "#000000",
			"type" => "color"
		),
		array(
			"name" => __("Sub Footer Background Color",'striking_admin'),
			"desc" => "",
			"id" => "sub_footer_bg",
			"default" => "",
			"type" => "color"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Header Setting",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Logo Text Color",'striking_admin'),
			"desc" => "",
			"id" => "site_name",
			"default" => "#444444",
			"type" => "color"
		),
		array(
			"name" => __("Logo Description Text Color",'striking_admin'),
			"desc" => "",
			"id" => "site_description",
			"default" => "#444444",
			"type" => "color"
		),
		array(
			"name" => __("Top Level Menu Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_top",
			"default" => "#000000",
			"type" => "color"
		),
		array(
			"name" => __("Top Level Current Menu Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_top_current",
			"default" => "#000000",
			"type" => "color"
		),
		array(
			"name" => __("Top Level Menu Active Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_top_active",
			"default" => "#000000",
			"type" => "color"
		),
		array(
			"name" => __("Sub Level Menu Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_sub",
			"default" => "#000000",
			"type" => "color"
		),
		array(
			"name" => __("Sub Level Menu Active Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_sub_active",
			"default" => "#000000",
			"type" => "color"
		),
		array(
			"name" => __("Sub Level Menu Background Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_sub_background",
			"default" => "#f5f5f5",
			"type" => "color"
		),
		array(
			"name" => __("Sub Level Menu Hover Background Color",'striking_admin'),
			"desc" => "",
			"id" => "menu_sub_hover_background",
			"default" => "#dddddd",
			"type" => "color"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Feature Setting",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Feature Box Text Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_box_text",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Feature Box Category Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_box_category",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Feature Box Category Active Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_box_category_active",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Feature Header Text Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_header",
			"default" => "#ffffff",
			"type" => "color"
		),	
		array(
			"name" => __("Feature Introduce Text Color",'striking_admin'),
			"desc" => "",
			"id" => "feature_introduce",
			"default" => "#ffffff",
			"type" => "color"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Page Setting",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Page Text Color",'striking_admin'),
			"desc" => "",
			"id" => "page",
			"default" => "#333333",
			"type" => "color"
		),
		array(
			"name" => __("Page Header Color",'striking_admin'),
			"desc" => "",
			"id" => "page_header",
			"default" => "#333333",
			"type" => "color"
		),
		array(
			"name" => __("Page H1 Color",'striking_admin'),
			"desc" => "",
			"id" => "page_h1",
			"default" => "",
			"type" => "color"
		),
		array(
			"name" => __("Page H2 Color",'striking_admin'),
			"desc" => "",
			"id" => "page_h2",
			"default" => "",
			"type" => "color"
		),
		array(
			"name" => __("Page H3 Color",'striking_admin'),
			"desc" => "",
			"id" => "page_h3",
			"default" => "",
			"type" => "color"
		),
		array(
			"name" => __("Page H4 Color",'striking_admin'),
			"desc" => "",
			"id" => "page_h4",
			"default" => "",
			"type" => "color"
		),
		array(
			"name" => __("Page H5 Color",'striking_admin'),
			"desc" => "",
			"id" => "page_h5",
			"default" => "",
			"type" => "color"
		),
		array(
			"name" => __("Page H6 Color",'striking_admin'),
			"desc" => "",
			"id" => "page_h6",
			"default" => "",
			"type" => "color"
		),
		array(
			"name" => __("Page Link Color",'striking_admin'),
			"desc" => "",
			"id" => "page_link",
			"default" => "#666666",
			"type" => "color"
		),
		array(
			"name" => __("Page Link Active Color",'striking_admin'),
			"desc" => "",
			"id" => "page_link_active",
			"default" => "#333333",
			"type" => "color"
		),
		array(
			"name" => __("Portfolio Sortable Header Background Color",'striking_admin'),
			"desc" => "",
			"id" => "portfolio_header_bg",
			"default" => "#eeeeee",
			"type" => "color"
		),
		array(
			"name" => __("Portfolio Sortable Header Text Color",'striking_admin'),
			"desc" => "",
			"id" => "portfolio_header_text",
			"default" => "#666666",
			"type" => "color"
		),
		array(
			"name" => __("Sidebar Link Color",'striking_admin'),
			"desc" => "",
			"id" => "sidebar_link",
			"default" => "#666666",
			"type" => "color"
		),
		array(
			"name" => __("Sidebar Link Active Color",'striking_admin'),
			"desc" => "",
			"id" => "sidebar_link_active",
			"default" => "#333333",
			"type" => "color"
		),
		array(
			"name" => __("Breadcrumbs Text Color",'striking_admin'),
			"desc" => "",
			"id" => "breadcrumbs",
			"default" => "#999999",
			"type" => "color"
		),
		array(
			"name" => __("Breadcrumbs Link Color",'striking_admin'),
			"desc" => "",
			"id" => "breadcrumbs_link",
			"default" => "#999999",
			"type" => "color"
		),
		array(
			"name" => __("Breadcrumbs Link Active Color",'striking_admin'),
			"desc" => "",
			"id" => "breadcrumbs_active",
			"default" => "#999999",
			"type" => "color"
		),
		array(
			"name" => __("Divider Line Color",'striking_admin'),
			"desc" => "",
			"id" => "divider_line",
			"default" => "#eeeeee",
			"type" => "color"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Footer Setting",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Footer Text Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_text",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Footer Widget Title Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_title",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Footer Link Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_link",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Footer Link Active Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_link_active",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Copyright Text Color",'striking_admin'),
			"desc" => "",
			"id" => "copyright",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Footer Menu Text Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_menu",
			"default" => "#ffffff",
			"type" => "color"
		),
		array(
			"name" => __("Footer Menu Active Color",'striking_admin'),
			"desc" => "",
			"id" => "footer_menu_active",
			"default" => "#ffffff",
			"type" => "color"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'color',
	'options' => $options
);
