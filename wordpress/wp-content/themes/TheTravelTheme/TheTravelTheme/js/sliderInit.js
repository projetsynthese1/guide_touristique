jQuery(document).ready(function() {
	jQuery('#slidershow_wrap').preloader({
		gradualDelay:false,
		imgSelector:'.feature_box_image img',
		afterShowAll:function(){
			var slider = jQuery('#sldershow_pagers');
			slider.tabs("#slidershow_wrap .slide_pane", {

				// enable "cross-fading" effect
				effect: 'fade',
				fadeOutSpeed: slideShow['animSpeed'],
				fadeInSpeed: slideShow['animSpeed'],
				// start from the beginning after the last tab
				rotate: true

			// use the slideshow plugin. It accepts its own configuration
			}).slideshow({
				clickable: false,
				autoplay: slideShow['autoplay'],
				interval: slideShow['pauseTime']
			});
			
			jQuery('#slidershow_loading').animate({opacity:0}, 1000,function(){jQuery(this).remove();});
			var $video_click = false;
			jQuery('.feature_box_video',this).mousedown(function(){
				slider.data('slideshow').stop();
				$video_click = true;
			});
			if(slideShow['pauseOnHover']){
				jQuery(this).hover(function(){
					slider.data('slideshow').stop();
				},function(){
					if($video_click !== true){
						slider.data('slideshow').play();
					}
				})
			}
		},
		beforeShowAll:function(){
			jQuery('<div id="slidershow_loading"><div class="inner"></div></div>').insertBefore(this);
		}
	})
});