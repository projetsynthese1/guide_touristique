<?php
/*-----------------------------------------------------------------------------------*/
/* Register Custom Post Types - Gallerys */
/*-----------------------------------------------------------------------------------*/
function register_gallery_post_type(){
	register_post_type('gallery', array(
		'labels' => array(
			'name' => _x('Gallerys', 'post type general name', 'striking_admin' ),
			'singular_name' => _x('Gallery', 'post type singular name', 'striking_admin' ),
			'add_new' => _x('Add New', 'gallery', 'striking_admin' ),
			'add_new_item' => __('Add New Gallery', 'striking_admin' ),
			'edit_item' => __('Edit Gallery', 'striking_admin' ),
			'new_item' => __('New Gallery', 'striking_admin' ),
			'view_item' => __('View Gallery', 'striking_admin' ),
			'search_items' => __('Search Gallerys', 'striking_admin' ),
			'not_found' =>  __('No gallerys found', 'striking_admin' ),
			'not_found_in_trash' => __('No gallerys found in Trash', 'striking_admin' ),
			'parent_item_colon' => ''
		),
		'singular_label' => __('gallery', 'striking_admin' ),
		'public' => false,
		'exclude_from_search' => false,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'query_var' => false,
		'supports' => array('title', 'excerpt')
	));
}
add_action('init','register_gallery_post_type');
