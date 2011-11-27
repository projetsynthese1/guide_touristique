<?php
function theme_shortcode_contactform($atts,$content = null) {
	extract(shortcode_atts(array(
		'email' => get_bloginfo('admin_email'),
	), $atts));
    wp_print_scripts('jquery-tools-validator');
    $content = trim($content);
	if(!empty($content)){
		$success = do_shortcode($content);
	}
	if(empty($success)){
		$success = __('Your message was successfully sent. <strong>Thank You!</strong>','striking_front');
	}
	$include_path = THEME_INCLUDES;
	return <<<HTML
[raw]
<div class="contact_form_wrap">
	<div class="success" style="display:none;"><div class="message_box_content">{$success}</div></div> 
	<form class="contact_form" action="{$include_path}/sendmail.php" method="post">
		<p><input type="text" required="required" id="contact_name" name="contact_name" class="text_input" value="" tabindex="5" />
		<label for="contact_name"><?php _e('Name *','striking_front');?></label></p>
		<p><input type="email" required="required" id="contact_email" name="contact_email" class="text_input" value="" tabindex="6"  />
		<label for="contact_email"><?php _e('Email *','striking_front');?></label></p>
		<p><textarea required="required" name="contact_content" class="textarea" cols="30" rows="5" tabindex="7"></textarea></p>
		<p><button type="submit" class="button white"><span><?php _e('Submit','striking_front');?></span></button></p>
		<input type="hidden" value="{$email}" name="contact_to"/>
	</form>
</div>
[/raw]
HTML;
}
add_shortcode('contactform', 'theme_shortcode_contactform');
function theme_shortcode_twitter($atts) {
	extract(shortcode_atts(array(
		'username' => '',
		'count' => 3,
		'query' => 'null',
		'avatarsize' => 'null',
	), $atts));
	$user_array = explode(',',$username);
	foreach($user_array as $key => $user){
		$user_array[$key] = '"'.$user.'"';
	}	
	wp_print_scripts('jquery-tweet');
	$id = rand(1,1000);
	$seconds_ago_text = __('about %d seconds ago','striking_front');
	$a_minutes_ago_text = __('about a minute ago','striking_front');
	$minutes_ago_text = __('about %d minutes ago','striking_front');
	$a_hours_ago_text = __('about an hour ago','striking_front');
	$hours_ago_text = __('about %d hours ago','striking_front');
	$a_day_ago_text = __('about a day ago','striking_front');
	$days_ago_text = __('about %d days ago','striking_front');
	$view_text = __('view tweet on twitter','striking_front');
	if ( !empty( $user_array )|| $query!="null" ) {
		$username = implode(',',$user_array);
		if($query != "null"){
			$query = '"'.html_entity_decode($query).'"';
		}
		$with_avatar = ($avatarsize != 'null')?' with_avatar':'';
		return <<<HTML
[raw]
<div class="twitter_wrap{$with_avatar}">
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#twitter_wrap_{$id}").tweet({
		username: [{$username}],
		count: {$count},
		query: {$query},
		avatar_size: {$avatarsize},
		seconds_ago_text: '{$seconds_ago_text}',
		a_minutes_ago_text: '{$a_minutes_ago_text}',
		minutes_ago_text: '{$minutes_ago_text}',
		a_hours_ago_text: '{$a_hours_ago_text}',
		hours_ago_text: '{$hours_ago_text}',
		a_day_ago_text: '{$a_day_ago_text}',
		days_ago_text: '{$days_ago_text}',
		view_text: '{$view_text}'
	});
});
</script>
	<div id="twitter_wrap_{$id}"></div>
	<div class="clearboth"></div>
</div>
[/raw]
HTML;
	}
}
add_shortcode('twitter', 'theme_shortcode_twitter');
function theme_shortcode_flickr($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'type' => 'user',
		'count' => 4,
		'display' => 'latest'//lastest or random
	), $atts));
	return <<<HTML
[raw]
<div class="flickr_wrap">
	<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count={$count}&amp;display={$display}&amp;size=s&amp;layout=x&amp;source={$type}&amp;{$type}={$id}"></script>
</div>
<div class="clearboth"></div>
[/raw]
HTML;
}
add_shortcode('flickr', 'theme_shortcode_flickr');
function theme_shortcode_contact_info($atts) {
	extract(shortcode_atts(array(
		'color' => '',
		'phone' => '',
		'cellphone' => '',
		'email' => '',
		'address' => '',
		'city' => '',
		'state' => '',
		'zip' => '',
		'name' => '',
	), $atts));
	if(!empty($city) && !empty($state)){
		$city = $city.',&nbsp;'.$state;
	}elseif(!empty($state)){
		$city = $state;
	}
	if(!empty($color)){
		$color = ' '.$color;
	}
	$output = '<div class="contact_info_wrap">';
	if(!empty($phone)){
		$output .= '<p><span class="icon_text icon_phone'.$color.'">'.$phone.'</span></p>';
	}
	if(!empty($cellphone)){
		$output .= '<p><span class="icon_text icon_cellphone'.$color.'">'.$cellphone.'</span></p>';
	}
	if(!empty($email)){
		$output .= '<p><a href="mailto:'.$email.'" class="icon_text icon_email'.$color.'">'.$email.'</a></p>';
	}
	if(!empty($address)){
		$output .= '<p><span class="icon_text icon_home'.$color.'">'.$address.'</span></p>';
	}
	if(!empty($city)||!empty($zip)){
		$output .= '<p class="contact_address">';
		if(!empty($city)){
			$output .= '<span>'.$city.'</span>';
		}
		if(!empty($zip)){
			$output .= '<span class="contact_zip">'.$zip.'</span>';
		}
	}
	if(!empty($name)){
		$output .= '<p><span class="icon_text icon_id'.$color.'">'.$name.'</span></p>';
	}
	$output .= '</div>';
	return $output;
}
add_shortcode('contact_info', 'theme_shortcode_contact_info');
function theme_shortcode_popular_posts($atts) {
	extract(shortcode_atts(array(
		'count' => '4',
		'thumbnail' => 'true',
		'extra' => 'desc',
		'cat' => '',
		'desc_length' => '80',
	), $atts));
	$query = array('showposts' => $count, 'nopaging' => 0, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'caller_get_posts' => 1);
	if($cat){
		$query['cat'] = $cat;
	}
	$r = new WP_Query($query);
	if ($r->have_posts()){
		$output = '<div class="popular_posts_wrap">';
		$output .= '<ul class="posts_list">';
		while ($r->have_posts()){
			$r->the_post();
			$output .= '<li>';
			if($thumbnail!='false'){
				$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
				if (has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}else{
					$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
				}
				$output .= '</a>';
			}
			$output .= '<div class="post_extra_info">';
			$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
			if($extra=='time'){
				$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
			}else{
				global $post;
				$excerpt = $post->post_excerpt;
				if($excerpt==''){
					$excerpt = get_the_content('');
				}
				$output .= '<p>'.wp_html_excerpt($excerpt,$desc_length).'...</p>';
			}
			$output .= '</div>';
			$output .= '<div class="clearboth"></div>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</div>';
	} 
	wp_reset_query();
	return '[raw]'.$output.'[/raw]';
}
add_shortcode('popular_posts', 'theme_shortcode_popular_posts');
function theme_shortcode_recent_posts($atts) {
	extract(shortcode_atts(array(
		'count' => '4',
		'thumbnail' => 'true',
		'extra' => 'desc',
		'cat' => '',
		'desc_length' => '80',
	), $atts));
	$query = array('showposts' => $count, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1);
	if($cat){
		$query['cat'] = $cat;
	}
	$r = new WP_Query($query);
	if ($r->have_posts()){
		$output = '<div class="popular_posts_wrap">';
		$output .= '<ul class="posts_list">';
		while ($r->have_posts()){
			$r->the_post();
			$output .= '<li>';
			if($thumbnail!='false'){
				$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';
				if (has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),array(65,65),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}else{
					$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="65" height="65" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
				}
				$output .= '</a>';
			}
			$output .= '<div class="post_extra_info">';
			$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
			if($extra=='time'){
				$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
			}else{
				global $post;
				$excerpt = $post->post_excerpt;
				if($excerpt==''){
					$excerpt = get_the_content('');
				}
				$output .= '<p>'.wp_html_excerpt($excerpt,$desc_length).'...</p>';
			}
			$output .= '</div>';
			$output .= '<div class="clearboth"></div>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</div>';
	} 
	wp_reset_query();
	return '[raw]'.$output.'[/raw]';
}
add_shortcode('recent_posts', 'theme_shortcode_recent_posts');
