<?php
$blog_page = theme_get_option('blog','blog_page');
if($blog_page == $post->ID){
	return require(THEME_DIR . "/template_blog.php");
}
$layout = get_post_meta($post->ID, '_layout', true);
if(empty($layout) || $layout == 'default'){
	$layout=theme_get_option('portfolio','layout');
}
?>
<?php  get_header(); ?>
<?php theme_generator('introduce',$post->ID);?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<div id="main">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="entry content">
<?php if(theme_get_option('portfolio','featured_image')):?>
				<header>
					<?php theme_generator('portfolio_featured_image',$layout); ?>
				</header>
<?php endif; ?>
				<?php the_content(); ?>
				<footer>
				<?php edit_post_link(__('Edit', 'striking_front'),'<p class="entry_edit">','</p>'); ?>
<?php if(theme_get_option('portfolio','single_navigation')):?>
					<nav class="entry_navigation">
						<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous portfolio link', 'striking_front' ) . '</span> %title' ); ?></div>
						<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next portfolio link', 'striking_front' ) . '</span>' ); ?></div>
					</nav>
<?php endif;?>
				</footer>
				<div class="clearboth"></div>
			</article>
<?php if(theme_get_option('portfolio','enable_comment')) comments_template( '', true ); ?>
<?php endwhile; // end of the loop.?>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
