<?php
function theme_more_link($more_link, $more_link_text) {
	$more_link = '[raw]' . $more_link . '[/raw]';
	return str_replace('more-link', 'read_more_link', $more_link);
}
add_filter('the_content_more_link', 'theme_more_link', 10, 2);
function theme_excerpt_more($excerpt) {
	return str_replace('[...]', '...', $excerpt);
}
add_filter('wp_trim_excerpt', 'theme_excerpt_more');
function theme_exclude_category_feed() {
	$exclude_cats = theme_get_option('blog','exclude_categorys');
	foreach ($exclude_cats as $key => $cat) {
		$exclude_cats[$key] = -$cat;
	}
	if ( is_feed() ) {
		set_query_var("cat", implode(",",$exclude_cats));
	}
}
add_filter('pre_get_posts', 'theme_exclude_category_feed');
/*
 * Remove Blog categories from category widget
 */
function theme_exclude_category_widget($cat_args)
{
	$exclude_cats = theme_get_option('blog','exclude_categorys');
	if(is_array($exclude_cats)){
		$cat_args['exclude'] = implode(",",$exclude_cats);
	}
 	return $cat_args;
}
add_filter('widget_categories_args', 'theme_exclude_category_widget');
function theme_exclude_the_categorys($thelist,$separator=' ') {
	if(!defined('WP_ADMIN') && !isset($_GET['geo_mashup_content'])) {
		//Category IDs to exclude
		$exclude = theme_get_option('blog','exclude_categorys');
		$exclude2 = array();
		foreach($exclude as $c) {
			$exclude2[] = get_cat_name($c);
		}
		$cats = explode($separator,$thelist);
		$newlist = array();
		foreach($cats as $cat) {
			$catname = trim(strip_tags($cat));
			if(!in_array($catname,$exclude2))
				$newlist[] = $cat;
		}
		return implode($separator,$newlist);
	} else {
		return $thelist;
	}
}
add_filter('the_category','theme_exclude_the_categorys',10,2);
/*
 * add a span element for style in the page
 */
function theme_comment_style($return) {
	return str_replace($return, "<span></span>$return", $return);
}
add_filter('get_comment_author_link', 'theme_comment_style');
function theme_widget_title_remove_space($return){
	$return = trim($return);
	if('&nbsp;' == $return){
		return '';	
	}else{
		return $return;
	}
}
add_filter('widget_title', 'theme_widget_title_remove_space');
function theme_widget_text_shortcode($content) {
	$content = do_shortcode($content);
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= do_shortcode($piece);
		}
	}
	return $new_content;
}
// Allow Shortcodes in Sidebar Widgets
add_filter('widget_text', 'theme_widget_text_shortcode');
/*
 * Thank to Bob Sherron.
 * http://stackoverflow.com/questions/1155565/query-multiple-custom-taxonomy-terms-in-wordpress-2-8/2060777#2060777
 */
function multi_tax_terms($where) {
    global $wp_query;
    global $wpdb;
    if (isset($wp_query->query_vars['term']) && (strpos($wp_query->query_vars['term'], ',') !== false && strpos($where, "AND 0") !== false) ) {
        // it's failing because taxonomies can't handle multiple terms
        //first, get the terms
        $term_arr = explode(",", $wp_query->query_vars['term']);
        foreach($term_arr as $term_item) {
            $terms[] = get_terms($wp_query->query_vars['taxonomy'], array('slug' => $term_item));
        }
        //next, get the id of posts with that term in that tax
        foreach ( $terms as $term ) {
            $term_ids[] = $term[0]->term_id;
        }
        $post_ids = get_objects_in_term($term_ids, $wp_query->query_vars['taxonomy']);
        if ( !is_wp_error($post_ids) && count($post_ids) ) {
            // build the new query
            $new_where = " AND $wpdb->posts.ID IN (" . implode(', ', $post_ids) . ") ";
            // re-add any other query vars via concatenation on the $new_where string below here
            // now, sub out the bad where with the good
            $where = str_replace("AND 0", $new_where, $where);
        } else {
            // give up
        }
    }
    return $where;
}
add_filter("posts_where", "multi_tax_terms");
if(theme_get_option('portfolio','single_doc_navigation')){
function get_adjacent_portfolio_join($join){
	global $post, $wpdb;
	if($post->post_type == 'portfolio'){
		$join .= " JOIN $wpdb->postmeta ON (p.ID = $wpdb->postmeta.post_id) ";
	}
	return $join;	
}
add_filter("get_previous_post_join", "get_adjacent_portfolio_join");
add_filter("get_next_post_join", "get_adjacent_portfolio_join");
function get_adjacent_portfolio_where($where){
	global $post, $wpdb;
	if($post->post_type == 'portfolio'){
		$where .= $wpdb->prepare(" AND $wpdb->postmeta.meta_key = %s ", '_type');
		$where .= $wpdb->prepare("AND $wpdb->postmeta.meta_value = %s ", 'doc');
	}
	return $where;
}
add_filter("get_previous_post_where", "get_adjacent_portfolio_where");
add_filter("get_next_post_where", "get_adjacent_portfolio_where");
}
// custom post type for google sitemap plugin
if(class_exists('GoogleSitemapGeneratorLoader')){
	require_once (THEME_PLUGINS . '/guar_sitemap/guar_sitemap.php');
	function theme_sitemap_filter($posttypes)
	{
		foreach($posttypes as $key => $val)
		{
			if($val=='slideshow')
			{
				unset($posttypes[$key]);
			}
		}
		$posttypes[] = 'portfolio';
		return $posttypes;
	}
	add_filter('guar_sitemap_posttype_filter','theme_sitemap_filter',10,1);
}
