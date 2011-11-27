<?php 
get_header();
$page= theme_get_option('homepage','home_page');
if($page){
	$page_date = get_page(wpml_get_object_id($page,'page'));
	$content = $page_date->post_content;
}else{
	$content = theme_get_option('homepage','content');
}
$layout=theme_get_option('homepage','layout');
?>
<?php theme_generator('SlideShow');?>
<div id="page" class="home">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<div id="main">
			<div class="content">
				<?php echo apply_filters('the_content', stripslashes( $content ));?>
				<div class="clearboth"></div>
			</div>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<img src="<?php echo THEME_URI;?>/images/spacer.png" alt="spacer3ADE3E"/>
<?php get_footer(); ?>