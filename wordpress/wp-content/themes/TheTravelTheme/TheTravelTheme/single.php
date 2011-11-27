<?php
$blog_page = theme_get_option('blog','blog_page');
if($blog_page == $post->ID){
	return require(THEME_DIR . "/template_blog.php");
}
$layout = get_post_meta($post->ID, '_layout', true);
if(empty($layout) || $layout == 'default'){
	$layout=theme_get_option('blog','single_layout');
}
?>
<?php  get_header(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 
	$featured_box = get_post_meta($post->ID, '_featured_box', true);
	if($featured_box == '') $featured_box = 'default';
	if($featured_box != "false"):
		if(($featured_box == 'default' && theme_get_option('blog','featured_box')) || $featured_box == 'true'):
			$featured_video = get_post_meta($post->ID, '_featured_video', true);
			$height = theme_get_option('blog','feature_box_height');
			if(!empty($featured_video)){
				preg_match('@(?:\?v=|/v/)([-\w]+)|([-\w]+)$@', $featured_video, $matches);
				$youtubeId = $matches[ count($matches) - 1 ];
				$featured_content = <<<HTML
<div class="feature_box_video">
	<object width="960" height="$height">
		<param name="movie" value="http://www.youtube.com/v/$youtubeId?fs=1&amp;rel=0&amp;showinfo=0&amp;showsearch=0&amp;iv_load_policy=3"></param>
		<param name="allowFullScreen" value="true"></param>
		<param name="allowscriptaccess" value="always"></param>
		<param value="opaque" name="wmode">
		<embed src="http://www.youtube.com/v/$youtubeId?fs=1&amp;rel=0&amp;showinfo=0&amp;showsearch=0&amp;iv_load_policy=3" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="960" height="$height"></embed>
	</object>
</div>
HTML;
			}elseif (has_post_thumbnail()){
				$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
				$image_src = THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image_src_array[0]).'&amp;h='.$height.'&amp;w=960&amp;zc=1';
				$featured_content = '<div class="feature_box_image"><img src="'.$image_src.'" alt="'.get_the_title().'" /></div>';
			}
			if(isset($featured_content)):
?>
<div id="feature_box">
	<?php echo $featured_content;?>
	<div class="feature_box_overlap">
		<div class="inner">
			<div class="feature_box_title">
				<?php the_title(); ?>
			</div>
			<div class="feature_box_category">
				<?php _e('CATEGORY: ', 'striking_front');  the_category(', '); ?>
			</div>
		</div>
	</div>
</div>
<?php		endif;
		endif;
	endif; ?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<div id="main">
			<article id="post-<?php the_ID(); ?>" class="entry content">
				<header>
					<div class="entry_title"><h1><?php the_title(); ?></h1></div>
					<div class="entry_meta">
<?php theme_generator('blog_meta'); ?>
					</div>
				</header>
				<?php the_content(); ?>
				<footer>
					<?php edit_post_link(__('Edit', 'striking_front'),'<p class="entry_edit">','</p>'); ?>
					<?php if(theme_get_option('blog','author')):theme_generator('blog_author_info');endif;?>
					<?php if(theme_get_option('blog','related_popular')):?>
					<div class="related_popular_wrap">
						<div class="one_half">
							<?php theme_generator('blog_related_posts');?>
						</div>
						<div class="one_half last">
							<?php theme_generator('blog_popular_posts');?>
						</div>
						<div class="clearboth"></div>
					</div>
					<?php endif;?>
					<?php if(theme_get_option('blog','related_posts')):?>
<?php
$backup = $post;  
$related_posts_type = theme_get_option('blog','related_posts_type');
$tagIDs = array();
$catIDs = array();
if($related_posts_type == 'tags'){
	$tags = wp_get_post_tags($post->ID);
	if ($tags) {
		$tagcount = count($tags);
		for ($i = 0; $i < $tagcount; $i++) {
			$tagIDs[$i] = $tags[$i]->term_id;
		}
	}
}else{
	$catIDs = wp_get_post_categories($post->ID);
}
$related_post_found = false;
$output = '';
if (!empty($tagIDs)||!empty($catIDs)) {
	$r = new WP_Query(array(
		'tag__in' => $tagIDs,
		'category__in' => $catIDs,
		'post__not_in' => array($post->ID),
		'showposts'=>3,
		'caller_get_posts'=>1
	));
	if ($r->have_posts()){
		$related_post_found = true;
		$output .= '<div class="related_posts_wrap">';
		$output .= '<h3>'.__('Related Posts','striking_front').'</h3>';
		$output .= '<ul class="related_posts_list">';
		while ($r->have_posts()){
			$r->the_post();
			$output .= '<li>';
			$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
			if (has_post_thumbnail() ){
				$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
				$image_src = THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image_src_array[0]).'&amp;h=80&amp;w=192&amp;zc=1';				
				$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" />';
			}else{
				$output .= '<img src="'.THEME_IMAGES.'/related_posts_thumbnail.png" width="192" height="80" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
			}
			$output .= '</a>';
			$output .= '<div class="post_extra_info">';
			$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
			$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
			$output .= '</div>';
			$output .= '<div class="clearboth"></div>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '<div class="clearboth"></div>';
		$output .= '</div>';
	}
	$post = $backup;
	echo $output;
}
?>
					<?php endif;?>
					<?php if(theme_get_option('blog','entry_navigation')):?>
					<nav class="entry_navigation">
						<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'striking_front' ) . '</span> %title' ); ?></div>
						<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'striking_front' ) . '</span>' ); ?></div>
					</nav>
					<?php endif;?>
				</footer>
				<div class="clearboth"></div>
			</article>
<?php comments_template( '', true ); ?>
<?php endwhile; // end of the loop.?>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
