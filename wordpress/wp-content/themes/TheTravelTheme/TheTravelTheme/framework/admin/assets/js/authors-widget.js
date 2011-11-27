jQuery(document).ready( function($) {
	jQuery('.author_count').live('change',function(){
		var wrap = jQuery(this).closest('p').siblings('.authors_wrap');
		wrap.children('div').hide();
		var count = jQuery(this).val();
		for(var i = 1; i <= count; i++){
			wrap.find('.author_'+i).show();
		}
	});
});
