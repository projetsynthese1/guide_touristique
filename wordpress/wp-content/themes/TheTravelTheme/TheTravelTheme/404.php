<?php  get_header(); ?>
<?php theme_generator('introduce');?>
<div id="page">
	<div class="inner right_sidebar">
		<div id="main">
			<div class="content">
				<h2><?php _e('Pages','striking_front');?></h2>
				<ul>
					<?php wp_list_pages('depth=0&sort_column=menu_order&title_li=' ); ?>		
				</ul>
				<div class="divider top"><a href="#"><?php _e('Top','striking_front');?></a></div>
<?php 
	$exclude_cats = theme_get_option('blog','exclude_categorys');
?>
				<h2><?php _e( 'Category Archives','striking_front'); ?></h2>
				<ul>
					<?php wp_list_categories( array( 'exclude'=> implode(",",$exclude_cats), 'feed' => __( 'RSS', 'striking_front' ), 'show_count' => true, 'use_desc_for_title' => false, 'title_li' => false ) ); ?>
				</ul>
				<div class="divider top"><a href="#"><?php _e('Top','striking_front');?></a></div> 
<?php 
	$archive_query = new WP_Query( array('showposts' => 1000,'category__not_in' => $exclude_cats ));
?>
				<h2><?php _e( 'Blog Posts','striking_front' ); ?></h2>
				<ul>
<?php while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
					<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'striking_front'), get_the_title() ); ?>"><?php the_title(); ?></a> (<?php comments_number('0', '1', '%'); ?>)</li>
<?php endwhile; ?>
				</ul>
				<div class="divider top"><a href="#"><?php _e('Top','striking_front');?></a></div>
			</div>
		</div>
		<aside id="sidebar">
			<div id="sidebar_content"><?php get_search_form(); ?></div>
			<div id="sidebar_bottom"></div>
		</aside>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
