<?php 
if (! function_exists("theme_add_custom_option")) {
	function theme_add_custom_option($value, $default) {
		if(!empty($default)){
			$customs = explode(',',$default);
		}else{
			$customs = array();
		}
		echo <<<HTML
<script type="text/javascript">
jQuery(document).ready( function($) {
	$("#add_custom").validator({effect:'option'}).closest('form').submit(function(e) {
		if (!e.isDefaultPrevented() && $("#add_custom").val()) {
			if($('#customs').val()){
				$('#customs').val($('#customs').val()+','+$("#add_custom").val());
			}else{
				$('#customs').val($("#add_custom").val());
			}
		}
	});
	$(".custom-item input:button").click(function(){
		$(this).closest(".custom-item").fadeOut("normal",function(){
  			$(this).remove();
  			$('#customs').val('');
			$(".custom-item-value").each(function(){
				if($('#customs').val()){
					$('#customs').val($('#customs').val()+','+$(this).val());
				}else{
					$('#customs').val($(this).val());
				}
			});
 		});
	});
});
</script>
<style type="text/css">
.custom-title {
	margin:20px 0 5px;
	font-weight:bold;
}
.custom-item {
	padding-left:10px;
}
.custom-item span {
	margin-right:10px;
}
</style>
HTML;
		echo '<input type="text" id="add_custom" name="add_custom" pattern="([a-zA-Z\x7f-\xff][ a-zA-Z0-9_\x7f-\xff]*){0,1}" data-message="'.__('Please input a valid name which starts with a letter, followed by letters, numbers, spaces, or underscores.').'" maxlength="20" /><span class="validator-error"></span>';
		if(!empty($customs)){
			echo '<div class="custom-title">'.__('Below are the Sizes you have created','striking_admin').'</div>';
			foreach($customs as $custom){
				echo '<div class="custom-item"><span>'.$custom.'</span><input type="hidden" class="custom-item-value" value="'.$custom.'"/><input type="button" class="button" value="'.__('Delete','striking_admin').'"/></div>';
			}
		}
		echo '<input type="hidden" value="' . $default . '" name="' . $value['id'] . '" id="customs"/>';
	}
}
$options = array(
	array(
		"name" => __("Image Sizes",'striking_admin'),
		"desc" => __("The options listed below determine the dimensions in pixels to use in the shortcode of image.",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Custom Sizes",'striking_admin'),
		"type" => "start"
	),
	array(
			"name" => __("Custom Sizes",'striking_admin'),
			"desc" => __("Enter the name of custom you'd like to create.",'striking_admin'),
			"id" => "customs",
			"function" => "theme_add_custom_option",
			"default" => "",
			"type" => "custom"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Small",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "small_width",
			"default" => 220,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "small_height",
			"default" => 150,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Medium",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "medium_width",
			"default" => 292,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "medium_height",
			"default" => 190,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Large",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => "large_width",
			"default" => 459,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => "large_height",
			"default" => 240,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		),
	array(
		"type" => "end"
	),
);
if($customs =  theme_get_option('image','customs')){
	$custom_array = explode(',',$customs);
	foreach ($custom_array as $custom){
		$options[] = array(
			"name" => $custom." Sizes",
			"type" => "start"
		);
		$options[] = array(
			"name" => __("Width",'striking_admin'),
			"desc" => "",
			"id" => $custom."_width",
			"default" => 150,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		);
		$options[] = array(
			"name" => __("Height",'striking_admin'),
			"desc" => "",
			"id" => $custom."_height",
			"default" => 150,
			"min" => 0,
			"max" => 960,
			"unit" => 'px',
			"type" => "range"
		);
		$options[] = array(
			"type" => "end"
		);
	}
}
return array(
	'auto' => true,
	'name' => 'image',
	'options' => $options
);
