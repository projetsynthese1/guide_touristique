<?php
/**
 * Geo Map Widget Class
 */
class Theme_Widget_Geo_Map extends WP_Widget {
	function Theme_Widget_Geo_Map() {
		$widget_ops = array('classname' => 'widget_geo', 'description' => __( 'Displays a map of the post', 'striking_admin' ) );
		$this->WP_Widget('geo_map', THEME_SLUG.' - '.__('Geo Map', 'striking_admin'), $widget_ops);
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		if(!class_exists('GeoMashupDB')){
			return;
		}
		global $wp_query;
		global $post;
		$backup = $wp_query->in_the_loop;
		$wp_query->in_the_loop = true;
		$location = GeoMashupDB::get_object_location( 'post', $post->ID );
		if(!empty($location)){
		echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
			echo do_shortcode('[geo_mashup_map]');
			echo '<div class="clearboth"></div>';
			echo $after_widget;
		}
		$wp_query->in_the_loop = $backup;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'striking_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
<?php
	}
}
