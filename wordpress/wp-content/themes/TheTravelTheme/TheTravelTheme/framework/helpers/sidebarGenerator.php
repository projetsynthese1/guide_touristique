<?php
class sidebarGenerator {
	var $sidebar_names = array();
	var $footer_sidebar_count = 0;
	var $footer_sidebar_names = array();
	function sidebarGenerator(){
		$this->sidebar_names = array(
			'home'=>__('Homepage Widget Area','striking_admin'),
			'page'=>__('Page Widget Area','striking_admin'),
			'blog'=>__('Blog Widget Area','striking_admin'),
			'single'=>__('Single Blog Post Widget Area','striking_admin'),
			'portfolio' =>__('Portfolio Widget Area','striking_admin'),
		);
		$this->footer_sidebar_names = array(
			__('First Footer Widget Area','striking_admin'),
			__('Second Footer Widget Area','striking_admin'),
			__('Third Footer Widget Area','striking_admin'),
			__('Fourth Footer Widget Area','striking_admin'),
			__('Fifth Footer Widget Area','striking_admin'),
			__('Sixth Footer Widget Area','striking_admin'),
		);
	}
	function register_sidebar(){
		foreach ($this->sidebar_names as $name){
			register_sidebar(array(
				'name' => $name,
				'description' => $name,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
			));
		}
		//register footer sidebars
		foreach ($this->footer_sidebar_names as $name){
			register_sidebar(array(
				'name' =>  $name,
				'description' => $name,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));
		}
		//register custom sidebars
		$custom_sidebars = theme_get_option('sidebar','sidebars');
		if(!empty($custom_sidebars)){
			$custom_sidebar_names = explode(',',$custom_sidebars);
			foreach ($custom_sidebar_names as $name){
				register_sidebar(array(
					'name' =>  $name,
					'description' => $name,
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget' => '</section>',
					'before_title' => '<h3 class="widgettitle">',
					'after_title' => '</h3>',
				));
			}
		}
	}
	function get_sidebar($post_id){
		if(is_page()){
			$sidebar = $this->sidebar_names['page'];
		}
		if(is_front_page() || $post_id == theme_get_option('homepage','home_page') ){
			$sidebar = $this->sidebar_names['home'];
		}
		if(is_blog()){
			$sidebar = $this->sidebar_names['blog'];
		}
		if(is_singular('post')){
			$sidebar = $this->sidebar_names['single'];
		}elseif(is_singular('portfolio')){
			$sidebar = $this->sidebar_names['portfolio'];
		}
		if(is_search() || is_archive()){
			$sidebar = $this->sidebar_names['blog'];
		}
		if(!empty($post_id)){
			$custom = get_post_meta($post_id, '_sidebar', true);
			if(!empty($custom)){
				$sidebar = $custom;
			}
		}
		if(isset($sidebar)){
			dynamic_sidebar($sidebar);
		}
	}
	function get_footer_sidebar(){
		dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
		$this->footer_sidebar_count++;
	}
}
global $_sidebarGenerator;
$_sidebarGenerator = new sidebarGenerator;
add_action('widgets_init', array($_sidebarGenerator,'register_sidebar'));
function sidebar_generator($function){
	global $_sidebarGenerator;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array(array( &$_sidebarGenerator, $function ), $args );
}
