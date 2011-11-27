<?php 
/**
 * JavaScripts In Header
 */
function theme_enqueue_scripts() {
	if(!is_admin()){
		wp_enqueue_script( 'jqueryslidemenu', THEME_JS .'/jqueryslidemenu.js', array('jquery'),false,true);
		wp_enqueue_script( 'jquery-tools-tabs', THEME_JS .'/jquery.tools.tabs.min.js', array('jquery'),false,true);
		wp_enqueue_script( 'jquery-colorbox', THEME_JS .'/jquery.colorbox-min.js', array('jquery'),false,true);
		wp_enqueue_script( 'jquery-swfobject', THEME_JS .'/jquery.swfobject.1-1-1.min.js', array('jquery'),false,true);
		wp_enqueue_script( 'video-js', THEME_JS .'/video.js', array('jquery'),false,true);
		wp_enqueue_script( 'custom-js', THEME_JS .'/custom.js', array('jquery'),false,true);
		
		wp_register_script( 'cufon-yui', THEME_JS .'/cufon-yui.js', array('jquery'),false,true);
		wp_register_script( 'jquery-quicksand', THEME_JS .'/jquery.quicksand.min.js', array('jquery'),false,true);
		wp_register_script('jquery-easing', THEME_JS . '/jquery.easing.1.3.js', array('jquery'),false,true);
		wp_register_script( 'jquery-gmap', THEME_JS .'/jquery.gmap-1.1.0-min.js', array('jquery'),false,true);
		wp_register_script( 'jquery-tweet', THEME_JS .'/jquery.tweet.js', array('jquery'),false,true);
		wp_register_script( 'jquery-tools-validator', THEME_JS .'/jquery.tools.validator.min.js', array('jquery'),false,true);
		if( (is_front_page()) ){
			theme_generator('slideShowHeader');
		}
		
		if ( is_singular() ){
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action('wp_print_scripts', 'theme_enqueue_scripts');

if(theme_get_option('general','enable_gmap')){
	function theme_add_gmap_script(){
		echo "\n<script type='text/javascript' src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".theme_get_option('general','gmap_api_key')."'></script>\n";
		wp_print_scripts('jquery-gmap');
	}
	add_filter('wp_head','theme_add_gmap_script');
}

if(theme_get_option('font','enable_cufon')){
	function theme_add_cufon_script(){
		$fonts = theme_get_option('font','fonts');
		if(is_array($fonts)){
			foreach ($fonts as $font){
				wp_register_script($font, THEME_FONT_URI .'/'.$font, array('cufon-yui'));
				wp_print_scripts($font);
			}
		}
		wp_print_scripts('cufon-yui');
	}
	add_filter('wp_head','theme_add_cufon_script');	
}
?>
