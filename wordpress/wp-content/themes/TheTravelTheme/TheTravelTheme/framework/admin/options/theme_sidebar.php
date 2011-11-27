<?php
if (! function_exists("theme_add_sidebar_option")) {
	function theme_add_sidebar_option($value, $default) {
		if(!empty($default)){
			$sidebars = explode(',',$default);
		}else{
			$sidebars = array();
		}
		echo <<<HTML
<script type="text/javascript">
jQuery(document).ready( function($) {
	$("#add_sidebar").validator({effect:'option'}).closest('form').submit(function(e) {
		if (!e.isDefaultPrevented() && $("#add_sidebar").val()) {
			if($('#sidebars').val()){
				$('#sidebars').val($('#sidebars').val()+','+$("#add_sidebar").val());
			}else{
				$('#sidebars').val($("#add_sidebar").val());
			}
		}
	});
	$(".sidebar-item input:button").click(function(){
		$(this).closest(".sidebar-item").fadeOut("normal",function(){
  			$(this).remove();
  			$('#sidebars').val('');
			$(".sidebar-item-value").each(function(){
				if($('#sidebars').val()){
					$('#sidebars').val($('#sidebars').val()+','+$(this).val());
				}else{
					$('#sidebars').val($(this).val());
				}
			});
 		});
	});
});
</script>
<style type="text/css">
.sidebar-title {
	margin:20px 0 5px;
	font-weight:bold;
}
.sidebar-item {
	padding-left:10px;
}
.sidebar-item span {
	margin-right:10px;
}
</style>
HTML;
		echo '<input type="text" id="add_sidebar" name="add_sidebar" pattern="([a-zA-Z\x7f-\xff][ a-zA-Z0-9_\x7f-\xff]*){0,1}" data-message="'.__('Please input a valid name which starts with a letter, followed by letters, numbers, spaces, or underscores.').'" maxlength="20" /><span class="validator-error"></span>';
		if(!empty($sidebars)){
			echo '<div class="sidebar-title">'.__('Below are the Sidebars you have created','striking_admin').'</div>';
			foreach($sidebars as $sidebar){
				echo '<div class="sidebar-item"><span>'.$sidebar.'</span><input type="hidden" class="sidebar-item-value" value="'.$sidebar.'"/><input type="button" class="button" value="'.__('Delete','striking_admin').'"/></div>';
			}
		}
		echo '<input type="hidden" value="' . $default . '" name="' . $value['id'] . '" id="sidebars"/>';
	}
}
$options = array(
	array(
		"name" => __("Sidebar",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Sidebar",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Generate Sidebar",'striking_admin'),
			"desc" => __("Enter the name of sidebar you'd like to create.",'striking_admin'),
			"id" => "sidebars",
			"function" => "theme_add_sidebar_option",
			"default" => "",
			"type" => "custom"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'sidebar',
	'options' => $options
);
