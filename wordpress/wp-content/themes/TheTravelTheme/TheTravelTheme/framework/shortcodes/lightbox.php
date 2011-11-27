<?php
/**
 * icon:zoom, doc, play
 */
function theme_shortcode_lightbox($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'href' => '#',
		'title' => '',
		'group' => '',
		'width' => false,
		'height' => false,
		'iframe' => 'false',
		'inline' => 'false',
		'photo' => 'false',
		'close' => 'true',
	), $atts));
	if($width && is_numeric($width)){
		$width = ' data-width="'.$width.'"';
	}else{
		$width = '';
	}
	if($height && is_numeric($height)){
		$height = ' data-height="'.$height.'"';
	}else{
		$height = '';
	}
	if($iframe != 'false'){
		$iframe = ' data-iframe="true"';
	}else{
		$iframe = ' data-iframe="false"';
	}
	if($inline != 'false'){
		$inline = ' data-inline="true" data-href="'.$href.'"';
		$href = '#';
	}else{
		$inline = ' data-inline="false"';
	}
	if($photo != 'false'){
		$photo = ' data-photo="true"';
	}else{
		$photo = ' data-photo="false"';
	}
	if($close != 'false'){
		$close = ' data-close="true"';
	}else{
		$close = ' data-close="false"';
	}
	$content = do_shortcode(str_replace('[button','[button button="true"',$content));
	return '<a title="'.$title.'" href="'.$href.'"'.($group?' rel="'.$group.'"':'').' class="colorbox"'.$width.$height.$iframe.$inline.$photo.$close.'>'.$content.'</a>';
}
add_shortcode('lightbox', 'theme_shortcode_lightbox');
