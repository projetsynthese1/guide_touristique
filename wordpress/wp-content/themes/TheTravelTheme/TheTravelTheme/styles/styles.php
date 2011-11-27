<?php
Header("Content-type: text/css");
$files=array(
	'reset' => 'Global Reset',
	'base'  => 'Base Style',
	'form'  => 'Form',
	'table' => 'Table',
	'columns'=> 'Columns',
	'divider' => 'Divider',
	'images' => 'Images Styles',
	'extended' => 'Extended Typography',
	'tabs' => 'Tabs & Accordion & Toggle',
	'boxes' => 'Boxes Styles',
	'buttons' => 'Buttons Styles',
	'colorbox' => 'ColorBox',
	'structure' => 'Structure',
	'header' => 'Header',
	'navigation' => 'Navigation',
	'feature' => 'Feature',
	'content' => 'Content',
	'footer' => 'Footer',
	'sliders' => 'Home Page Sliders',
	'portfolio' => 'Portfolio Styles',
	'blog' => 'Blog Styles',
	'photoalbum' => 'Photo Album',
	'widgets' => 'Widget Styles',
	'video' => 'Video',
	'enhance' => 'Enhance Styles'
);
foreach ($files as $file => $title){
	$title = str_pad($title, 57, " ", STR_PAD_BOTH);
	echo <<<TITLE
/* ======================================================= */
/*{$title}*/
/* ======================================================= */
TITLE;
	echo file_get_contents('css' .DIRECTORY_SEPARATOR.$file.'.css');
	echo <<<PAD
PAD;
}
?>
