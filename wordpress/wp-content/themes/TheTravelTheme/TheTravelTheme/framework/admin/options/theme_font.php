<?php
if (! function_exists("theme_cufon_get_fonts")){
	function theme_cufon_get_fonts(){
		$fonts = array();
		foreach(glob(THEME_FONT_DIR."/*.js") as $font_file){
			$file_content = file_get_contents($font_file);
			if(preg_match('/font-family":"(.*?)"/i',$file_content,$match)){
				$fonts[$match[1]] = basename($font_file);
			}
		}
		return $fonts;
	}
}
if(isset($_GET['page']) && $_GET['page']=='theme_font'){
	if (! function_exists("theme_cufon_add_script_option")){
		function theme_cufon_add_script_option(){
			wp_enqueue_script( 'cufon-yui', THEME_JS .'/cufon-yui.js');
			wp_enqueue_script( 'ZeroClipboard', THEME_ADMIN_ASSETS_URI .'/js/ZeroClipboard.js');
			$cufon_scripts = "<script type='text/javascript'>\njQuery(document).ready(function($) {\n";
			$count = 1;
			foreach(theme_cufon_get_fonts() as $font_name => $file_name){
				wp_enqueue_script( $font_name, THEME_FONT_URI .'/'.$file_name);
				$cufon_scripts .= stripslashes("Cufon('#preview-$count', { fontFamily: '$font_name' });\n");
				$count ++;
			}
			do_action('admin_print_scripts');
			$cufon_scripts .= "ZeroClipboard.setMoviePath('".THEME_ADMIN_ASSETS_URI."/js/ZeroClipboard.swf');\njQuery('.cufon_font_name').each(function(){\nvar clip = new ZeroClipboard.Client();\nclip.setText(jQuery(this).text());\nclip.glue(this,jQuery(this).parent('.font_name_wrap')[0]);\n});";
			echo $cufon_scripts."});\n</script>";
		}
		add_filter('admin_head', 'theme_cufon_add_script_option');
	}
}
if (! function_exists("theme_cufon_fonts_option")) {
	function theme_cufon_fonts_option($value, $default) {
		echo '<tr colspan="2"><td style="padding:0"><table class="widefat cufon_table" cellspacing="0"><tbody>';
		$count = 1;
		foreach(theme_cufon_get_fonts() as $font_name => $file_name){
			if(is_array($default)){
				$checked = in_array($file_name,$default)?' checked="checked"':'';
			}else{
				$checked = '';
			}
			echo '<tr><td style="width:15%"><div class="font_name_wrap" style="position: relative;"><a class="cufon_font_name" href="#" title="'.$file_name.'">'.$font_name.'</a></div></td><td style="width:10%"><input type="checkbox" name="fonts[]" class="toggle-button" value="'.$file_name.'"'.$checked.'/></td><td><span class="cufon_preview" id="preview-'.$count.'">This is a preview of the <span style="color:  #379BFF;">'.$font_name.'</span> font. Some numbers: 0123456789 &amp; so on..</span></td></tr>';
			$count ++;
		}
		echo '</tbody></table></td></tr>';
	}
}
$options = array(
	array(
		"name" => __("Font",'striking_admin'),
		"type" => "title"
	),
	array(
		"name" => __("Font General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Font family",'striking_admin'),
			"desc" => '',
			"id" => "font_family",
			"default" => '"Lucida Sans Unicode","Lucida Grande",Garuda,sans-serif',
			"options" => array(
				'"Arial Black",Gadget,sans-serif' => '"Arial Black",Gadget,sans-serif',
				'Arial,Helvetica,Garuda,sans-serif' => 'Arial,Helvetica,Garuda,sans-serif',
				'Verdana,Geneva,Kalimati,sans-serif' => 'Verdana,Geneva,Kalimati,sans-serif',
				'"Lucida Sans Unicode","Lucida Grande",Garuda,sans-serif' => '"Lucida Sans Unicode","Lucida Grande",Garuda,sans-serif',
				'Georgia,"Nimbus Roman No9 L",serif' => 'Georgia,"Nimbus Roman No9 L",serif',
				'"Palatino Linotype","Book Antiqua",Palatino,FreeSerif,serif' => '"Palatino Linotype","Book Antiqua",Palatino,FreeSerif,serif',
				'Tahoma,Geneva,Kalimati,sans-serif' => 'Tahoma,Geneva,Kalimati,sans-serif',
				'"Trebuchet MS",Helvetica,Jamrul,sans-serif' => '"Trebuchet MS",Helvetica,Jamrul,sans-serif',
				'"Times New Roman",Times,FreeSerif,serif' => '"Times New Roman",Times,FreeSerif,serif',
			),
			"type" => "select",
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Font Size Setting",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Logo Text Size",'striking_admin'),
			"desc" => "",
			"id" => "site_name",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "40",
			"type" => "range"
		),
		array(
			"name" => __("Logo Description Size",'striking_admin'),
			"desc" => "",
			"id" => "site_description",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "11",
			"type" => "range"
		),
		array(
			"name" => __("Top Level Menu Text Size",'striking_admin'),
			"desc" => "",
			"id" => "menu_top",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "17",
			"type" => "range"
		),
		array(
			"name" => __("Sub Level Menu Text Size",'striking_admin'),
			"desc" => "",
			"id" => "menu_sub",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "14",
			"type" => "range"
		),
		array(
			"name" => __("Feature Box Title Size",'striking_admin'),
			"desc" => "",
			"id" => "feature_box_title",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "42",
			"type" => "range"
		),
		array(
			"name" => __("Feature Box Category Size",'striking_admin'),
			"desc" => "",
			"id" => "feature_box_category",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "14",
			"type" => "range"
		),
		array(
			"name" => __("Feature Header Text Size",'striking_admin'),
			"desc" => "",
			"id" => "feature_header",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "42",
			"type" => "range"
		),
		array(
			"name" => __("Feature Introduce Text Size",'striking_admin'),
			"desc" => "",
			"id" => "feature_introduce",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "21",
			"type" => "range"
		),
		array(
			"name" => __("Page Text Size",'striking_admin'),
			"desc" => "",
			"id" => "page",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "12",
			"type" => "range"
		),
		array(
			"name" => sprintf(__("%s Size",'striking_admin'),'H1'),
			"desc" => "",
			"id" => "h1",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "36",
			"type" => "range"
		),
		array(
			"name" => sprintf(__("%s Size",'striking_admin'),'H2'),
			"desc" => "",
			"id" => "h2",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "30",
			"type" => "range"
		),
		array(
			"name" =>  sprintf(__("%s Size",'striking_admin'),'H3'),
			"desc" => "",
			"id" => "h3",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "24",
			"type" => "range"
		),
		array(
			"name" =>  sprintf(__("%s Size",'striking_admin'),'H4'),
			"desc" => "",
			"id" => "h4",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "18",
			"type" => "range"
		),
		array(
			"name" =>  sprintf(__("%s Size",'striking_admin'),'H5'),
			"desc" => "",
			"id" => "h5",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "14",
			"type" => "range"
		),
		array(
			"name" =>  sprintf(__("%s Size",'striking_admin'),'H6'),
			"desc" => "",
			"id" => "h6",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "12",
			"type" => "range"
		),
		array(
			"name" => __("Blog Post Title Size",'striking_admin'),
			"desc" => "",
			"id" => "entry_title",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "36",
			"type" => "range"
		),
		array(
			"name" => __("Portfolio Title Size",'striking_admin'),
			"desc" => "",
			"id" => "portfolio_title",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "24",
			"type" => "range"
		),
		array(
			"name" => __("Sidebar Widget Title",'striking_admin'),
			"desc" => "",
			"id" => "widget_title",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "24",
			"type" => "range"
		),
		array(
			"name" => __("Footer Text Title",'striking_admin'),
			"desc" => "",
			"id" => "footer_text",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "12",
			"type" => "range"
		),
		array(
			"name" => __("Footer Widget Title",'striking_admin'),
			"desc" => "",
			"id" => "footer_title",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "24",
			"type" => "range"
		),
		array(
			"name" => __("Copyright Text Size",'striking_admin'),
			"desc" => "",
			"id" => "copyright",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "11",
			"type" => "range"
		),
		array(
			"name" => __("Footer Menu Text Size",'striking_admin'),
			"desc" => "",
			"id" => "footer_menu",
			"min" => "1",
			"max" => "60",
			"step" => "1",
			"unit" => 'px',
			"default" => "12",
			"type" => "range"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => __("Cufón General",'striking_admin'),
		"type" => "start"
	),
		array(
			"name" => __("Enable Cufon",'striking_admin'),
			"desc" => "",
			"id" => "enable_cufon",
			"default" => 1,
			"type" => "toggle"
		),
		array(
			"name" => __("Cufon Code",'striking_admin'),
			"desc" => __('sample:<p><code>Cufon.replace("h1,h2,h3,h4,h5", {fontFamily : "Vegur"});</code></p><p><code>Cufon.replace("#site_name", {fontFamily : "Vegur", color: \'-linear-gradient(white, black)\'});</code></p><p>For more code tips go to official <a href="http://wiki.github.com/sorccu/cufon/styling">Cufon\'s site</a>','striking_admin'),
			"id" => "code",
			"default" => '',
			"rows" => '8',
			"type" => "textarea"
		),
	array(
		"type" => "end"
	),
	array(
		"name" => sprintf(__('Fonts located in "%s"','striking_admin'),'<code>'.str_replace( WP_CONTENT_DIR, '', THEME_FONT_DIR ).'</code>'),
		"type" => "start"
	),
		array(
			"id" => "fonts",
			"layout" => false,
			"function" => "theme_cufon_fonts_option",
			"default" => array('Segan_300.font.js'),
			"type" => "custom"
		),
	array(
		"type" => "end"
	),
);
return array(
	'auto' => true,
	'name' => 'font',
	'options' => $options
);
