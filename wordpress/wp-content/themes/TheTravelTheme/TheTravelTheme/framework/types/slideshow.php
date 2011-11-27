<?php
/*-----------------------------------------------------------------------------------*/
/* Register Custom Post Types - Gallerys */
/*-----------------------------------------------------------------------------------*/
function register_slideshow_post_type(){
	register_post_type('slideshow', array(
		'labels' => array(
			'name' => _x('Slider Items', 'post type general name', 'striking_admin'),
			'singular_name' => _x('Slider Item', 'post type singular name', 'striking_admin'),
			'add_new' => _x('Add New', 'slideshow', 'striking_admin'),
			'add_new_item' => __('Add New Slider Item', 'striking_admin'),
			'edit_item' => __('Edit Slider Item', 'striking_admin'),
			'new_item' => __('New Slider Item', 'striking_admin'),
			'view_item' => __('View Slider Item', 'striking_admin'),
			'search_items' => __('Search Slider Items', 'striking_admin'),
			'not_found' =>  __('No slider item found', 'striking_admin'),
			'not_found_in_trash' => __('No slider items found in Trash', 'striking_admin'), 
			'parent_item_colon' => ''
		),
		'singular_label' => __('slideshow', 'striking_admin'),
		'public' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => false,
		'query_var' => false,
		'supports' => array('title','editor', 'thumbnail' , 'page-attributes')
	));
}
add_action('init','register_slideshow_post_type');
function slideshow_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'slideshow' ) {
		global $wp_query;
		$wp_query->is_home = false;
		$wp_query->is_404 = true;
		$wp_query->is_single = false;
		$wp_query->is_singular = false;
	}
}
add_action( 'template_redirect', 'slideshow_context_fixer' );
