<?php
require_once (THEME_HELPERS . '/shortcodesGenerator.php');
function theme_get_image_size(){
	$customs =  theme_get_option('image','customs');
	$sizes = array(
		"small" => __("Small",'striking_admin'),
		"medium" => __("Medium",'striking_admin'),
		"large" => __("Large",'striking_admin'),
	);
	if(!empty($customs)){
		$customs = explode(',',$customs);
		foreach($customs as $custom){
			$sizes[$custom] = ucfirst(strtolower($custom));
		}
	}
	return $sizes;
}
$config = array(
	'title' => __('Shortcode Generator','striking_admin'),
	'id' => 'shortcode',
	'pages' => array('page','post','portfolio','slideshow'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$shortcodes = include(THEME_ADMIN_METABOXES . '/shortcode_options.php');
new shortcodesGenerator($config,$shortcodes);
