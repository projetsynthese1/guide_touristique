<?php
/*-----------------------------------------------------------------------------------*/
/* Manage slideshow's columns */
/*-----------------------------------------------------------------------------------*/
function edit_slideshow_columns($slideshow_columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => _x('Slider Item Title', 'column name', 'striking_admin' ),
		"author" => __('Author', 'striking_admin' ),
		"date" => __('Date', 'striking_admin' ),
		"thumbnail" => __('Thumbnail', 'striking_admin' )
	);
	return $columns;
}
add_filter('manage_edit-slideshow_columns', 'edit_slideshow_columns');
function manage_slideshow_columns($column) {
	global $post;
	if ($post->post_type == "slideshow") {
		switch($column){
			case 'thumbnail':
				echo the_post_thumbnail('thumbnail');
				break;
		}
	}
}
add_action('manage_posts_custom_column', 'manage_slideshow_columns', 10, 2);
/*-----------------------------------------------------------------------------------*/
/* Add image size for slideshow */
/*-----------------------------------------------------------------------------------*/
if ((isset($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) == 'slideshow') || 
	(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')) {
	add_image_size('slideshow', 960, 440, true);
}
/*-----------------------------------------------------------------------------------*/
/* Add scripts and styles for slideshow */
/*-----------------------------------------------------------------------------------*/
if(theme_is_post_type_edit('slideshow') || theme_is_post_type_new('slideshow')){
	wp_deregister_script('autosave');
}
