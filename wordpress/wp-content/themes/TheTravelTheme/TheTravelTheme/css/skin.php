<?php require_once( '../../../../wp-load.php' );
	Header("Content-type: text/css");
	$color = theme_get_option('color');
	function html2rgb($color)
	{
	    if ($color[0] == '#')
	        $color = substr($color, 1);
	    if (strlen($color) == 6)
	        list($r, $g, $b) = array($color[0].$color[1],
	                                 $color[2].$color[3],
	                                 $color[4].$color[5]);
	    elseif (strlen($color) == 3)
	        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	    else
	        return false;
	    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
	    return array($r, $g, $b, $color);
	}
	$feature_bg=html2rgb($color['feature_bg']);
	$feature_bg_Opacity = base_convert($color['feature_bg_Opacity']*255,10,16);
	$font = theme_get_option('font');
	$font['font_family']=stripslashes($font['font_family']);
	if($color['page_h1']==''){
		$color['page_h1']=$color['page_header'];
	}
	if($color['page_h2']==''){
		$color['page_h2']=$color['page_header'];
	}
	if($color['page_h3']==''){
		$color['page_h3']=$color['page_header'];
	}
	if($color['page_h4']==''){
		$color['page_h4']=$color['page_header'];
	}
	if($color['page_h5']==''){
		$color['page_h5']=$color['page_header'];
	}
	if($color['page_h6']==''){
		$color['page_h6']=$color['page_header'];
	}
	$logo_bottom = theme_get_option('general','logo_bottom');
	$posts_gap = theme_get_option('blog','posts_gap');
	$header_height = theme_get_option('general','header_height');
	$slidershow_height = theme_get_option('slideshow','height');
	$slidershow_loading_height = $slidershow_height - 2;
	$blog_left_image_width = theme_get_option('blog', 'left_width');
	$blog_left_image_height = theme_get_option('blog','left_height');
	$blog_left_image_shadow_width = $blog_left_image_width +2;
	$custom_css =  stripslashes(theme_get_option('general','custom_css'));
	foreach($color as $key => $value){
		if($value == ''){
			$color[$key]='transparent';
		}
	}
	echo <<<CSS
body {
	font-family: {$font['font_family']};
}
#header {
	height: {$header_height}px;
	background-color: {$color['header_bg']};
}
#site_name {
	color: {$color['site_name']};
	font-size: {$font['site_name']}px;
}
#site_description {
	color: {$color['site_description']};
	font-size: {$font['site_description']}px;
}
#logo, #logo_text {
	bottom: {$logo_bottom}px;
}
#navigation ul li a, #main_navigation ul li a:visited {
	font-size: {$font['menu_top']}px;
	color: {$color['menu_top']};
}
#navigation .menu > .current-menu-item > a,#navigation .menu > .current-menu-item > a:visited,
#navigation .menu > .current-menu-ancestor > a {
	color: {$color['menu_top_current']};
}
#navigation .menu > .current_page_item > a,#main_navigation .menu > .current_page_item > a:visited,
#navigation .menu > .current_page_ancestor > a {
	color: {$color['menu_top_current']};
}
#navigation ul li a:hover, #navigation ul li a:active {
	color: {$color['menu_top_active']};
}
#navigation ul ul li a, #navigation ul ul li a:visited {
	font-size: {$font['menu_sub']}px;
	color: {$color['menu_sub']};
}
#navigation ul ul li a:hover, #navigation ul ul li a:active {
	color: {$color['menu_sub_active']};
}
#navigation ul li ul {
	background-color: {$color['menu_sub_background']};
}
#navigation ul li ul li a:hover, #navigation ul ul li a:hover {
	background-color: {$color['menu_sub_hover_background']};
}
#feature .feature_title {
	font-size: {$font['feature_header']}px;
	color: {$color['feature_header']};
}
#introduce {
	font-size: {$font['feature_introduce']}px;
	color: {$color['feature_introduce']};
}
#introduce a {
	color: {$color['feature_introduce']};
}
#feature_box {
	background-color: {$color['feature_area_bg']};
}
.feature_box_overlap,#feature {
	background-color: {$color['feature_bg']};
	background-color: rgba($feature_bg[0], $feature_bg[1], $feature_bg[2], {$color['feature_bg_Opacity']});  
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorStr='#{$feature_bg_Opacity}{$feature_bg[3]}',EndColorStr='#{$feature_bg_Opacity}{$feature_bg[3]}'); 
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorStr='#{$feature_bg_Opacity}{$feature_bg[3]}',EndColorStr='#{$feature_bg_Opacity}{$feature_bg[3]}')"; 
}
.feature_box_title{
	color: {$color['feature_box_text']};
	font-size: {$font['feature_box_title']}px;
}
.feature_box_title a,.feature_box_title a:visited,.feature_box_title a:hover,.feature_box_title a:active {
	font-size: {$font['feature_box_title']}px;
	color: {$color['feature_box_text']};
}
.feature_box_category {
	font-size: {$font['feature_box_category']}px;
	color: {$color['feature_box_text']};
	margin: 0 0 0 15px;
}
.feature_box_category a, .feature_box_category a:visited {
	color: {$color['feature_box_category']};
}
.feature_box_category a:hover, .feature_box_category a:active {
	color: {$color['feature_box_category_active']};
}
#page {
	background-color: {$color['page_bg']};
	color: {$color['page']};
	font-size: {$font['page']}px;
}
ul.mini_tabs li.current, ul.mini_tabs li.current a {
	background-color: {$color['page_bg']};
}
.divider.top a {
	background-color: {$color['page_bg']};
}
#sidebar,#sidebar_box_1,#sidebar_box_2 {
	background-color: {$color['page_bg']};
}
#page_bottom{
	background-color: {$color['page_bg']};
}
#page h1,#page h2,#page h3,#page h4,#page h5,#page h6{
	color: {$color['page_header']};
}
#page h1 {
	color: {$color['page_h1']};
}
#page h2 {
	color: {$color['page_h2']};
}
#page h3 {
	color: {$color['page_h3']};
}
#page h4 {
	color: {$color['page_h4']};
}
#page h5 {
	color: {$color['page_h5']};
}
#page h6 {
	color: {$color['page_h6']};
}
#page a, #page a:visited {
	color: {$color['page_link']};
}
#page a:hover, #page a:active {
	color: {$color['page_link_active']};
}
#page h1 a,#page h1 a:visited,#page h1 a:hover,#page h1 a:active {
	color: {$color['page_h1']};
}
#page h2 a,#page h2 a:visited,#page h2 a:hover,#page h2 a:active {
	color: {$color['page_h2']};
}
#page h3 a,#page h3 a:visited,#page h3 a:hover,#page h3 a:active {
	color: {$color['page_h3']};
}
#page h4 a,#page h4 a:visited,#page h4 a:hover,#page h4 a:active {
	color: {$color['page_h4']};
}
#page h5 a,#page h5 a:visited,#page h5 a:hover,#page h5 a:active {
	color: {$color['page_h5']};
}
#page h6 a,#page h6 a:visited,#page h6 a:hover,#page h6 a:active {
	color: {$color['page_h6']};
}
#page .portfolios.sortable header a {
	background-color:{$color['portfolio_header_bg']};
	color:{$color['portfolio_header_text']};
}
#sidebar .widget a, #sidebar .widget a:visited {
	color: {$color['portfolio_header_text']};
}
#sidebar .widget a:hover, #sidebar .widget a:active {
	color: {$color['sidebar_link_active']};
}
#sidebar .widgettitle {
	font-size: {$font['widget_title']}px;
}
#breadcrumbs {
	color: {$color['breadcrumbs']};
}
#breadcrumbs a, #breadcrumbs a:visited {
	color: {$color['breadcrumbs_link']};
}
#breadcrumbs a:hover, #breadcrumbs a:active {
	color: {$color['breadcrumbs_active']};
}
#page .portfolio_title {
	font-size: {$font['portfolio_title']}px;
}
#footer {
	background-color:{$color['footer_bg']};
	color: {$color['footer_text']};
	font-size: {$font['footer_text']}px;
}
#footer .widget a, #footer .widget a:visited{
	color: {$color['footer_link']};
}
#footer .widget a:active, #footer .widget a:hover{
	color: {$color['footer_link_active']};
}
#footer h4.widgettitle {
	color: {$color['footer_title']};
	font-size: {$font['footer_title']}px;
}
#footer_bottom {
	background-color:{$color['sub_footer_bg']};
}
#copyright {
	color: {$color['copyright']};
	font-size: {$font['copyright']}px;
}
#copyright a,#copyright a:hover,#copyright a:visited,#copyright a:active {
	color: {$color['copyright']};
}
#footer_menu a {
	font-size: {$font['footer_menu']}px;
}
#footer_menu a, #footer_menu a:visited{
	color: {$color['footer_menu']};
}
#footer_menu a:hover, #footer_menu a:active {
	color: {$color['footer_menu_active']};
}
.divider, .divider_line, .commentlist li,.entry .entry_meta,#sidebar .widget li,#sidebar .widget_pages ul ul,#about_the_author .author_content {
	border-color: {$color['divider_line']};
}
h1 {
	font-size: {$font['h1']}px;
}
h2 {
	font-size: {$font['h2']}px;
}
h3 {
	font-size: {$font['h3']}px;
}
h4 {
	font-size: {$font['h4']}px;
}
h5 {
	font-size: {$font['h5']}px;
}
h6 {
	font-size: {$font['h6']}px;
}
#slidershow_wrap, #feature_box.slidershow {
	height: {$slidershow_height}px;
}
#slidershow_loading {
	height: {$slidershow_loading_height}px;
}
.entry {
	margin-bottom: {$posts_gap}px;
}
.entry_title {
	font-size: {$font['entry_title']}px;
}
.entry_left .entry_image .image_frame {
	width: {$blog_left_image_width}px;
	height: {$blog_left_image_height}px;
}
.entry_left .entry_image, .entry_left .entry_image .image_shadow {
	width: {$blog_left_image_shadow_width}px;
}
{$custom_css}
CSS;
?>
