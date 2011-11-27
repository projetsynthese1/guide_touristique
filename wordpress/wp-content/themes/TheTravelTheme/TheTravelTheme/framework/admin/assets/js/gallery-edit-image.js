jQuery(document).ready( function($) {
	jQuery('form').submit(function() {
		jQuery.ajax({
			type: "POST",
			data: $(this).serializeArray(),
			success: function(msg){
				var win = window.dialogArguments || opener || parent || top;
				win.themeGalleryCompleteEditImage(jQuery('#attachment_id').val());
				win.tb_remove();
			}
		}); 
		return false;
	});
});
