<?php 
$lessgap=' class="lessgap"';
if(theme_get_option('general','disable_breadcrumb')==false){
	if(isset($post->ID)){
		$disable = get_post_meta($post->ID, '_disable_breadcrumb', true);
	}
	if(!isset($disable) || $disable == -1){
		$lessgap='';
	}
}
?>
<aside id="sidebar">
	<div id="sidebar_content"<?php echo $lessgap;?>><?php if(isset($post)){theme_generator('sidebar',$post->ID);}else{theme_generator('sidebar');} ?></div>
	<div id="sidebar_bottom"></div>
</aside>
