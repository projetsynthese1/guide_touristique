<?php
/*
Template Name: Sitemap
*/
?>
<?php get_header(); ?>
<?php theme_generator('introduce',$post->ID);?>
<div id="page">
	<div class="inner right_sidebar">
		<div id="main">
			<?php theme_generator('breadcrumbs',$post->ID);?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<?php the_content(); ?>
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
				<h2><?php _e( 'Blog Posts','striking_front'); ?></h2>
				<ul>
<?php while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
					<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'striking_front'), get_the_title() ); ?>"><?php the_title(); ?></a> (<?php comments_number('0', '1', '%'); ?>)</li>
<?php endwhile; ?>
				</ul>
				<div class="divider top"><a href="#"><?php _e('Top','striking_front');?></a></div> 
<?php 
	$portfolio_query = new WP_Query( array('post_type' => 'portfolio','showposts' => 1000 ));
	if($portfolio_query->have_posts()):
?>
				<h2><?php _e( 'Portfolios','striking_front'); ?></h2>
				<ul>
<?php while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
					<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'striking_front'), get_the_title() ); ?>"><?php the_title(); ?></a><?php if(theme_get_option('portfolio','enable_comment')):?> (<?php comments_number('0', '1', '%'); ?>)<?php endif;?></li>
<?php endwhile; ?>
				</ul>
				<div class="divider top"><a href="#"><?php _e('Top','striking_front');?></a></div> 
<?php endif;?>
				<?php edit_post_link(__('Edit', 'striking_front'),'<footer><p class="entry_edit">','</p></footer>'); ?>
				<div class="clearboth"></div>
			</div>
<?php endwhile; ?>
			<div class="clearboth"></div>
		</div>
		<?php get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
