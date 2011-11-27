<?php
class themeGenerator {
	function title(){
		global $page, $paged;
		wp_title( '|', true, 'right' );
		// Add the blog name.
		bloginfo( 'name' );
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && is_front_page() )
			echo " | $site_description";
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'striking_front' ), max( $paged, $page ) );
	}
	function wpml_flags(){
		if(function_exists('icl_get_languages')){
			$languages = icl_get_languages('skip_missing=0');
			if(!empty($languages)){
				echo '<div id="language_flags"><ul>';
				foreach($languages as $l){
					echo '<li>';
					if(!$l['active']) echo '<a href="'.$l['url'].'" title="'.$l['native_name'].'">';
					echo '<img src="'.$l['country_flag_url'].'" alt="'.$l['language_code'].'" />';
					if(!$l['active']) echo '</a>';
					echo '</li>';
				}
				echo '</ul></div>';
			}
		}
	}
	function menu(){
		if (theme_get_option('general','enable_nav_menu') && has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 
				'theme_location' => 'primary-menu',
				'container' => 'nav',
				'container_id' => 'navigation',
				'container_class' => 'jqueryslidemenu',
				'fallback_cb' => ''
			));
		}else{
			$excluded_pages_with_childs = theme_get_excluded_pages();
			$active_class = (is_front_page()) ? 'class="current_page_item"' : '';
			$output = '<nav id="navigation" class="jqueryslidemenu">';
			$output .= '<ul id="menu-navigation" class="menu">';
			$output .= '<li><a ' .$active_class. ' href="' .get_option('home'). '">'.__('Home','striking_front').'</a></li>';
			$output .= wp_list_pages("sort_column=menu_order&exclude=$excluded_pages_with_childs&title_li=&echo=0&depth=4");
			$output .= '</ul>';
			$output .= '</nav>';
			echo $output;
		}
	}
	function sidebar($post_id = NULL){
		sidebar_generator('get_sidebar',$post_id);
	}
	function footer_sidebar(){
		sidebar_generator('get_footer_sidebar');
	}
	function introduce($post_id = NULL) {
		if(!theme_get_option('general','introduce')){
			return;
		}
		if (is_single() || is_page()){
			$type = get_post_meta($post_id, '_introduce_text_type', true);
			if (empty($type))
				$type = 'default';
			if ($type == 'disable') {
				return;
			}
			if (in_array($type, array('default', 'title', 'title_custom'))) {
				$title = get_the_title($post_id);
			}
			$blog_page_id = theme_get_option('blog','blog_page');
			if ($type == 'default' && is_singular('post') && $post_id!=$blog_page_id) {
				$show_in_header = theme_get_option('blog','show_in_header');
				if($show_in_header){
					$title = get_the_title($post_id);
					$text = '<div class="entry_meta">';
					$text .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
					$text .= '<span class="separater">|</span>';
					$text .= '<span class="categories">'.get_the_category_list(',').'</span>'; 
					ob_start();
						edit_post_link( __( 'Edit', 'striking_front' ), '<span class="separater">|</span> <span class="edit-link">', '</span>' );
						global $post;
						if($post->comment_count > 0 || comments_open()):
							echo '<span class="comments">';
							comments_popup_link(__('No Comments','striking_front'), __('1 Comment','striking_front'), __('% Comments','striking_front'));
							echo '</span>';
						endif;
					$text .= ob_get_clean();
					$text .= '</div>';
				}else{
					return $this->introduce($blog_page_id);
				}
			}
			if (in_array($type, array('custom', 'title_custom'))) {
				$text = get_post_meta($post_id, '_custom_introduce_text', true);
			}
		}
		if (is_archive()){
			$title = __('Archives','striking_front');
			if(is_category()){
				$text = sprintf(__('Category Archive for: ‘%s’','striking_front'),single_cat_title('',false));
			}elseif(is_tag()){
				$text = sprintf(__('Tag Archives for: ‘%s’','striking_front'),single_tag_title('',false));
			}elseif(is_day()){
				$text = sprintf(__('Daily Archive for: ‘%s’','striking_front'),get_the_time('F jS, Y'));
			}elseif(is_month()){
				$text = sprintf(__('Monthly Archive for: ‘%s’','striking_front'),get_the_time('F, Y'));
			}elseif(is_year()){
				$text = sprintf(__('Yearly Archive for: ‘%s’','striking_front'),get_the_time('Y'));
			}elseif(is_author()){
				if(get_query_var('author_name')){
					$curauth = get_user_by('slug', get_query_var('author_name'));
				} else {
					$curauth = get_userdata(get_query_var('author'));
				}
				$text = sprintf(__('Author Archive for: ‘%s’','striking_front'),$curauth->nickname);
			}elseif(isset($_GET['paged']) && !empty($_GET['paged'])) {
				$text = __('Blog Archives','striking_front');
			}elseif(is_tax()){
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$text = sprintf(__('Archives for: ‘%s’','striking_front'),$term->name);
			}
		}
		if (is_404()) {
			$title = __('404 - Not Found','striking_front');
			$text = __("Looks like the page you're looking for isn't here anymore. Try using the search box or sitemap below.",'striking_front');
		}
		if (is_search()) {
			$title = __('Search','striking_front');
			$text = sprintf(__('Search Results for: ‘%s’','striking_front'),stripslashes( strip_tags( get_search_query() ) ));
		}
		$color = get_post_meta($post_id, '_introduce_background_color', true);
		if(!empty($color) && $color != "transparent"){
			$color = ' style="background-color:'.$color.'"';
		}else{
			$color = '';
		}
		echo '<div id="feature"'.$color.'>';
		echo '<div class="top_shadow"></div>';
		echo '<div class="inner">';
		if (isset($title)) {
			echo '<div class="feature_title">' . $title . '</div>';
		}
		if (isset($text)) {
			echo '<div id="introduce">';
			echo $text;
			echo '</div>';
		}
		echo '</div>';
		echo '<div class="bottom_shadow"></div>';
		echo '</div>';
	}
	function breadcrumbs($post_id = NULL) {
		if(theme_get_option('general','disable_breadcrumb')==false){
			if($post_id){
				$disable = get_post_meta($post_id, '_disable_breadcrumb', true);
			}
			if(!isset($disable) || $disable == -1){
				breadcrumbs_plus(array(
					'prefix' => '<section id="breadcrumbs">',
					'suffix' => '</section>',
					'title' => false,
					'home' => __( 'Home', 'striking_front' ),
					'sep' => '&raquo;',
					'front_page' => false,
					'bold' => false,
					'blog' => __( 'Blog', 'striking_front' ),
					'echo' => true
				));
			}
		}
	}
	function portfolio_featured_image($layout=''){
		if($layout == 'full'){
			$width = 958;
		}else{
			$width = 628;
		}
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
		$adaptive_height = theme_get_option('portfolio', 'adaptive_height');
		if($adaptive_height){
			$height = floor($width*($image_src_array[2]/$image_src_array[1]));
		}else{
			$height = theme_get_option('portfolio', 'fixed_height');
		}
		$image_src = THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image_src_array[0]).'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1';
		if (has_post_thumbnail()): 
?>
	<div class="image_styled entry_image">
		<span class="image_frame" style="height:<?php echo $height;?>px;width:<?php echo $width;?>px">
<?php if(is_single()):?>
			<img src="<?php echo $image_src;?>" alt="<?php the_title();?>" />
			<span class="image_overlay"></span>
<?php else:?>
			<a class="image_icon_doc" href="<?php echo get_permalink() ?>" title="">
				<img src="<?php echo $image_src;?>" alt="<?php the_title();?>" />
				<span class="image_overlay"></span>
			</a>
<?php endif;?>
		</span>
		<img src="<?php echo THEME_IMAGES;?>/image_shadow.png" class="image_shadow" style="width:<?php echo ($width+2);?>px">
	</div>
<?php
		endif;
	}
	function blog_featured_image($type='full',$layout=''){
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
		if($layout == 'full'){
			$width = 958;
		}elseif(is_numeric($layout)){
			$width = $layout-2;
		}else{
			$width = 628;
		}
		if($type=='left'){
			$width = theme_get_option('blog', 'left_width');
			$height = theme_get_option('blog', 'left_height');
			$image_src = THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image_src_array[0]).'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1';
		}else{
			$adaptive_height = theme_get_option('blog', 'adaptive_height');
			if($adaptive_height){
				$height = floor($width*($image_src_array[2]/$image_src_array[1]));
			}else{
				$height = theme_get_option('blog', 'fixed_height');
			}
			$image_src = THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image_src_array[0]).'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1';
		}
		$featured_video = get_post_meta(get_the_ID(), '_featured_video', true);
		if(!empty($featured_video) && $type == 'full'):
			preg_match('@(?:\?v=|/v/)([-\w]+)|([-\w]+)$@', $featured_video, $matches);
			$youtubeId = $matches[ count($matches) - 1 ];
?>
<div class="entry_video">
<div class="video_frame">
<object width="<?php echo $width;?>" height="<?php echo $height;?>">
	<param name="movie" value="http://www.youtube.com/v/<?php echo $youtubeId;?>?fs=1&amp;rel=0&amp;showinfo=0&amp;showsearch=0&amp;iv_load_policy=3"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<param value="opaque" name="wmode">
	<embed src="http://www.youtube.com/v/<?php echo $youtubeId?>?fs=1&amp;rel=0&amp;showinfo=0&amp;showsearch=0&amp;iv_load_policy=3" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?php echo $width;?>" height="<?php echo $height;?>"></embed>
</object>
</div>
<img src="<?php echo THEME_IMAGES;?>/image_shadow.png" class="image_shadow" style="width:<?php echo ($width+2);?>px">
</div>
<?php
		elseif (has_post_thumbnail()):
?>
	<div class="image_styled entry_image">
		<span class="image_frame" style="height:<?php echo $height;?>px;width:<?php echo $width;?>px">
<?php if(is_single()):?>
			<img src="<?php echo $image_src;?>" alt="<?php the_title();?>" />
			<span class="image_overlay"></span>
<?php else:?>
			<a class="image_icon_doc" href="<?php echo get_permalink() ?>" title="">
				<img src="<?php echo $image_src;?>" alt="<?php the_title();?>" />
				<span class="image_overlay"></span>
			</a>
<?php endif;?>
		</span>
		<img src="<?php echo THEME_IMAGES;?>/image_shadow.png" class="image_shadow" style="width:<?php echo ($width+2);?>px">
	</div>
<?php
		endif;
	}
	function blog_meta()
	{
 		global $post;
		if(theme_get_option('blog','meta_comment') && ($post->comment_count > 0 || comments_open())): ?>
			<span class="comments"><?php comments_popup_link(__('No Comments','striking_front'), __('1 Comment','striking_front'), __('% Comments','striking_front'),''); ?></span>
<?php endif;
		if(theme_get_option('blog','meta_category')): ?>
			<span class="categories"><?php _e('Posted in: ', 'striking_front');  the_category(', '); ?></span>
			<span class="separater">|</span>
<?php endif; ?>
<?php if(theme_get_option('blog','meta_tags')): ?>
			<?php the_tags('<span class="tags">'.__('Tags: ', 'striking_front'),', ','</span> <span class="separater">|</span>'); ?>
<?php endif; ?>
<?php if(theme_get_option('blog','meta_author')): ?>
			<span class="author"><?php _e('By: ', 'striking_front');  the_author_link(); ?></span>
			<span class="separater">|</span>
<?php endif; ?>
<?php if(theme_get_option('blog','meta_date')): ?>
			<time datetime="<?php the_time('Y-m-d') ?>"><a href="<?php echo get_month_link(get_the_time('Y'), get_the_time('m')); ?>"><?php echo get_the_date(); ?></a></time>
<?php endif; 
			edit_post_link( __( 'Edit', 'striking_front' ), '<span class="separater">|</span> <span class="edit-link">', '</span>' );
	}
	function blog_author_info()
	{
?>
<section id="about_the_author">
	<h3><?php _e('About the author','striking_front');?></h3>
	<div class="author_content">
		<div class="gravatar"><?php echo get_avatar( get_the_author_meta('user_email'), '60' ); ?></div>
		<div class="author_info">
			<div class="author_name"><?php the_author_posts_link(); ?></div>
			<p class="author_desc"><?php the_author_description(); ?></p>
		</div>
		<div class="clearboth"></div>
	</div>
</section>
<?php 
	}
	function blog_popular_posts()
	{
		$r = new WP_Query(array(
			'showposts' => 3, 
			'nopaging' => 0, 
			'orderby'=> 'comment_count', 
			'post_status' => 'publish', 
			'caller_get_posts' => 1
		));
		$output = '';
		if ($r->have_posts()){
			$output .= '<h3>'.__('Popular Posts','striking_front').'</h3>';
			$output .= '<section class="popular_posts_wrap">';
			$output .= '<ul class="posts_list">';
			while ($r->have_posts()){
				$r->the_post();
				$output .= '<li>';
				$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
				if (has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}else{
					$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
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
			$output .= '</section>';
		}
		wp_reset_postdata();
		echo $output;
	}
	function blog_related_posts()
	{
		global $post;
		$backup = $post;  
		$tags = wp_get_post_tags($post->ID);
        $tagIDs = array();
        $related_post_found = false;
        $output = '';
		if ($tags) {
			$tagcount = count($tags);
			for ($i = 0; $i < $tagcount; $i++) {
				$tagIDs[$i] = $tags[$i]->term_id;
			}
			$r = new WP_Query(array(
				'tag__in' => $tagIDs,
				'post__not_in' => array($post->ID),
				'showposts'=>3,
				'caller_get_posts'=>1
			));
			if ($r->have_posts()){
				$related_post_found = true;
				$output .= '<h3>'.__('Related Posts','striking_front').'</h3>';
				$output .= '<section class="related_posts_wrap">';
				$output .= '<ul class="posts_list">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
					if (has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}else{
						$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
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
				$output .= '</section>';
			}
			$post = $backup;
		}
		if(!$related_post_found){
			$r = new WP_Query(array(
				'showposts' => 3, 
				'nopaging' => 0, 
				'post_status' => 'publish', 
				'caller_get_posts' => 1
			));
			if ($r->have_posts()){
				$output .= '<h3>'.__('Recent Posts','striking_front').'</h3>';
				$output .= '<section class="recent_posts_wrap">';
				$output .= '<ul class="posts_list">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
					if (has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}else{
						$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
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
				$output .= '</section>';
			}
		}
		wp_reset_postdata();
		echo $output;
	}
	function slideShow() {
		if (theme_get_option('slideshow', 'disable_slideshow')) {
			return;
		}
		$height = theme_get_option('slideshow', 'height') - 2;
		$posts = $this->slideShow_getPosts();
		echo '<div id="feature_box" class="slidershow">';
		echo '<div id="slidershow_wrap">';
		$i = 0;
		foreach($posts as $post){
			$i++;
			echo '<div class="slide_pane">';
			if(isset($post['video'])){
				preg_match('@(?:\?v=|/v/)([-\w]+)|([-\w]+)$@', $post['video'], $matches);
				$youtubeId = $matches[ count($matches) - 1 ];
			 	echo <<<HTML
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
			}else{
				$image_src = THEME_INCLUDES.'/timthumb.php?src='.$post['image'].'&amp;h='.$height.'&amp;w=960&amp;zc=1';
				echo '<div class="feature_box_image"><img src="'.$image_src.'" alt="'.$post['title'].'" /></div>';
			}
			echo '<div class="feature_box_overlap" id="feature_box_overlap' . $i .'">';
			echo '<div class="inner">';
			echo '<div class="feature_box_title"><a href="'.$post['link'].'">'.$post['title'].'</a></div>';
			echo '<div class="feature_box_category">'. __('CATEGORY: ', 'striking_front');
			the_category(', ');
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
		echo '<div id="sldershow_pagers"><div class="inner">';
		foreach($posts as $post){
			echo '<a href="#" class=""></a>';
		}
		echo '</div></div>';
		echo '</div>';		
		$options = array(
			'height' => theme_get_option('slideshow', 'height'), 
			'animSpeed' => theme_get_option('slideshow', 'animSpeed'), 
			'pauseTime' => theme_get_option('slideshow', 'pauseTime'), 
			'pauseOnHover' => theme_get_option('slideshow', 'pauseOnHover'), 
			'autoplay' => theme_get_option('slideshow', 'autoplay'), 
		);
		echo "\n<script type=\"text/javascript\">\n";
		echo "var slideShow = []; \n";
		foreach($options as $key => $value) {
			if (is_bool($value)) {
				$value = $value ? "true" : "false";
			} elseif($value!="true"&&$value!="false") {
				$value = "'" . $value . "'";
			}
			echo "slideShow['" . $key . "'] = " . $value . "; \n";
		}
		echo "</script>\n";
	}
	function slideShowHeader() {
		if (theme_get_option('slideshow', 'disable_slideshow')) {
			return;
		}
		wp_enqueue_script( 'jquery-tools-tabs-slidershow', THEME_JS .'/jquery.tools.tabs.slideshow.js', array('jquery-tools-tabs'),false,true);
		wp_enqueue_script('jquery-easing', THEME_JS . '/jquery.easing.1.3.js', array('jquery'),false,true);
		wp_enqueue_script('slider-init', THEME_JS . '/sliderInit.js',false,false,true);
	}
	function slideShow_getPosts(){
		$posts = array();
		if(theme_get_option('slideshow','posts')){
			$loop = new WP_Query( array('showposts'=>-1, 'post__in'=>theme_get_option('slideshow','posts'), 'nopaging' => 0,'post_status' => 'publish','caller_get_posts' => 1 ) );
		}else{
			$loop = new WP_Query( array('showposts'=>theme_get_option('slideshow','count'), 'nopaging' => 0,'post_status' => 'publish','caller_get_posts' => 1 ) );
		}
		while ( $loop->have_posts() ) : $loop->the_post();
			$featured_video = get_post_meta(get_the_ID(), '_featured_video', true);
			if(!empty($featured_video) || has_post_thumbnail()){
				$post = array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'link' => get_permalink(),
		 		);
				if(!empty($featured_video)){
					$post['video'] = $featured_video;
				}else{
					$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
					$post['image'] = get_image_src($image_src_array[0]);
				}
				$posts[] = $post;
			}
		endwhile;
		return $posts;
	}
	function slideShow_anything() {
		echo <<<HTML
<div id="feature_box" class="anything">
	<div class="inner">
		<div id="anything_slider_wrap">
			<ul id="anything_slider">
HTML;
		$images = $this->slideShow_getPosts('-1','full');
		$height = theme_get_option('slideshow','anything_height');
		foreach($images as $image) {
			$stop = get_post_meta($image['id'], '_anything_stop', true);
			if($stop === '1'){
				echo "\n<li class='panel stoped'>\n";
			}else{
				echo "\n<li class='panel'>\n";
			}
			switch(get_post_meta($image['id'], '_anything_type', true)){
				case 'sidebar':
					echo '<div class="anything_sidebar_'.get_post_meta($image['id'], '_sidebar_position', true).'">';
					echo '<div class="anything_sidebar_content">';
					$page_data = get_page( $image['id'] );
					$content = $page_data->post_content; 
					echo apply_filters('the_content', stripslashes( $content ));
					echo '</div>';
					echo '<div class="anything_sidebar_image">';
					if($image['link'] != ''){
						echo '<a href="'.$image['link'].'"><img src="' . THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image['src']).'&amp;h='.$height.'&amp;w=660&amp;zc=1" alt="" /></a>';
					}else{
						echo '<img src="' . THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image['src']).'&amp;h='.$height.'&amp;w=660&amp;zc=1" alt="" />';
					}
					echo '</div>';
					echo '</div>';
					break;
				case 'html':
					$page_data = get_page( $image['id'] );
					$content = $page_data->post_content; 
					echo apply_filters('the_content', stripslashes( $content ));
					break;
				case 'image':
				default:
					$caption_position = get_post_meta($image['id'], '_image_caption_position', true);
					if($caption_position != '' && $caption_position !='disable'){
						echo '<div class="anything_caption caption_'.$caption_position.'">';
						echo '<h3>'.$image['title'].'</h3>';
						if($image['desc']) echo '<p>'.$image['desc'].'</p>';
						echo '</div>';
					}
					if($image['link'] != ''){
						echo '<a href="'.$image['link'].'"><img src="' . THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image['src']).'&amp;h='.$height.'&amp;w=960&amp;zc=1" alt="" /></a>';
					}else{
						echo '<img src="' . THEME_INCLUDES.'/timthumb.php?src='.get_image_src($image['src']).'&amp;h='.$height.'&amp;w=960&amp;zc=1" alt="" />';
					}
					break;
			}
			echo "\n</li>\n";
		}
		echo <<<HTML
			</ul>
		</div>
	</div>
</div>
HTML;
		$options = array(
			'height' => theme_get_option('slideshow', 'anything_height'), 
			'buildArrows' => theme_get_option('slideshow', 'anything_buildArrows'), 
			'toggleArrows' => theme_get_option('slideshow', 'anything_toggleArrows'), 
			'buildNavigation' => theme_get_option('slideshow', 'anything_buildNavigation'), 
			'toggleControls' => theme_get_option('slideshow', 'anything_toggleControls'), 
			'autoPlay' => theme_get_option('slideshow', 'anything_autoPlay'), 
			'pauseOnHover' => theme_get_option('slideshow', 'anything_pauseOnHover'), 
			'resumeOnVideoEnd' => theme_get_option('slideshow', 'anything_resumeOnVideoEnd'),
			'stopAtEnd' => theme_get_option('slideshow', 'anything_stopAtEnd'),
			'playRtl' => theme_get_option('slideshow', 'anything_playRtl'),
			'delay' => theme_get_option('slideshow', 'anything_delay'),
			'animationTime' => theme_get_option('slideshow', 'anything_animationTime'),
			'easing' => theme_get_option('slideshow', 'anything_easing'),
		);
		echo "\n<script type=\"text/javascript\">\n";
		echo "var slideShow = []; \n";
		foreach($options as $key => $value) {
			if (is_bool($value)) {
				$value = $value ? "true" : "false";
			} elseif($value!="true"&&$value!="false") {
				$value = "'" . $value . "'";
			}
			echo "slideShow['" . $key . "'] = " . $value . "; \n";
		}
		echo "</script>\n";
	}
}
function theme_generator($function){
	global $_themeGenerator;
	$_themeGenerator = new themeGenerator;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array(array( &$_themeGenerator, $function ), $args );
}
