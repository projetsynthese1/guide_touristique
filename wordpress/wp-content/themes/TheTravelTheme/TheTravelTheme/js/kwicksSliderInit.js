jQuery(document).ready(function() {
	var autoplay = true;
	var current = 0;
	var kwicks_items = jQuery('#kwicks li');
    if (kwicks_items.size()!=0) {
	jQuery('#kwicks').preloader({
		delay:500,
		beforeShowAll:function(){
			jQuery(this).kwicks({
				max: slideShow['max'],
				duration: slideShow['duration'],
				easing: slideShow['easing'],
				spacing:0
			});
			jQuery(".kwick_detail",this).width(slideShow['max']);
			if(slideShow['title']){
				jQuery(".kwick_title,",this).fadeTo("fast", slideShow['title_opacity']);
			}
			
			jQuery("li",this).append('<div class="kwick_shadow"> </div><div class="kwick_frame_top"></div><div class="kwick_frame"></div>').each(function(i){
				jQuery(this).css('z-index',100+i);
			}).hover(function(e,auto) {
				if( auto!= true ){
					if(slideShow['autoplay']){
						kwicks_items.filter('.auto').removeClass('auto').trigger('mouseout');
						autoplay = false;
					}
				}
				if(slideShow['title']){
					jQuery(".kwick_title").stop().fadeTo(slideShow['title_speed'], 0);
				}
				if(slideShow['detail']){
					jQuery(".kwick_detail",this).stop().fadeTo(slideShow['detail_speed'], slideShow['detail_opacity']);
				}
			},function(){
				if(slideShow['autoplay']){
					autoplay = true;
				}
				if(slideShow['title']){
					jQuery(".kwick_title").stop().fadeTo(slideShow['title_speed'],slideShow['title_opacity']);
				}
				if(slideShow['detail']){
					jQuery(".kwick_detail",this).stop().fadeTo(slideShow['detail_speed'], 0);
				}
			}).each(function(){
				if(jQuery(this).children('a').attr('href') != '#'){
					jQuery(this).css('cursor','pointer').click(function(){
						location.href = jQuery(this).children('a').attr('href');
					});
				}
			});

			
			
			jQuery("li:last-child",this).append('<div class="kwick_last_frame"></div>');
		},
		beforeShow:function(){
			jQuery(this).closest('li').addClass('preloading');
		},
		afterShow:function(){
			jQuery(this).closest('li').removeClass('preloading');
		},
		afterShowAll:function(){
			if(slideShow['autoplay']){
				setInterval(function(){
					//console.info(autoplay);
					if(autoplay){
						kwicks_items.eq(current-1).removeClass('auto').trigger('mouseout');
						kwicks_items.eq(current).addClass('auto').trigger('mouseover',[true]);
						current++;
						if(current >= slideShow['number']){
							current = 0;
						}
					}
				},slideShow['pauseTime']);
			}
		}
	});
    };
});
