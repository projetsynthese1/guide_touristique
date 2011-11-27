<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php theme_generator('title'); ?></title>
<?php if($custom_favicon = theme_get_option('general','custom_favicon')) { ?>
<link rel="shortcut icon" href="<?php echo $custom_favicon; ?>" />
<?php } ?>
<!-- Feeds and Pingback -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS2 Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!-- Stylesheets --> 
<link rel="stylesheet" href="<?php echo THEME_URI;?>/style.css">
<?php wp_head(); ?>
<!--[if IE 6 ]>
	<link href="<?php echo THEME_CSS;?>/ie6.css" media="screen" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?php echo THEME_JS;?>/dd_belatedpng-min.js"></script>
	<script type="text/javascript" src="<?php echo THEME_JS;?>/ie6.js"></script>
<![endif]-->
<!--[if IE 7 ]>
<link href="<?php echo THEME_CSS;?>/ie7.css" media="screen" rel="stylesheet" type="text/css">
<![endif]-->
<!--[if IE 8 ]>
<link href="<?php echo THEME_CSS;?>/ie8.css" media="screen" rel="stylesheet" type="text/css">
<![endif]-->
<!--[if IE]>
	<script type="text/javascript" src="<?php echo THEME_JS;?>/html5.js"></script>
<![endif]-->
<?php
	if(theme_get_option('font','enable_cufon')){
		theme_add_cufon_code();
	}
?>
<script type="text/javascript">
var image_url='<?php echo THEME_IMAGES;?>';
</script>
</head>
<body>
<header id="header">
	<div class="inner">
<?php if(theme_get_option('general','display_logo') && $custom_logo = theme_get_option('general','logo') ): 
?>
		<div id="logo">
			<a href="<?php echo home_url( '/' ); ?>"><img class="ie_png" src="<?php echo $custom_logo; ?>" /></a>
		</div>
<?php else:?>
		<div id="logo_text">
<?php 	
if (is_front_page() ) { 
	echo "<h1>"; 
?>
	<a id="site_name" href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a>
<?php 
	echo "</h1>";
} 
else {
?>
	<a id="site_name" href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a>
<?php 
}
?>
<?php if(theme_get_option('general','display_site_desc')){
		$site_desc = get_bloginfo( 'description' );
		if(!empty($site_desc)):?>
			<div id="site_description"><?php bloginfo( 'description' ); ?></div>
<?php endif;}?>
		</div>
<?php endif; ?>
<?php $top_area_type = theme_get_option('general','top_area_type');
	switch($top_area_type){
		case 'html':
			if(theme_get_option('general','top_area_html')){
				echo '<div id="top_area">';
				echo apply_filters('the_content', wpml_t(THEME_NAME, 'Top Area Html Code', stripslashes( theme_get_option('general','top_area_html') )));
				echo '</div>';
			}
			break;
		case 'wpml_flags':
			theme_generator('wpml_flags');
			break;
	}
?>
		<?php theme_generator('menu');?>
	</div>
</header>
