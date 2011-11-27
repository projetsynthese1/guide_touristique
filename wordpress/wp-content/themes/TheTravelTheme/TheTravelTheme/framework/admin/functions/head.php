<?php
add_action('admin_head', 'theme_add_head');
/**
 * Change the icon on every page where theme use.
 */
function theme_add_head() {
	?>
<style>
	<?php if (theme_is_post_type('portfolio')) : ?>
	#icon-edit { background:transparent url('<?php echo THEME_ADMIN_ASSETS_URI .'/images/portfolio_icon32.png';?>') no-repeat; }
	<?php endif; ?>
	<?php if (theme_is_post_type('slideshow')) : ?>
	#icon-edit { background:transparent url('<?php echo THEME_ADMIN_ASSETS_URI .'/images/slideshow_icon32.png';?>') no-repeat; }
	<?php endif; ?>
</style>
<script>
var theme_admin_assets_uri="<?php echo THEME_ADMIN_ASSETS_URI;?>";
</script>
	<?php
}
if(theme_is_options() || theme_is_post_type()){
	add_action('admin_init', 'theme_admin_add_script');
}
function theme_admin_add_script() {
	wp_enqueue_script('theme-script', THEME_ADMIN_ASSETS_URI . '/js/script.js');
	wp_enqueue_script('rangeinput',THEME_ADMIN_ASSETS_URI . '/js/rangeinput.js',array('jquery'));
	wp_enqueue_script('mColorPicker',THEME_ADMIN_ASSETS_URI . '/js/mColorPicker.js',array('jquery'));
	wp_enqueue_script('iphone-style-checkboxes',THEME_ADMIN_ASSETS_URI . '/js/iphone-style-checkboxes.js',array('jquery'));
	wp_enqueue_script('validator',THEME_ADMIN_ASSETS_URI . '/js/validator.js',array('jquery'));
	wp_register_script('shortcode',THEME_ADMIN_ASSETS_URI . '/js/shortcode.js',array('jquery'));
	add_thickbox();
}
if(is_admin()){
	add_action('admin_init', 'theme_admin_add_style');
}
function theme_admin_add_style() {
	wp_enqueue_style('theme-style', THEME_ADMIN_ASSETS_URI . '/css/style.css');
}
if(theme_is_options() && $_GET['page']=='theme_homepage'){
	add_filter('admin_head','theme_admin_tinymce');
	add_filter('admin_head','theme_homepage_option_add_script');
}
function theme_homepage_option_add_script(){
	wp_print_scripts('shortcode');
}
function theme_admin_tinymce() {
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}
?>
