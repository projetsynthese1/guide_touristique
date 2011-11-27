<?php
/**
 * Twitter Widget Class
 */
class Theme_Widget_All_Author_Twitter extends WP_Widget {
	function Theme_Widget_All_Author_Twitter() {
		$widget_ops = array('classname' => 'widget_twitter', 'description' => __( 'Displays a list of twitter feeds', 'striking_admin' ) );
		$this->WP_Widget('all_author_twitter', THEME_SLUG.' - '.__('All Author Twitter', 'striking_admin'), $widget_ops);
		if ( is_active_widget(false, false, $this->id_base) ){
			add_action( 'wp_print_scripts', array(&$this, 'add_tweet_script') );
		}
	}
	function get_all_author(){
		global $wpdb;
		$order = 'user_nicename';
		$user_ids = $wpdb->get_col("SELECT ID FROM $wpdb->users ORDER BY $order");
		foreach($user_ids as $user_id) :
			$user = get_userdata($user_id);
			$all_authors[$user_id] = $user->display_name;
		endforeach;
		return $all_authors;
	}
	function add_tweet_script(){
		wp_enqueue_script('jquery-tweet');
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Tweets', 'striking_front') : $instance['title'], $instance, $this->id_base);
		$authors = $this->get_all_author();
		$user_array = array();
		foreach($authors as $author_id => $author_name){
			$user_array[] = '"'.get_the_author_meta( 'twitter',$author_id ).'"';
		}
		$avatar_size = (int)$instance['avatar_size'];
		if(empty($avatar_size)){
			$avatar_size = 'null';
		}
		$count = (int)$instance['count'];
		if($count < 1){
			$count = 1;
		}
		if ( !empty($user_array) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		$id = rand(1,1000);
		?>
		<script type="text/javascript">
				jQuery(document).ready(function($) {
					 $("#twitter_wrap_<?php echo $id;?>").tweet({
						avatar_size: <?php echo $avatar_size;?>,
						username: [<?php echo implode(',',$user_array);?>],
						count: <?php echo $count;?>,
						seconds_ago_text: '<?php _e('about %d seconds ago','striking_front');?>',
						a_minutes_ago_text: '<?php _e('about a minute ago','striking_front');?>',
						minutes_ago_text: '<?php _e('about %d minutes ago','striking_front');?>',
						a_hours_ago_text: '<?php _e('about an hour ago','striking_front');?>',
						hours_ago_text: '<?php _e('about %d hours ago','striking_front');?>',
						a_day_ago_text: '<?php _e('about a day ago','striking_front');?>',
						days_ago_text: '<?php _e('about %d days ago','striking_front');?>',
						view_text: '<?php _e('view tweet on twitter','striking_front');?>'
					 });
				});
		</script>
		<div id="twitter_wrap_<?php echo $id;?>"<?php if($avatar_size != 'null'):?> class="with_avatar"<?php endif;?>></div>
		<div class="clearboth"></div>
		<?php
			echo $after_widget;
		}
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = (int) $new_instance['count'];
		$instance['avatar_size'] = $new_instance['avatar_size']?(int) $new_instance['avatar_size']:'';
		return $instance;
	}
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 3;
		$avatar_size = isset($instance['avatar_size']) ? absint($instance['avatar_size']) : '';
		$display = isset( $instance['display'] ) ? $instance['display'] : 'latest';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'striking_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('avatar_size'); ?>"><?php _e('height and width of avatar if displayed (48px max)(optional)', 'striking_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('avatar_size'); ?>" name="<?php echo $this->get_field_name('avatar_size'); ?>" type="text" value="<?php echo $avatar_size; ?>" size="3" /></p>
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many tweets to display?', 'striking_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>
<?php
	}
}
