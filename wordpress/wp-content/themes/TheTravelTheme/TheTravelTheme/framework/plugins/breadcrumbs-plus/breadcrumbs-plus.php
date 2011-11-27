<?php

/*!
 * Plugin Name: Breadcrumbs Plus
 * Plugin URI: http://snippets-tricks.org/proyectos/breadcrumbs-plus-plugin/
 * Description: Breadcrumbs Plus provide links back to each previous page the user navigated through to get to the current page or—in hierarchical site structures—the parent pages of the current one.
 * Version: 0.2
 * Author: Luis Alberto Ochoa
 * Author URI: http://luisalberto.org
 * License: GPL2
 */

/*!
 * Poedit is a good tool to for translating.
 * @link http://poedit.net
 *
 * @since 0.1
 */
load_plugin_textdomain( 'breadcrumbs-plus', false, 'breadcrumbs-plus/languages' );

/*!
 * Shows a breadcrumb for all types of pages.
 *
 * @since 0.1
 * @param array $args
 * @return string
 */
function breadcrumbs_plus( $args = '' ) {

	/* Get the textdomain. */
	$domain = 'breadcrumbs-plus';

	/* Set up the default arguments for the breadcrumb. */
	$defaults = array(
		'prefix' => '<p id="breadcrumbs">',
		'suffix' => '</p>',
		'title' => __( 'You are here: ', 'striking_front' ),
		'home' => __( 'Home', 'striking_front' ),
		'sep' => '&raquo;',
		'front_page' => false,
		'bold' => false,
		'blog' => __( 'Blog', 'striking_front' ),
		'echo' => true
	);

	$r = wp_parse_args( apply_filters( 'breadcrumbs_args', $args ), $defaults );

	if ( is_front_page() && !$r['front_page'] )
		return apply_filters( 'breadcrumbs', false );

	global $wp_query;

	$separator = '<span class="breadcrumbs-sep">' . $r['sep'] . '</span>';
	$on_front = get_option( 'show_on_front' );

	if ( $on_front == "page" ) {
		$homelink = '<a href="' . get_permalink( get_option( 'page_on_front' ) ) . '" rel="home" class="breadcrumbs-begin">' . $r['home'] . '</a>';
		$bloglink = $homelink . ' ' . $separator . ' <a href="' . get_permalink( get_option( 'page_for_posts' ) ).'">' . $r['blog'] . '</a>';

	} else {
		$homelink = '<a href="' . home_url( '/' ) . '" rel="home" class="breadcrumbs-begin">' . $r['home'] . '</a>';
		$bloglink = $homelink;

	}

	if ( ( $on_front == "page" && is_front_page() ) || ( $on_front == "posts" && is_home() ) )
		$output = bold( $r['home'], $r['bold'] );

	elseif ( $on_front == "page" && is_home() )
		$output = $homelink . ' ' . $separator . ' ' . bold( $r['blog'], $r['bold'] );

	elseif ( is_singular() ) {
		
		$output = $bloglink . ' ' . $separator . ' ';
		
		/* If viewing post. */
		if ( is_single() ) {
			if(function_exists('theme_get_option')){
				$blog_page = theme_get_option('blog','blog_page');
				if($blog_page){
					$output .= '<a href="' . get_permalink($blog_page) . '">' . $r['blog'] . '</a> ' . $separator . ' ';
				}
			}			
			
			$cats = get_the_category();
			$cat = $cats[0];
			
			if ( is_object( $cat ) ) {
				if ( $cat->parent != 0 )
					$output .= get_category_parents( $cat->term_id, true, " " . $separator . " " );

				else
					$output .= '<a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '</a> ' . $separator . ' ';

			}

			$output .= bold( get_the_title(), $r['bold'] );
		}
		
		/* If view page. */
		elseif ( is_page() ) {
			$page = $wp_query->get_queried_object();

			if ( 0 == $page->post_parent ) {
				$output .= bold( get_the_title(), $r['bold'] );

			} else {
				if ( isset( $page->ancestors ) ) {
					if ( is_array( $page->ancestors ) )
						$ancestors = array_values( $page->ancestors );

					else
						$ancestors = array( $page->ancestors );

				} else
					$ancestors = array( $page->post_parent );

				$ancestors = array_reverse( $ancestors );
				$ancestors[] = $page->ID;
				
				$links = array();
				
				foreach( (array) $ancestors as $ancestor ) {
					$tmp = array();
					$tmp['title'] = strip_tags( get_the_title( $ancestor ) );
					$tmp['url'] = get_permalink( $ancestor );
					$tmp['cur'] = false;
					
					if ( $ancestor == $page->ID )
						$tmp['cur'] =  true;
					
					$links[] = $tmp;
				}
				
				$output = $homelink;
				
				foreach ( (array) $links as $link ) {
					$output .= ' ' . $separator . ' ';

					if ( !$link['cur'] )
						$output .= '<a href="' . $link['url'] . '">' . $link['title'] . '</a>';
					else
						$output .= bold( $link['title'], $r['bold'] );

				}
			}
		}
	}

	/* If we're viewing any type of archive. */
	elseif ( is_archive() ) {
		
		$output = $bloglink . ' ' . $separator . ' ';
		
		if ( is_category() ) {
			$category = intval( get_query_var( 'cat' ) );
			$output .= breadcrumbs_get_category_parents( $category, false, " " . $separator . " ", false, $r['bold'] );

		} elseif ( is_tag() ){
			$output .= bold( sprintf(__('Tag Archives for: ‘%s’','striking_front'),single_tag_title('',false)), $r['bold'] );

		} elseif ( is_tax() ) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$output .= bold( sprintf(__('Archives for: ‘%s’','striking_front'),$term->name), $r['bold'] );

		} elseif ( is_date() ) {
			if ( is_day() )
				$output .= bold( sprintf(__('Daily Archive for: ‘%s’','striking_front'),get_the_time('F jS, Y')), $r['bold'] );
			
			elseif ( is_month() )
				$output .= bold( sprintf(__('Monthly Archive for: ‘%s’','striking_front'),get_the_time('F, Y')), $r['bold'] );
					
			elseif ( is_year() )
				$output .= bold( sprintf(__('Yearly Archive for: ‘%s’','striking_front'),get_the_time('Y')), $r['bold'] );

		} elseif ( is_author() ){
				if(get_query_var('author_name')){
					$curauth = get_user_by('slug', get_query_var('author_name'));
				} else {
					$curauth = get_userdata(get_query_var('author'));
				}
				$output .= bold( sprintf(__('Author Archive for: ‘%s’','striking_front'),$curauth->nickname), $r['bold'] );
		}

	}

	/* If viewing search results. */
	elseif ( is_search() ) {
		$output = $homelink . ' ' . $separator . ' ';
		$output .= bold( sprintf(__( 'Search Results for: ‘%s’', 'striking_front' ),stripslashes( strip_tags( get_search_query() ) ) ), $r['bold'] );

	}
	/* If viewing a 404 error page. */
	elseif ( is_404() ) {
		$output = $homelink . ' ' . $separator . ' ';
		$output .= bold( __( 'Page Not Found', 'striking_front' ), $r['bold'] );

	}
	if(!empty($r['title'])){
	$breadcrumb = $r['prefix'] . '<span class="breadcrumbs-title">' . $r['title'] . '</span>' . $output . $r['suffix'];
	}else{
		$breadcrumb = $r['prefix'] . $output . $r['suffix'];
	}
	$breadcrumb = apply_filters( 'breadcrumbs', $breadcrumb );

	if ( !$r['echo'] )
		return $breadcrumb;

	echo $breadcrumb;
}

/*!
 * Searches for term parents of hierarchical categories.
 * Copied and adapted from Yoast Breadcrumbs
 *
 * @since 0.1
 * @return string
 */
function breadcrumbs_get_category_parents( $id, $link = false, $separator = '/', $nicename = false, $bold ){
	$chain = '';
	$parent = &get_category( $id );

	if ( is_wp_error( $parent ) )
		return $parent;

	if ( $nicename )
		$name = $parent->slug;
	else
		$name = $parent->cat_name;

	if ( $parent->parent && ( $parent->parent != $parent->term_id ) )
		$chain .= get_category_parents( $parent->parent, true, $separator, $nicename );

	$chain .= bold( $name, $bold );

	return $chain;
}

/*!
 * Return a Input with <strong> tag
 *
 * @since 0.1
 * @return string
 */
function bold( $input, $lastbold ) {
	if ( $lastbold )
		return '<strong>'. $input . '</strong>';

	return $input;
}

?>
