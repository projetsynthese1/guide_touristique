var themeGalleryGetImage,themeGalleryCompleteEditImage,themeGalleryDeleteImage,themeGalleryImagesSetIds;
(function($){
themeGalleryGetImage = function(attachment_id){
	jQuery.post(ajaxurl, {
		action:'theme-gallery-get-image',
		id: attachment_id, 
		cookie: encodeURIComponent(document.cookie)
	}, function(str){
		if ( str == '0' ) {
			alert( 'Could not insert into gallery. Try a different attachment.' );
		} else {
			jQuery("#imagesSortable").append(str);
			themeGalleryImagesSetIds();
		}
	});
	//var field = $('input[value=_thumbnail_id]', '#list-table');
	//if ( field.size() > 0 ) {
	//	$('#meta\\[' + field.attr('id').match(/[0-9]+/) + '\\]\\[value\\]').text(id);
	//}
};
themeGalleryCompleteEditImage = function(attachment_id){
	jQuery.post(ajaxurl, {
		action:'theme-gallery-get-image',
		id: attachment_id, 
		cookie: encodeURIComponent(document.cookie)
	}, function(str){
		if ( str == '0' ) {
			alert( 'Could not insert into gallery. Try a different attachment.' );
		} else {
			jQuery("#image-"+ attachment_id).replaceWith(str);
			themeGalleryImagesSetIds();
		}
	});
};
themeGalleryDeleteImage = function(attachment_id){
	jQuery("#image-"+ attachment_id).remove();
	themeGalleryImagesSetIds();
};
themeGalleryImagesSetIds = function(){
	var ids = jQuery('#imagesSortable').sortable('toArray').toString();
	jQuery('#gallery_image_ids').val(ids);
}
})(jQuery);
jQuery(document).ready( function($) {
	jQuery("#imagesSortable").sortable({
		handle: '.handle',
		opacity: 0.6,
		placeholder: 'sortItem-placeholder',
		stop: function(event, ui) {
			themeGalleryImagesSetIds();
		}
	});
	jQuery('.edit-item',"#imagesSortable").live('click', function(){
		var id = jQuery(this).parents('.imageItemWrap').attr('id').slice(6);//remove "image-"
		tb_show('Edit Image',"media.php?action=edit&attachment_id="+id+"&gallery_edit_image=true&TB_iframe=true");
	})
	jQuery('.delete-item',"#imagesSortable").live('click', function(){
		var id = jQuery(this).parents('.imageItemWrap').attr('id').slice(6);//remove "image-"
		themeGalleryDeleteImage(id);
	})
});
