var theme = {
	optionsMultidropdown : function() {
		var wrap = jQuery(".multidropdown-wrap");
		wrap.each(function() {
			var selects = jQuery(this).children('select');
			var field = jQuery(this).siblings('input:hidden');
			field.val("");
			var name = field.attr("name");
			selects.each(function(i) {
				if (jQuery(this).val()) {
					if (field.val()) {
						field.val(field.val() + ',' + jQuery(this).val());
					} else {
						field.val(jQuery(this).val());
					}
				}
				jQuery(this).attr('id', name + '_' + i);
				jQuery(this).attr('name', name + '_' + i);
				jQuery(this).unbind('change').bind('change',function() {
					if (jQuery(this).val() && selects.length == i + 1) {
						jQuery(this).clone().val("").appendTo(wrap);
					} else if (!(jQuery(this).val())
							&& !(selects.length == i + 1)) {
						jQuery(this).remove();
					}
					theme.optionsMultidropdown();
				});
			})
		})
	},
	optionSuperlink : function() {
		var wrap = jQuery(".superlink-wrap");
		wrap.each(function(){
			var field = jQuery(this).siblings('input:hidden');
			var selector = jQuery(this).siblings('select');
			var name = field.attr('name');
			var items = jQuery(this).children();
			selector.change(function(){
				items.hide();
				jQuery("#"+name+"_"+jQuery(this).val()).show();
				field.val('');
			});
			items.change(function(){
				field.val(selector.val()+'||'+jQuery(this).val());
			})
		})
	},
	uploaderInit : function(){
		jQuery('.theme-upload-button').each(function(){
		});	
	},
	themeOptionGetImage : function(attachment_id,target){
		jQuery.post(ajaxurl, {
			action:'theme-option-get-image',
			id: attachment_id, 
			cookie: encodeURIComponent(document.cookie)
		}, function(src){
			if ( src == '0' ) {
				alert( 'Could not use this image. Try a different attachment.' );
			} else {
				jQuery("#"+target).val(src);
				jQuery("#"+target+"_preview").html('<a class="thickbox" href="'+src+'?"><img src="'+src+'"/></a>');
			}
		});
	}
}
jQuery(document).ready( function($) {
	jQuery('.meta-box-item a.switch').click(function(event){
		jQuery(this).parent().siblings('.description').toggle();
		event.preventDefault();
	});
	theme.optionsMultidropdown();
	theme.uploaderInit();
	theme.optionSuperlink();
	$(".range-input-wrap :range").rangeinput();//enable range input
	$.tools.validator.addEffect("option", function(errors, event) {
		// add new ones
		$.each(errors, function(index, error) {
			var input = error.input;
			input.addClass("invalid");
			var msg = input.next('.validator-error').empty();
			$.each(error.messages, function(i, m) {
				$("<span/>").html(m).appendTo(msg);			
			});
		});
	// the effect does nothing when all inputs are valid	
	}, function(inputs)  {
		inputs.removeClass("invalid").each(function() {
			$(this).next('.validator-error').empty();
		});
	});
	$(".validator-wrap :input").validator({effect:'option'});
	//mColorPicker setting
	$.fn.mColorPicker.init.showLogo = false;
	$.fn.mColorPicker.defaults.imageFolder = theme_admin_assets_uri + "/images/mColorPicker/";
	$('.toggle-button:checkbox').each(function(){
		if(!$(this).parents().is('.shortcode_wrap')){
			if($(this).parents('.postbox').is('.closed')){
				var button = $(this);
				button.parents('.postbox').children('.hndle,.handlediv').bind('clickoutside',function(e){
					button.iphoneStyle();
				});
			}else{
				$(this).iphoneStyle();
			}
		}
	});
});
/*
 * jQuery outside events - v1.1 - 3/16/2010
 * http://benalman.com/projects/jquery-outside-events-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,c,b){$.map("click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup".split(" "),function(d){a(d)});a("focusin","focus"+b);a("focusout","blur"+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+"."+e+"-special-event";$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})(jQuery,document,"outside");
