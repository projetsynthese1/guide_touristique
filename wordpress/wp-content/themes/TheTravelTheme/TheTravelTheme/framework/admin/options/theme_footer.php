<?php
if (! function_exists("theme_footer_column_option")) {
	function theme_footer_column_option($value, $default) {
		echo '<script type="text/javascript" src="' . THEME_ADMIN_ASSETS_URI . '/js/theme-footer-column.js"></script>';
		echo '<div class="theme-footer-columns">';
		echo '<div>';
		echo '<a href="#" rel="1"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/1.png" /></a>';
		echo '<a href="#" rel="2"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/2.png" /></a>';
		echo '<a href="#" rel="3"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/3.png" /></a>';
		echo '<a href="#" rel="4"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/4.png" /></a>';
		echo '<a href="#" rel="5"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/5.png" /></a>';
		echo '<a href="#" rel="6"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/6.png" /></a>';
		echo '</div><div>';
		echo '<a href="#" rel="half_sub_half"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/half_sub_half.png" /></a>';
		echo '<a href="#"href="#"rel="half_sub_third"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/half_sub_third.png" /></a>';
		echo '<a href="#" rel="third_sub_third"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/third_sub_third.png" /></a>';
		echo '<a href="#" rel="third_sub_fourth"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/third_sub_fourth.png" /></a>';
		echo '</div><div>';
		echo '<a href="#" rel="sub_half_half"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/sub_half_half.png" /></a>';
		echo '<a href="#" rel="sub_third_half"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/sub_third_half.png" /></a>';
		echo '<a href="#" rel="sub_third_third"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/sub_third_third.png" /></a>';
		echo '<a href="#" rel="sub_fourth_third"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/sub_fourth_third.png" /></a>';
		echo '</div>';
		echo '</div>';
		echo '<input type="hidden" value="' . $default . '" name="' . $value['id'] . '" id="' . $value['id'] . '"/>';
	}
}
$options = array(
	array(
		"name" => __("Footer",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Footer",'striking_admin'),
		"type" => "start"
	),
		/*array(
			"name" => __("Footer",'striking_admin'),
			"desc" => __("If you don't want display footer, turn off the button.",'striking_admin'),
			"id" => "footer",
			"default" => 1,
			"type" => "toggle"
		),		
		array(
			"name" => __("Sub Footer",'striking_admin'),
			"desc" => __("If you don't want display sub footer, turn off the button.",'striking_admin'),
			"id" => "sub_footer",
			"default" => 1,
			"type" => "toggle"
		),	*/	
		array(
			"name" => __("Footer Column layout",'striking_admin'),
			"desc" => __("choose the layout of footer columns you'd like the footer widgets displayed in",'striking_admin'),
			"id" => "column",
			"function" => "theme_footer_column_option",
			"default" => "4",
			"type" => "custom"
		),
		array(
			"name" => __("Copyright Footer Text",'striking_admin'),
			"desc" => __("Enter the copyright text that you'd like to display in the footer",'striking_admin'),
			"id" => "copyright",
			"default" => "© 2010 Blog.com. ",
			"rows" => 3,
			"type" => "textarea"
		),
		array(
			"name" => __("Sub Footer Right Area Type",'striking_admin'),
			"desc" => "",
			"id" => "footer_right_area_type",
			"default" => 'menu',
			"options" => array(
				"menu" => __('Menu','striking_admin'),
				"html" => __('Html','striking_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Sub Footer Right Area Html code",'striking_admin'),
			"desc" => '',
			"id" => "footer_right_area_html",
			"default" => "",
			'rows' => '3',
			"type" => "textarea"
		),
		/* array(
			"name" => __("Footer Link Pause Time",'striking_admin'),
			"id" => "link_pauseTime",
			"min" => "1000",
			"max" => "200000",
			"step" => "500",
			"unit" => 'miliseconds',
			"default" => "5000",
			"type" => "range"
		),*/
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'footer',
	'options' => $options
);
