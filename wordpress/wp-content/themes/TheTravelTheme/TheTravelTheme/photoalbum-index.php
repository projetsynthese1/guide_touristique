<?php
/*
Template Name: Photo Album
If you want to customize the look and feel of your photo album, follow these steps. 
You'll probably need a good understanding of HTML and CSS!
1. Copy this file into your current active theme's directory
2. Also copy all the files starting with "photoalbum-" into your theme's directory
      * Alternatively, you could only copy just the "photoalbum-" file you want to customize into your current themes directory.
3. Customize the CSS in photoalbum-header.html to your liking.
4. That's it :)
The main template files:
- photoalbum-albums-index.html shows all your Flickr sets (aka albums)
- photoalbum-album.html displays a highlight photo and all the photos for an album
- photoalbum-photo.html displays one photo, along with next and previous photo links 
Troubleshooting Tips:
Not all WordPress themes are created equal, so default look and feel might look a little weird
on your blog. Try looking at your theme's "index.php" and copy and paste any extra HTML or
PHP into this file.
$Revision: 128 $
$Date: 2008-04-24 00:16:32 -0400 (Thu, 24 Apr 2008) $
$Author: joetan54 $
*/
global $TanTanFlickrPlugin;
if (!is_object($TanTanFlickrPlugin)) wp_die('Flickr Photo Album plugin is not installed / activated!');
get_header();
// load the appropriate albums index, album's photos, or individual photo template.
// $photoTemplate contains the template being used
?>
<div id="feature" class="flickr_feature">
	<div class="top_shadow"></div>
	<div class="inner">
		<h1><?php
		switch($photoTemplate){
			case 'photoalbum-albums-index.html':
				echo 'Gallery';
				break;
			case 'photoalbum-album.html':
				echo $album['title'];
				break;
			case 'photoalbum-photo.html':
				echo $photo['title'];
				break;
		}
		?></h1>
	</div>
	<div class="bottom_shadow"></div>
</div>
	<div id="page" class="photoalbum">
		<div class="inner">
			<div id="main">
<?php
include($tpl = $TanTanFlickrPlugin->getDisplayTemplate($photoTemplate));
// uncomment this line to print out the template being used
// echo 'Photo Album Template: '.$tpl;
?>
		</div>
	</div>
</div>
<?php
// uncomment this if you need a sidebar
//get_sidebar();
get_footer();
?>
