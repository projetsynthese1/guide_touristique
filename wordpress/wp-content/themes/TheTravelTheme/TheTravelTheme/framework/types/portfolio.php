<?php
/*-----------------------------------------------------------------------------------*/
/* Register Custom Post Types - Portfolios */
/*-----------------------------------------------------------------------------------*/
function register_portfolio_post_type(){
	register_post_type('portfolio', array(
		'labels' => array(
			'name' => _x('Portfolios', 'post type general name', 'striking_admin' ),
			'singular_name' => _x('Portfolio', 'post type singular name', 'striking_admin' ),
			'add_new' => _x('Add New', 'portfolio', 'striking_admin' ),
			'add_new_item' => __('Add New Portfolio', 'striking_admin' ),
			'edit_item' => __('Edit Portfolio', 'striking_admin' ),
			'new_item' => __('New Portfolio', 'striking_admin' ),
			'view_item' => __('View Portfolio', 'striking_admin' ),
			'search_items' => __('Search Portfolios', 'striking_admin' ),
			'not_found' =>  __('No portfolios found', 'striking_admin' ),
			'not_found_in_trash' => __('No portfolios found in Trash', 'striking_admin' ), 
			'parent_item_colon' => '',
		),
		'singular_label' => __('portfolio', 'striking_admin' ),
		'public' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array( 'with_front' => false ),
		'query_var' => false,
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments', 'page-attributes')
	));
	//register taxonomy for portfolio
	register_taxonomy('portfolio_category','portfolio',array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Portfolio Categories', 'taxonomy general name', 'striking_admin' ),
			'singular_name' => _x( 'Portfolio Category', 'taxonomy singular name', 'striking_admin' ),
			'search_items' =>  __( 'Search Categories', 'striking_admin' ),
			'popular_items' => __( 'Popular Categories', 'striking_admin' ),
			'all_items' => __( 'All Categories', 'striking_admin' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Portfolio Category', 'striking_admin' ), 
			'update_item' => __( 'Update Portfolio Category', 'striking_admin' ),
			'add_new_item' => __( 'Add New Portfolio Category', 'striking_admin' ),
			'new_item_name' => __( 'New Portfolio Category Name', 'striking_admin' ),
			'separate_items_with_commas' => __( 'Separate Portfolio category with commas', 'striking_admin' ),
			'add_or_remove_items' => __( 'Add or remove portfolio category', 'striking_admin' ),
			'choose_from_most_used' => __( 'Choose from the most used portfolio category', 'striking_admin' )
		),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => false,
	));
}
add_action('init','register_portfolio_post_type');
function portfolio_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'portfolio' ) {
		global $wp_query;
		$wp_query->is_home = false;
	}
	if ( get_query_var( 'taxonomy' ) == 'portfolio_category' ) {
		global $wp_query;
		$wp_query->is_404 = true;
		$wp_query->is_tax = false;
		$wp_query->is_archive = false;
	}
}
add_action( 'template_redirect', 'portfolio_context_fixer' );
