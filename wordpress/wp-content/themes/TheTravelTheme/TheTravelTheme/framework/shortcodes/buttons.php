<?php
function theme_shortcode_button($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'id' => false,
		'class' => false,
		'size' => 'small',
		'link' => '',
		'linktarget' => '',
		'color' => 'gray',
		'bgcolor' => '',
		'textcolor' => '',
		'hoverbgcolor' => '',
		'hovertextcolor' => '',
		'full' => "false",
		'align' => false,
		'button' => "false",
	), $atts));
	$id = $id?' id="'.$id.'"':'';
	$full = ($full==="false")?'':' full';
	$color = $color?' '.$color:'';
	$class = $class?' '.$class:'';
	$link = $link?' href="'.$link.'"':'';
	$linktarget = $linktarget?' target="'.$linktarget.'"':'';
	$hoverbgcolor = $hoverbgcolor?($bgcolor?' data-bg="'.$bgcolor.'"':'').' data-hoverBg="'.$hoverbgcolor.'"':'';
	$hovertextcolor = $hovertextcolor?($textcolor?' data-color="'.$textcolor.'"':'').' data-hoverColor="'.$hovertextcolor.'"':'';
	$bgcolor = $bgcolor?' style="background-color:'.$bgcolor.'"':'';
	$textcolor = $textcolor?' style="color:'.$textcolor.'"':'';
	if($align != 'center'){
		$aligncss = ' align'.$align;
	}else{
		$aligncss = '';
	}
	if($button == 'true'){
		$tag = 'button';
	}else{
		$tag = 'a';
	}
	$content = '<'.$tag.$id.$link.$linktarget.$bgcolor.$hoverbgcolor.$hovertextcolor.' class="button '.$size.$color.$full.$class.$aligncss.'"><span'.$textcolor.'>' . trim($content) . '</span></'.$tag.'>';
	if($align === 'center'){
		return '<p class="center">'.$content.'</p>';
	}else{
		return $content;
	}
}
add_shortcode('button','theme_shortcode_button');
