<?php
/*-----------------------------------------------------------------------------------*/
/* Gallery Images Metabox */
/*-----------------------------------------------------------------------------------*/
if (! function_exists("theme_gallery_image_option")) {
	function theme_gallery_image_option($value, $default) {
		global $post;
?>
	<div id="gallery_actions">
		<a title="Add Media" class="thickbox" id="add_media" href="media-upload.php?post_id=<?php echo $post->ID; ?>&gallery_image_upload=1&type=image&TB_iframe=1&width=640&height=644" style="border:none;text-decoration:none;">
			<input type="button" class="button-primary" value="Add Image" id="add-image" name="add">
		</a>
	</div>
	<div id="galleryTableWrapper">
		<table class="widefat galleryTable" cellspacing="0">
			<thead>
				<tr>
					<th width="10" scope="row">&nbsp;</th>
					<th width="70" scope="row">Thumbnail</th>
					<th width="150" scope="row">Title</th>
					<th scope="row">Description</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4">
						<div>
							<ul id="imagesSortable">
	<?php 
		$image_ids_str = get_post_meta($post->ID, '_image_ids', true);
		if(!empty($image_ids_str)){
			$image_ids = explode(',',str_replace('image-','',$image_ids_str));
			foreach($image_ids as $image_id){
				gallery_create_image_item($image_id);
			}
		}
	?>
							</ul>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" id="gallery_image_ids" name="_image_ids" value="<?php echo get_post_meta($post->ID, '_image_ids', true);?>">
	</div>
<?php
	}
}
$config = array(
	'title' => __('Gallery Images','striking_admin'),
	'id' => 'single',
	'pages' => array('gallery'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
$options = array(
	array(
		"name" => __("Layout",'striking_admin'),
		"id" => "_image_ids",
		"layout" => false,
		"function" => "theme_gallery_image_option",
		"type" => "custom",
	),
);
new metaboxesGenerator($config,$options);
/*-----------------------------------------------------------------------------------*/
/* Gallery image ajax callback
/*-----------------------------------------------------------------------------------*/
//gallery insert image ajax action callback
function gallery_get_image_action_callback() {
	$html = gallery_create_image_item($_POST['id']);
	if (! empty($html)) {
		echo $html;
	} else {
		die(0);
	}
	die();
}
add_action('wp_ajax_theme-gallery-get-image', 'gallery_get_image_action_callback');
//gallery image edit action callback
function gallery_image_edit_action_callback() {
	include (THEME_ADMIN_AJAX . '/gallery-image-edit.php');
	die();
}
add_action('wp_ajax_theme-gallery-image-edit', 'gallery_image_edit_action_callback');
// gallery metaboax function
function gallery_create_image_item($attachment_id) {
	$image = & get_post($attachment_id);
	if ($image) {
		$meta = wp_get_attachment_metadata($attachment_id);
		$date = mysql2date(get_option('date_format'), $image->post_date);
		$size = $meta['width'] . ' x ' . $meta['height'] . 'pixel';
		include (THEME_ADMIN_AJAX . '/gallery-image-item.php');
	}
}
/*-----------------------------------------------------------------------------------*/
/* Add scripts and styles for gallery */
/*-----------------------------------------------------------------------------------*/
function gallery_add_scripts_and_styles() {
	wp_deregister_script('autosave');
	wp_register_script('theme-gallery', THEME_ADMIN_ASSETS_URI . '/js/gallery.js', array('jquery-ui-sortable'));
	wp_enqueue_script('theme-gallery');
	wp_register_style('theme-gallery', THEME_ADMIN_ASSETS_URI . '/css/gallery.css');
	wp_enqueue_style('theme-gallery');
	add_thickbox();
}
if(theme_is_post_type_edit('gallery') || theme_is_post_type_new('gallery')){
	gallery_add_scripts_and_styles();
}
if (isset($_GET['gallery_image_upload']) || isset($_POST['gallery_image_upload'])) {
	include_once (THEME_ADMIN_FUNCTIONS . '/gallery-media-upload.php');
}
if (isset($_GET['gallery_edit_image'])) {
	wp_register_script('theme-gallery-edit-image', THEME_ADMIN_ASSETS_URI . '/js/gallery-edit-image.js');
	wp_enqueue_script('theme-gallery-edit-image');
	wp_register_style('theme-gallery-edit-image', THEME_ADMIN_ASSETS_URI . '/css/gallery-edit-image.css');
	wp_enqueue_style('theme-gallery-edit-image');
}
