<?php
include_once (THEME_HELPERS . '/optionGenerator.php');
class shortcodesGenerator extends optionGenerator {
	function shortcodesGenerator($config, $shortcodes){
		$this->config = $config;
		$this->options = $shortcodes;
		add_action('admin_init', array(&$this, 'add_script'));
		add_action('admin_menu', array(&$this, 'create'));
	}
	function create() {
		if (function_exists('add_meta_box')) {
			if (! empty($this->config['callback']) && function_exists($this->config['callback'])) {
				$callback = $this->config['callback'];
			} else {
				$callback = array(&$this, 'render');
			}
			foreach($this->config['pages'] as $page) {
				add_meta_box($this->config['id'], $this->config['title'], $callback, $page, $this->config['context'], $this->config['priority']);
			}
		}
	}
	function add_script(){
		if( theme_is_post_type_new($this->config['pages']) || theme_is_post_type_post($this->config['pages']) ){
			wp_enqueue_script('shortcode',THEME_ADMIN_ASSETS_URI . '/js/shortcode.js',array('jquery'));
		}
	}
	function render() {
		global $post;
		echo '<div class="shortcode_selector"><table class="theme-options-table" cellspacing="0"><tbody><tr><th scope="row" style="text-align:right"><h4><label for="shortcode_selector">Shortcode</label></h4></th><td><select name="sc_selector">';
		echo '<option value="">Choose one...</option>';
		foreach($this->options as $shortcode) {
			echo '<option value="'.$shortcode['value'].'">'.$shortcode['name'].'</option>';
		}
		echo '</select></td></tr></tbody></table></div>';
		foreach($this->options as $shortcode) {
			echo '<div id="shortcode_'.$shortcode['value'].'" class="shortcode_wrap">';
			if(isset($shortcode['sub'])){
				echo '<div class="shortcode_sub_selector"><table cellspacing="0" class="theme-options-table"><tbody><th scope="row"><h4><label for="shortcode_selector">Type</label></h4></th><td><select name="sc_'.$shortcode['value'].'_selector">';
				echo '<option value="">Choose one...</option>';
				foreach($shortcode['options'] as $sub_shortcode) {
					echo '<option value="'.$sub_shortcode['value'].'">'.$sub_shortcode['name'].'</option>';
				}
				echo '</select></td></tr></tbody></table></div>';
				foreach($shortcode['options'] as $sub_shortcode) {
					echo '<div id="sub_shortcode_'.$sub_shortcode['value'].'" class="sub_shortcode_wrap"><table cellspacing="0" class="theme-options-table"><tbody>';
					foreach($sub_shortcode['options'] as $option){
						if (method_exists($this, $option['type'])) {
							$option['id']='sc_'.$shortcode['value'].'_'.$sub_shortcode['value'].'_'.$option['id'];
							$this->$option['type']($option);
						}
					}
					echo '</tbody></table></div>';
				}
			}else{
				echo '<table cellspacing="0" class="theme-options-table"><tbody>';
				foreach($shortcode['options'] as $option){
					if (method_exists($this, $option['type'])) {
						$option['id']='sc_'.$shortcode['value'].'_'.$option['id'];
						$this->$option['type']($option);
					}
				}
				echo '</tbody></table>';
			}
			echo '</div>';
		}
		echo '<p><input type="button" id="shortcode_send" class="button" value="Send Shortcode to Editor »"/></p>';
	}
}
