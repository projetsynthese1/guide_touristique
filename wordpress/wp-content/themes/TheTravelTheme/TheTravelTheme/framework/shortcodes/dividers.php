<?php
function theme_shortcode_divider() {
	return '<div class="divider"></div>';
}
add_shortcode('divider', 'theme_shortcode_divider');
function theme_shortcode_divider_top() {
	return '<div class="divider top"><a href="#">'.__('Top','striking_front').'</a></div>';
}
add_shortcode('divider_top', 'theme_shortcode_divider_top');
function theme_shortcode_divider_padding() {
	return '<div class="divider_padding"></div>';
}
add_shortcode('divider_padding', 'theme_shortcode_divider_padding');
function theme_shortcode_divider_line() {
	return '<div class="divider_line"></div>';
}
add_shortcode('divider_line', 'theme_shortcode_divider_line');
function theme_shortcode_clearboth() {
   return '<div class="clearboth"></div>';
}
add_shortcode('clearboth', 'theme_shortcode_clearboth');
