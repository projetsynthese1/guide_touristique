<?php
/*-----------------------------------------------------------------------------------*/
/* Manage gallery's columns */
/*-----------------------------------------------------------------------------------*/
function edit_gallery_columns($gallery_columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"id" => __('ID'),
		"title" => _x('Gallery Name', 'column name'),
		"count" => __('Image Quantity'),
		"description" => __('Description')
	);
	return $columns;
}
add_filter('manage_edit-gallery_columns', 'edit_gallery_columns');
function manage_gallery_columns($column) {
	global $post;
	if ($post->post_type == "gallery") {
		switch($column){
			case "id":
				echo $post->ID;
				break;
			case "description":
				the_excerpt();
				break;
			case "count":
				echo count(explode(',', str_replace('image-', '', get_post_meta($post->ID, 'image_ids', true))));
				break;
		}
	}
}
add_action('manage_posts_custom_column', 'manage_gallery_columns', 10, 2);
