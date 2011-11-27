<?php
/* Load the Theme class. */
require_once (TEMPLATEPATH . '/framework/theme.php');

$theme = new Theme();
$theme->init(array(
	'theme_name' => 'Travel', 
	'theme_slug' => 'travel'
));

require_once (TEMPLATEPATH . '/travel/travel.php');
?>
