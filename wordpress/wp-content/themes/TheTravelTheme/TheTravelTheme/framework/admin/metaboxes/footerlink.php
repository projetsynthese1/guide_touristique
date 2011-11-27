<?php
$config = array(
	'title' => sprintf(__('%s Footer Dynamic Link','striking_admin'),THEME_NAME),
	'id' => 'footer_link',
	'pages' => array('post','page','portfolio'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
function get_footer_link_options(){
	$links = get_bookmarks(array('category_name'=>'Dynamic'));
	if(!empty($links)){
		$options = array();
		foreach ($links as $link){
			$options[$link->link_id] = $link->link_name;
		}
		return $options;
	}else{
		return array();
	}
}
$options = array(
	array(
		"name" => __("Footer Link",'striking_admin'),
		"id" => "_footer_link",
		"prompt" => __("Dynamic",'striking_admin'),
		"default" => '',
		"options" => get_footer_link_options(),
		"type" => "select",
	),
);
new metaboxesGenerator($config,$options);
