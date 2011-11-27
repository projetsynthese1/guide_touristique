<?php
function gallery_image_upload_tabs ($tabs) {
	unset($tabs['type_url'], $tabs['gallery']);
    return $tabs;
}
function gallery_image_form_url($form_action_url, $type){
	$form_action_url = $form_action_url.'&gallery_image_upload=1';
	return $form_action_url;
}
function disable_gallery_flash_uploader($flash){
	return false;
}
function gallery_image_attachment_fields_to_edit($form_fields, $post){
	unset($form_fields['url'], $form_fields['align'], $form_fields['image-size']);
	$filename = basename( $post->guid );
	$attachment_id = $post->ID;
	if ( current_user_can( 'delete_post', $attachment_id ) ) {
		if ( !EMPTY_TRASH_DAYS ) {
			$delete = "<a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Delete Permanently' ) . '</a>';
		} elseif ( !MEDIA_TRASH ) {
			$delete = "<a href='#' class='del-link' onclick=\"document.getElementById('del_attachment_$attachment_id').style.display='block';return false;\">" . __( 'Delete' ) . "</a>
			 <div id='del_attachment_$attachment_id' class='del-attachment' style='display:none;'>" . sprintf( __( 'You are about to delete <strong>%s</strong>.' ), $filename ) . "
			 <a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='button'>" . __( 'Continue' ) . "</a>
			 <a href='#' class='button' onclick=\"this.parentNode.style.display='none';return false;\">" . __( 'Cancel' ) . "</a>
			 </div>";
		} else {
			$delete = "<a href='" . wp_nonce_url( "post.php?action=trash&amp;post=$attachment_id", 'trash-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Move to Trash' ) . "</a>
			<a href='" . wp_nonce_url( "post.php?action=untrash&amp;post=$attachment_id", 'untrash-attachment_' . $attachment_id ) . "' id='undo[$attachment_id]' class='undo hidden'>" . __( 'Undo' ) . "</a>";
		}
	} else {
		$delete = '';
	}
	$form_fields['buttons'] = array( 
		'tr' => "\t\t<tr><td></td><td><input type='button' class='button' onclick='themeImageInsertIntoGallery(".$post->ID.")' value='" . esc_attr__( 'Insert into Gallery' ) . "' /> $delete</td></tr>\n"
	);
	return $form_fields;
}
function gallery_image_upload_init(){
	add_filter('flash_uploader', 'disable_gallery_flash_uploader');
	add_filter('media_upload_tabs', 'gallery_image_upload_tabs');
	add_filter('attachment_fields_to_edit', 'gallery_image_attachment_fields_to_edit', 10, 2);
	add_filter('media_upload_form_url', 'gallery_image_form_url', 10, 2);
	wp_register_script('theme-gallery-image-upload', THEME_ADMIN_ASSETS_URI . '/js/gallery-image-upload.js');
	wp_enqueue_script('theme-gallery-image-upload');
}
add_action('admin_init', 'gallery_image_upload_init');
?>
