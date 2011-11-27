<?php
function theme_shortcode_table($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'id' => false,
		'type' => 'funcy',
	), $atts));
	return '<div'.($id?'id="'.$id.'"':'').' class="table_style">' . do_shortcode(trim($content)) . '</div>';
}
add_shortcode('styled_table','theme_shortcode_table');
