<?php
/**
 * This is the default template for full post display of a clicked marker
 * in a Geo Mashup map. 
 *
 * Don't modify this file! It will be overwritten by upgrades.
 *
 * Instead, copy this file to "full-post.php" in this directory,
 * or "geo-mashup-full-post.php" in your theme directory. Those files will
 * take precedence over this one.
 *
 * @package GeoMashup
 */
?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<h4><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
			<?php if(has_post_thumbnail()):
				the_post_thumbnail(array(65,65),array(
					'class'	=> "alignleft"
				));
 			endif;?>
			<div class="storycontent">
				<?php the_content(); ?>
			</div>
	<?php endwhile; ?>
<?php else : ?>
	<h4 class="center">Not Found</h4>
	<p class="center">Sorry, but you are looking for something that isn't here.</p>
<?php endif; ?>
