<?php 
$layout=theme_get_option('blog','layout');
get_header(); ?>
<?php theme_generator('introduce',$post->ID);?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<div id="main">
			<?php theme_generator('breadcrumbs',$post->ID);?>
			<div class="content">
				<?php 
					$exclude_cats = theme_get_option('blog','exclude_categorys');
					foreach ($exclude_cats as $key => $value) {
						$exclude_cats[$key] = -$value;
					}
					$query_string = "cat=".implode(",",$exclude_cats)."&paged=$paged";
					query_posts($query_string);
					get_template_part( 'loop','blog');
				?>
				<div class="clearboth"></div>
			</div>
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
