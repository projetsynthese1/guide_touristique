<?php
/**
 * Contact Form Widget Class
 */
class Theme_Widget_Contact_Form extends WP_Widget {
	function Theme_Widget_Contact_Form() {
		$widget_ops = array('classname' => 'widget_contact_form', 'description' => __( 'Displays a email contact form.', 'striking_admin') );
		$this->WP_Widget('contact_form', THEME_SLUG.' - '.__('Contact Form', 'striking_admin'), $widget_ops);
		if ( is_active_widget(false, false, $this->id_base) ){
			add_action( 'wp_print_scripts', array(&$this, 'add_script') );
		}
	}
	function add_script(){
		wp_enqueue_script( 'jquery-tools-validator');
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Email Us', 'striking_front') : $instance['title'], $instance, $this->id_base);
		$email= $instance['email'];
		echo $before_widget;
		if ( $title)
			echo $before_title . $title . $after_title;
		?>
		<form class="contact_form" action="<?php echo THEME_INCLUDES;?>/sendmail.php" method="post">
			<p><input type="text" required="required" id="contact_name" name="contact_name" class="text_input" value="" size="22" tabindex="5" />
			<label for="contact_name"><?php _e('Name *', 'striking_front'); ?></label></p>
			<p><input type="email" required="required" id="contact_email" name="contact_email" class="text_input" value="" size="22" tabindex="6"  />
			<label for="contact_email"><?php _e('Email *', 'striking_front'); ?></label></p>
			<p><textarea required="required" name="contact_content" class="textarea" cols="30" rows="5" tabindex="7"></textarea></p>
			<p><button type="submit" class="button white"><span><?php _e('Submit', 'striking_front'); ?></span></button></p>
			<input type="hidden" value="<?php echo $email;?>" name="contact_to"/>
		</form>
		<?php
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['email'] = strip_tags($new_instance['email']);
		return $instance;
	}
	function form( $instance ) {
		//Defaults
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$email = isset($instance['email']) ? esc_attr($instance['email']) :get_bloginfo('admin_email');
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'striking_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Your Email:', 'striking_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" /></p>
<?php
	}
}
