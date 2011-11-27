<?php
function theme_shortcode_googlemap($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		"width" => false,
		"height" => '400',
		"address" => '',
		"latitude" => 0,
		"longitude" => 0,
		"zoom" => 1,
		"html" => '',
		"popup" => 'false',
		"controls" => '[]',
		"scrollwheel" => 'true',
		"maptype" => 'G_NORMAL_MAP',
		"marker" => 'true',
		'align' => false,
	), $atts));
	if($width && is_numeric($width)){
		$width = 'width:'.$width.'px;';
	}else{
		$width = '';
	}
	if($height && is_numeric($height)){
		$height = 'height:'.$height.'px';
	}else{
		$height = '';
	}
	if($center != 'false'){
		$center = ' block_center';	
	}else{
		$center = '';
	}
	$align = $align?' align'.$align:'';
	$id = rand(100,1000);
	if($marker != 'false'){
		return <<<HTML
[raw]
<div id="google_map_{$id}" class="google_map{$align}" style="{$width}{$height}"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#google_map_{$id}").gMap({
	    zoom: {$zoom},
	    markers:[{
	    	address: "{$address}",
			latitude: {$latitude},
	    	longitude: {$longitude},
	    	html: "{$html}",
	    	popup: {$popup}
		}],
		controls: {$controls},
		maptype: {$maptype},
	    scrollwheel:{$scrollwheel}
	});
});
</script>
[/raw]
HTML;
	}else{
return <<<HTML
[raw]
<div id="google_map_{$id}" class="google_map{$align}" style="{$width}{$height}"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#google_map_{$id}").gMap({
	    zoom: {$zoom},
	    latitude: {$latitude},
	    longitude: {$longitude},
	    address: "{$address}",
		controls: {$controls},
		maptype: {$maptype},
	    scrollwheel:{$scrollwheel}
	});
});
</script>
[/raw]
HTML;
	}
}
add_shortcode('gmap','theme_shortcode_googlemap');
