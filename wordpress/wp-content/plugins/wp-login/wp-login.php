<?php
/**
 Plugin Name: WP Login
 Plugin URI: http://websitez.com/
 Description: This is plugin will create a custom login, registration, and lost password page automatically inserted into your current activated theme. This plugin will override the 'wp-login.php' file. This is the only login plugin that utilizes the WordPress login code, so you know it works properly.

 Version: 1.0
 Author: Eric Stolz
 Author URI: http://websitez.com
*/
global $wzcl_default_options;
$wzcl_default_options = array(
	"general"=>array(
		"theme"=>"default"
	)
);
global $wzcl_options;
$wzcl_options = wzcl_get_options();

global $wzcl_require_authentication;
$wzcl_require_authentication = false;

function wzcl_get_options(){
	global $wzcl_default_options;
	$user_options = get_option('wzcl_custom_login_options');
	
	if(is_array($user_options)):
		$options = array_merge($wzcl_default_options, $user_options);
	else:
		$options = $wzcl_default_options;
	endif;
	
	if(strlen($options['general']['theme']) == 0):
		$options['general']['theme'] = "default";
	endif;
	
	return $options;
}

add_action('template_redirect', 'wz_is_mobile', 10);

function wz_is_mobile(){
	global $wp_query;
	if(is_object($wp_query)):
		//var_dump($wp_query->query_vars['device_details']);
	endif;
}

// Install plugin
if(function_exists('register_activation_hook')):
	register_activation_hook( __FILE__, 'wzcl_install' );
endif;

if(!function_exists('wzcl_install')):
	function wzcl_install(){
		//Install the options in the options table
	}
endif;

if(!function_exists('wzcl_is_authorized')):
	function wzcl_is_authorized(){
		global $wzcl_options, $wzcl_require_authentication;
		if($wzcl_options['authorized']=="true"||!$wzcl_require_authentication):
			return true;
		else:
			return false;
		endif;
	}
endif;

if(!function_exists('wzcl_check_configuration')):
	function wzcl_check_configuration(){
		global $wzcl_options;
		if(!wzcl_is_authorized()):
			wzcl_show_admin_message("You must enter a license key before the <a href='admin.php?page=wp_login'>WP Login</a> plugin will work properly.", true);
		elseif($wzcl_options['general']['login_type']=="new" && $wzcl_options['general']['login_page_id'] == ""):
			wzcl_show_admin_message("You must set the login page for the <a href='admin.php?page=wp_login'>WP Login</a> plugin before it will work properly.", true);
		endif;
	}
endif;

if(!function_exists('wzcl_show_admin_message')):
	function wzcl_show_admin_message($message, $error_message=false){
		if ($error_message):
			echo '<div id="message" class="error">';
		else:
			echo '<div id="message" class="updated fade">';
		endif;
	
		echo "<p><strong>$message</strong></p></div>";
	}
endif;

if(!function_exists('wzcl_login_class')):
	function wzcl_login_class($classes=''){
		$classes[] = 'login';
		return $classes;
	}
endif;

if(!function_exists('wzcl_login_head')):
	function wzcl_login_head(){
		global $wzcl_options;
		do_action( 'login_enqueue_scripts' );
		do_action( 'login_head' );
		echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url(__FILE__) . 'themes/'.$wzcl_options['general']['theme'].'/style.php" />';
	}
endif;

if(!function_exists('wzcl_login')):
	function wzcl_login($template){
		global $wp_query, $wzcl_options;
		if(is_page()):
			$post_id = $wp_query->post->ID;
			if($post_id==$wzcl_options['general']['login_page_id']):
				$template = dirname(__FILE__)."/wp-login_modified.php";
				add_action('wp_head', 'wzcl_login_head');
				add_filter('body_class','wzcl_login_class');
			endif;
		endif;
		
		return $template;
	}
endif;

if(!function_exists('wzcl_add_stylesheet')):
	function wzcl_add_stylesheet(){
		global $wzcl_options;
		echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url(__FILE__) . 'themes/'.$wzcl_options['general']['theme'].'/style.php" />';
	}
endif;

if(!function_exists('wzcl_check_login')):
	function wzcl_check_login(){
		global $wzcl_options;
		if(wzcl_is_authorized()):
			if($_SERVER['SCRIPT_NAME']=="/wp-login.php"):
				if($wzcl_options['general']['login_type'] == "new"):
					if(array_key_exists('general', $wzcl_options) && array_key_exists('login_page_id', $wzcl_options['general'])):
						if(strlen($_SERVER['QUERY_STRING'])>0):
							$diff = (stripos(get_permalink( $wzcl_options['general']['login_page_id'] ),"?")===false) ? '?' : '&';
							header("Location: ".get_permalink( $wzcl_options['general']['login_page_id'] ).$diff.$_SERVER['QUERY_STRING']);
						else:
							header("Location: ".get_permalink( $wzcl_options['general']['login_page_id'] ));
						endif;
					endif;
				elseif($wzcl_options['general']['login_type'] == "current"):
					add_action('login_head', 'wzcl_add_stylesheet', 10, 0);
				endif;
			elseif($wzcl_options['general']['login_type'] == "new"):
				add_action('page_template', 'wzcl_login');
			endif;
		endif;
	}
endif;

if(!function_exists('wzcl_admin_menu')):
	function wzcl_admin_menu(){
		add_menu_page( __( 'WP Login' ), __( '<span style="font-size:12px;">'.__("WP Login").'</span>', 'Login' ), 8, 'wp_login', 'wzcl_wp_login_page');
	}
endif;

if(!function_exists('wzcl_get_themes')):
	function wzcl_get_themes(){
		$template_files = array();
		$template_directory = dirname(__FILE__)."/themes/";
		$template_dir = @ dir("$template_directory");
		if ( $template_dir ) {
			while ( ($file = $template_dir->read()) !== false ) {
				if($file != "." && $file != ".."):
					$template_files[] = $file;
				endif;
			}
			@ $template_dir->close();
		}
		return $template_files;
	}
endif;

if(!function_exists('wzcl_get_files')):
	function wzcl_get_files($theme="default"){
		global $wzcl_options;
		$files = array();
		$dir = dirname(__FILE__)."/themes/".$wzcl_options['general']['theme']."/";
		if (is_dir($dir)):
	    if ($dh = opendir($dir)):
        while (($file = readdir($dh)) !== false):
        	if(filetype($dir . $file) == "file"):
          	$files[] = $file;
          endif;
        endwhile;
        closedir($dh);
	    endif;
		endif;
		
		return $files;
	}
endif;

if(!function_exists('wzcl_admin_head_scripts')):
	function wzcl_admin_head_scripts(){
		wp_enqueue_script('jpicker', plugin_dir_url(__FILE__)."jpicker-1.1.5.min.js", array('jquery','jquery-ui-core'), '0.1');
	}
endif;

if(!function_exists('wzcl_wp_login_page')):
	function wzcl_wp_login_page(){
		global $wpdb, $table_prefix, $wzcl_options;
		$themes = wzcl_get_themes();
		$pages = get_pages( array('post_status'=>'publish', 'sort_column'=>'post_title') );
		?>
		<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('.Multiple').jPicker(
				{
					window: {
						position: {
							x: 'screenCenter',
							y: '100'
						}
					},
					images: {
						clientPath: '<?php bloginfo('url');?>/wp-content/plugins/a-wp-mobile-detector/admin/images/'
					}
				},
				function(color,context){
					//update_color(this.id,color.val('all').hex);
					save();
				}
			);
		});
		</script>
		<style>
		input[type=text]{
			width: 200px;
		}
		div.field_name{
			float: left;
			width: 200px;
			padding-bottom: 10px;
			font-weight: bold;
		}
		div.field_value{
			float: left;
			width: 300px;
			padding-bottom: 10px;
		}
		div.field_value_short{
			float: left;
			width: 30px;
			padding-bottom: 10px;
		}
		div.field_description{
			float: left;
			padding-bottom: 10px;
			width: 400px;
		}
		div.field_demo{
			padding-left: 50px;
			width: 200px;
			float: left;
		}
		div.field_description_demo{
			float: left;
			width: 600px;
			padding-bottom: 10px;
		}
		div.clearer{
			clear: both;
			height: 1px;
			background-color: #ccc;
			margin: 10px 0px;
		}
		div.clear{
			clear: both;
			margin-top: 30px;
		}
		div.clearerDiv{
			clear: both;
			height: 3px;
			background-color: #333;
			margin: 10px 0px;
		}
		div.clearerBox{
			margin-top: 30px;
		}
		.btn {
			text-decoration: none;
		  cursor: pointer;
		  display: inline-block;
		  background-color: #e6e6e6;
		  background-repeat: no-repeat;
		  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), color-stop(0.25, #ffffff), to(#e6e6e6));
		  background-image: -webkit-linear-gradient(#ffffff, #ffffff 0.25, #e6e6e6);
		  background-image: -moz-linear-gradient(#ffffff, #ffffff 0.25, #e6e6e6);
		  background-image: -ms-linear-gradient(#ffffff, #ffffff 0.25, #e6e6e6);
		  background-image: -o-linear-gradient(#ffffff, #ffffff 0.25, #e6e6e6);
		  background-image: linear-gradient(#ffffff, #ffffff 0.25, #e6e6e6);
		  padding: 4px 14px;
		  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
		  color: #333;
		  font-size: 13px;
		  line-height: 18px;
		  border: 1px solid #ccc;
		  border-bottom-color: #bbb;
		  -webkit-border-radius: 4px;
		  -moz-border-radius: 4px;
		  border-radius: 4px;
		  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
		  -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
		  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
		  -webkit-transition: 0.1s linear all;
		  -moz-transition: 0.1s linear all;
		  transition: 0.1s linear all;
		}
		.btn:hover {
		  background-position: 0 -15px;
		  color: #333;
		  text-decoration: none;
		}
		.btn.primary, .btn.danger {
		  color: #fff;
		}
		.btn.primary:hover, .btn.danger:hover {
		  color: #fff;
		}
		.btn.primary {
		  background-color: #0064cd;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#049cdb), to(#0064cd));
		  background-image: -moz-linear-gradient(#049cdb, #0064cd);
		  background-image: -ms-linear-gradient(#049cdb, #0064cd);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #049cdb), color-stop(100%, #0064cd));
		  background-image: -webkit-linear-gradient(#049cdb, #0064cd);
		  background-image: -o-linear-gradient(#049cdb, #0064cd);
		  background-image: linear-gradient(#049cdb, #0064cd);
		  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		  border-color: #0064cd #0064cd #003f81;
		  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		}
		.alert-message {
		  background-color: rgba(0, 0, 0, 0.15);
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(transparent), to(rgba(0, 0, 0, 0.15)));
		  background-image: -moz-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
		  background-image: -ms-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, transparent), color-stop(100%, rgba(0, 0, 0, 0.15)));
		  background-image: -webkit-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
		  background-image: -o-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
		  background-image: linear-gradient(transparent, rgba(0, 0, 0, 0.15));
		  filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#15000000')";
		  background-color: #e6e6e6;
		  margin-bottom: 18px;
		  padding: 8px 15px;
		  color: #fff;
		  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
		  border-bottom: 1px solid rgba(0, 0, 0, 0.3);
		  -webkit-border-radius: 4px;
		  -moz-border-radius: 4px;
		  border-radius: 4px;
		}
		.alert-message p {
		  color: #fff;
		  margin-bottom: 0;
		}
		.alert-message p + p {
		  margin-top: 5px;
		}
		.alert-message.error {
		  background-color: #d83a2e;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#e4776f), to(#d83a2e));
		  background-image: -moz-linear-gradient(#e4776f, #d83a2e);
		  background-image: -ms-linear-gradient(#e4776f, #d83a2e);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #e4776f), color-stop(100%, #d83a2e));
		  background-image: -webkit-linear-gradient(#e4776f, #d83a2e);
		  background-image: -o-linear-gradient(#e4776f, #d83a2e);
		  background-image: linear-gradient(#e4776f, #d83a2e);
		  border-bottom-color: #b32b21;
		}
		.alert-message.warning {
		  background-color: #ffd040;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#ffe38d), to(#ffd040));
		  background-image: -moz-linear-gradient(#ffe38d, #ffd040);
		  background-image: -ms-linear-gradient(#ffe38d, #ffd040);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffe38d), color-stop(100%, #ffd040));
		  background-image: -webkit-linear-gradient(#ffe38d, #ffd040);
		  background-image: -o-linear-gradient(#ffe38d, #ffd040);
		  background-image: linear-gradient(#ffe38d, #ffd040);
		  border-bottom-color: #ffc40d;
		}
		.alert-message.success {
		  background-color: #62bc62;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#97d397), to(#62bc62));
		  background-image: -moz-linear-gradient(#97d397, #62bc62);
		  background-image: -ms-linear-gradient(#97d397, #62bc62);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #97d397), color-stop(100%, #62bc62));
		  background-image: -webkit-linear-gradient(#97d397, #62bc62);
		  background-image: -o-linear-gradient(#97d397, #62bc62);
		  background-image: linear-gradient(#97d397, #62bc62);
		  border-bottom-color: #46a546;
		}
		.alert-message.info {
		  background-color: #04aef4;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#62cffc), to(#04aef4));
		  background-image: -moz-linear-gradient(#62cffc, #04aef4);
		  background-image: -ms-linear-gradient(#62cffc, #04aef4);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #62cffc), color-stop(100%, #04aef4));
		  background-image: -webkit-linear-gradient(#62cffc, #04aef4);
		  background-image: -o-linear-gradient(#62cffc, #04aef4);
		  background-image: linear-gradient(#62cffc, #04aef4);
		  border-bottom-color: #049cdb;
		}
		.alert-message .close {
		  float: right;
		  margin-top: -2px;
		  color: #000;
		  font-size: 20px;
		  font-weight: bold;
		  text-shadow: 0 1px 0 #ffffff;
		  filter: alpha(opacity=20);
		  -khtml-opacity: 0.2;
		  -moz-opacity: 0.2;
		  opacity: 0.2;
		}
		.alert-message .close:hover {
		  text-decoration: none;
		  filter: alpha(opacity=40);
		  -khtml-opacity: 0.4;
		  -moz-opacity: 0.4;
		  opacity: 0.4;
		}
		.tabs, .pills {
		  margin: 0 0 20px;
		  padding: 0;
		  zoom: 1;
		}
		.tabs:before,
		.pills:before,
		.tabs:after,
		.pills:after {
		  display: table;
		  content: "";
		}
		.tabs:after, .pills:after {
		  clear: both;
		}
		.tabs li, .pills li {
		  display: inline;
		}
		.tabs li a, .pills li a {
		  float: left;
		  width: auto;
		}
		.tabs {
		  width: 100%;
		  border-bottom: 1px solid #bfbfbf;
		}
		.tabs li a {
		  margin-bottom: -1px;
		  margin-right: 2px;
		  padding: 0 15px;
		  line-height: 35px;
		  -webkit-border-radius: 3px 3px 0 0;
		  -moz-border-radius: 3px 3px 0 0;
		  border-radius: 3px 3px 0 0;
		}
		.tabs li a:hover {
		  background-color: #e6e6e6;
		  border-bottom: 1px solid #bfbfbf;
		}
		.tabs li.active a {
		  background-color: #fff;
		  padding: 0 14px;
		  border: 1px solid #ccc;
		  border-bottom: 0;
		  color: #808080;
		}
		.pills li a {
			text-decoration: none;
		  margin: 5px 3px 5px 0;
		  padding: 0 15px;
		  text-shadow: 0 1px 1px #fff;
		  line-height: 30px;
		  -webkit-border-radius: 15px;
		  -moz-border-radius: 15px;
		  border-radius: 15px;
		}
		.pills li a:hover {
		  background: #0050a3;
		  color: #fff;
		  text-decoration: none;
		  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.25);
		}
		.pills li.active a {
		  background: #0069d6;
		  color: #fff;
		  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.25);
		}
		.name{
			width: 160px;
			float: left;
		}
		.intro{
			padding-top: 10px;
			float: left;
			font-style: italic;
		}
		.login_link{
			float: left;
			margin-left: 450px;
			margin-top: 10px;
		}
		#editor .content{
			float: left;
			width: 550px;
		}
		#editor .action{
			float: left;
			width: 200px;
		}
		#editor .action ul li.active a{
			text-decoration: none;
			color: #000;
			font-style: italic;
		}
		.footer{
			margin-top: 50px;
			text-align: center;
			font-weight: bold;
		}
		</style>
		<div class="wrap">
			<?php
			if(isset($_POST['data'])):
				if(isset($_FILES['logo'])):
					$overrides = array( 'test_form' => false);
					$file = wp_handle_upload($_FILES['logo'], $overrides);
					if(array_key_exists('url', $file)):
						$_POST['data']['images']['logo'] = $file['url'];
					endif;
				endif;
				$c = get_option('wzcl_custom_login_options');
				if(!wzcl_is_authorized()):
					$license_key = $_POST['data']['license_key'];
					$request = wzcl_remote_request("http://websitez.com","");
					$c['authorized'] = "true";
				endif;

				if(is_array($c)):
					$d = array_merge($c,$_POST['data']);
				else:
					$d = $_POST['data'];
				endif;
				$u = update_option('wzcl_custom_login_options', $d);
				if($u):
					echo "<div class='alert-message success'>Your settings were successfully saved.</div>";
					//Now update options
					$wzcl_options = wzcl_get_options();
				else:
					echo "<div class='alert-message error'><p>Your settings could not be saved. Please try again.</p></div>";
				endif;
			elseif(isset($_POST['newcontent'])):
				$newcontent = stripslashes($_POST['newcontent']);
				$dir = dirname(__FILE__)."/themes/".$wzcl_options['general']['theme']."/";
				$file = $dir.$_GET['file'];
				if (is_writeable($file)):
					//is_writable() not always reliable, check return value. see comments @ http://uk.php.net/is_writable
					$f = fopen($file, 'w+');
					if ($f !== FALSE):
						fwrite($f, $newcontent);
						fclose($f);
						echo "<div class='alert-message success'>Your changes were successfully saved.</div>";
					else:
						echo "<div class='alert-message error'><p>Your changes could not be saved. Please try again.</p></div>";
					endif;
				endif;
			endif;
			?>
			<div class="name"><h1>WP Login</h1></div>
			<div class="intro"><p>Configure the look and feel of your login form. A plugin by <a href="http://websitez.com/wp-login/?src=wp-login" target="_blank">Websitez.com</a>.</p></div>
			<div class="login_link">
				<a href="/wp-login.php" target="_blank" class="btn">View Your Login Page</a>
			</div>
			<div style="clear: both;"></div>
			<?php if(!wzcl_is_authorized()): ?>
				<form action="admin.php?page=wp_login" method="POST">
					<div class="field_name">
						Enter License Key:
					</div>
					<div class="field_value">
						<input type="text" name="data[license_key]" />
					</div>
					<div class="field_description_demo">
						To authorize the plugin, please enter the license key you received upon purchase.
					</div>
					<div class="clearer"></div>
					<div class="buttons">
						<input type="submit" value="Save Settings" class="btn primary"/>
					</div>
				</form>
			<?php elseif(wzcl_is_authorized()): ?>
			<ul class="pills">
				<li<?php if(!isset($_GET['act'])) echo ' class="active"'; ?>><a href="" onClick="jQuery('.pills').find('li').removeClass('active'); jQuery('#settings').show('slow');jQuery('#editor').hide('slow');jQuery('#default_editor').hide('slow');jQuery(this).parent().addClass('active'); return false;">Settings</a></li>
				<li<?php if($_GET['act']=="editor") echo ' class="active"'; ?>><a href="" onClick="jQuery('.pills').find('li').removeClass('active'); jQuery('#settings').hide('slow');jQuery('#default_editor').hide('slow');jQuery('#editor').show('slow');jQuery(this).parent().addClass('active'); return false;">File Editor</a></li>
				<li<?php if($_GET['act']=="default_editor") echo ' class="active"'; ?>><a href="" onClick="jQuery('.pills').find('li').removeClass('active'); jQuery('#settings').hide('slow');jQuery('#editor').hide('slow');jQuery('#default_editor').show('slow');jQuery(this).parent().addClass('active'); return false;">Style Editor</a></li>
			</ul>
			<div id="settings"<?php if(isset($_GET['act'])) echo ' style="display: none;"'; ?>>
				<form action="admin.php?page=wp_login" method="POST">
					<?php if(!wzcl_is_authorized()): ?>
					<div class="field_name">
						Enter License Key:
					</div>
					<div class="field_value">
						<input type="text" name="data[license_key]" />
					</div>
					<div class="field_description_demo">
						To authorize the plugin, please enter the license key you received upon purchase.
					</div>
					<div class="clearer"></div>
					<?php endif; ?>
					<div class="field_name">
						Set Login Theme:
					</div>
					<div class="field_value">
						<select name="data[general][theme]">
						<option value="">Please select one...</option>
						<?php foreach($themes as $theme): ?>
							<option value="<?php echo $theme; ?>"<?php if($wzcl_options['general']['theme']==$theme) echo " selected='selected'"; ?>><?php echo $theme; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<div class="field_description_demo">
						The theme selected will be shown to users.<br/>
						Theme files are located here: <?php echo plugin_dir_url(__FILE__); ?>themes/<?php echo $wzcl_options['general']['theme']; ?>/
					</div>
					<div class="clearer"></div>
					<div class="field_name">
						Set Login Type:
					</div>
					<div class="field_value_short">
						<p><input type="radio" name="data[general][login_type]" value="current" <?php if($wzcl_options['general']['login_type'] == "current") echo " checked"; ?>/></p>
						<p><input type="radio" name="data[general][login_type]" value="new" <?php if($wzcl_options['general']['login_type'] == "new") echo " checked"; ?>/></p>
					</div>
					<div class="field_description_demo">
						<p>Would you like to use the default WordPress page and have the ability to change the logo and colors?</p>
						<p>Would you like to place the WordPress login page inside of your current activated theme <strong><?php echo get_option('current_theme'); ?></strong>?</p>
					</div>
					<div class="clearer"></div>

					<div id="login-page"<?php if($wzcl_options['general']['login_type']!="new") echo ' style="display: none;"'; ?>>
						<div class="field_name">
							Set Login Page:
						</div>
						<div class="field_value">
							<select name="data[general][login_page_id]">
							<option value="">Please select one...</option>
							<?php foreach($pages as $page): ?>
								<option value="<?php echo $page->ID; ?>"<?php if($wzcl_options['general']['login_page_id']==$page->ID) echo " selected='selected'"; ?>><?php echo $page->post_title; ?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="field_description_demo">
							The page selected will be used to display the login form. It is recommended that you create a new page called "Login" and select this page in the drop down.
						</div>
						<div class="clearer"></div>
					</div>
					<div class="buttons">
						<input type="submit" value="Save Settings" class="btn primary"/>
					</div>
				</form>
			</div>
			<div id="editor"<?php if($_GET['act']!="editor") echo ' style="display: none;"'; ?>>
				<?php
				$files = wzcl_get_files($wzcl_options['general']['theme']);

				if(isset($_GET['file'])):
					$target = $_GET['file'];
				else:
					$target = $files[0];
				endif;
				
				$editable = false;
				$dir = dirname(__FILE__)."/themes/".$wzcl_options['general']['theme']."/";
				$file = $dir.$target;
				if(filesize($file) > 0):
					$f = fopen($file, 'r');
					$content = esc_textarea(fread($f, filesize($file)));
				endif;
				
				if ( is_writable( $file ) ) :
					$editable = true;
				endif;
				?>
				<form action="" method="POST">
				<div class="content">
					<textarea cols="70" rows="25" name="newcontent" id="newcontent" tabindex="1"><?php echo $content ?></textarea>
				</div>
				<div class="action">
					<p><strong><span style="color: #999;">Files from the</span> <?php echo $wzcl_options['general']['theme']; ?> <span style="color: #999;">theme:</span></strong></p>
					<?php if(count($files) > 0): ?>
						<ul>
						<?php foreach($files as $file): ?>
							<li<?php if($target==$file) echo ' class="active"'; ?>><a href="admin.php?page=wp_login&file=<?php echo $file; ?>&act=editor"><?php echo $file; ?></a></li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<p><input type="submit" class="btn<?php if($editable) echo " primary"; ?>" value="Save Changes"<?php if(!$editable) echo " disabled"; ?>></p>
					<?php if(!$editable): ?>
						<p><em><?php _e('You need to make this file writable before you can save your changes. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.'); ?></em></p>
					<?php endif; ?>
				</div>
				</form>
			</div><!-- #editor -->
			<div id="default_editor"<?php if($_GET['act']!="default_editor") echo ' style="display: none;"'; ?>>
				<form action="admin.php?page=wp_login&act=default_editor" method="POST" <?php if(strlen($wzcl_options['images']['t']) == 0) echo ' enctype="multipart/form-data"'; ?> id="default_editor_form">
					<div class="field_name">
						Disable all styles on this page?
					</div>
					<div class="field_value">
						<select name="data[style][disable]">
							<option value="">Please select one…</option>
							<option value="No"<?php if($wzcl_options['style']['disable']=="No") echo ' selected'; ?>>No</option>
							<option value="Yes"<?php if($wzcl_options['style']['disable']=="Yes") echo ' selected'; ?>>Yes</option>
						</select>
					</div>
					<div class="field_description_demo">
						This allows you to disable all custom styles if you would like to use the default WordPress styles.
					</div>
					<div class="clearer"></div>
					<div<?php if($wzcl_options['general']['login_type']=="new") echo ' style="display: none;"'; ?>>
						<div class="field_name">
							Set Image:
						</div>
						<div class="field_value">
							<?php if(strlen($wzcl_options['images']['t']) > 0): ?>
								An image has been uploaded. <a href="" onClick="jQuery('#images_t').val(''); jQuery('#default_editor_form').submit(); return false;">Delete image</a>?
								<input id="images_t" type="hidden" name="data[images][logo]" value="<?php echo $wzcl_options['images']['logo']; ?>" />
							<?php else: ?>
								<input type="file" name="logo" value="" />
							<?php endif; ?>
						</div>
						<div class="field_description_demo">
							<?php if(strlen($wzcl_options['images']['logo']) > 0): ?>
								<img src="<?php echo $wzcl_options['images']['logo']; ?>" />
							<?php else: ?>
								Here you can upload an image to change the default image for the login form.
							<?php endif; ?>
						</div>
						<div class="clearer"></div>
					</div>
					<div<?php if($wzcl_options['general']['login_type']=="new") echo ' style="display: none;"'; ?>>
						<div class="field_name">
							Set Background Color:
						</div>
						<div class="field_value">
							<input type="text" name="data[colors][background]" value="<?php echo $wzcl_options['colors']['background']; ?>" class="Multiple" />
						</div>
						<div class="field_description">
							Here you can select the background color for the login page.
						</div>
						<div class="field_demo">
							
						</div>
						<div class="clearer"></div>
					</div>
					<div class="field_name">
						Set Login Background Color:
					</div>
					<div class="field_value">
						<input type="text" name="data[colors][login_background]" value="<?php echo $wzcl_options['colors']['login_background']; ?>" class="Multiple" />
					</div>
					<div class="field_description">
						Here you can select the background color for the login form.
					</div>
					<div class="field_demo">
						
					</div>
					<div class="clearer"></div>
					<div class="field_name">
						Set Label Font Color:
					</div>
					<div class="field_value">
						<input type="text" name="data[colors][label_font_color]" value="<?php echo $wzcl_options['colors']['label_font_color']; ?>" class="Multiple" />
					</div>
					<div class="field_description">
						Here you can select the color of the front for the username and password fields.
					</div>
					<div class="field_demo">
						
					</div>
					<div class="clearer"></div>
					<div class="field_name">
						Set Link Font Color:
					</div>
					<div class="field_value">
						<input type="text" name="data[colors][link_font_color]" value="<?php echo $wzcl_options['colors']['link_font_color']; ?>" class="Multiple" />
					</div>
					<div class="field_description">
						Here you can select the color of the front used for the links just below the login form.
					</div>
					<div class="field_demo">
						
					</div>
					<div class="clearer"></div>
					<div class="field_name">
						Set Link Font Shadow Color:
					</div>
					<div class="field_value">
						<input type="text" name="data[colors][link_font_shadow_color]" value="<?php echo $wzcl_options['colors']['link_font_shadow_color']; ?>" class="Multiple" />
					</div>
					<div class="field_description">
						Here you can select the color of the text shadow used for the links just below the login form.
					</div>
					<div class="field_demo">
						
					</div>
					<div class="clearer"></div>
					<div class="field_name">
						Hide the 'back to blog' link?
					</div>
					<div class="field_value">
						<select name="data[style][hide_back_to_link]">
							<option value="">Please select one…</option>
							<option value="No"<?php if($wzcl_options['style']['hide_back_to_link']=="No") echo ' selected'; ?>>No</option>
							<option value="Yes"<?php if($wzcl_options['style']['hide_back_to_link']=="Yes") echo ' selected'; ?>>Yes</option>
						</select>
					</div>
					<div class="field_description">
						Here you can select the color of the text shadow used for the links just below the login form.
					</div>
					<div class="field_demo">
						
					</div>
					<div class="clearer"></div>
					<div class="buttons">
						<input type="submit" value="Save Settings" class="btn primary"/>
					</div>
				</form>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
			<div class="footer">
				<p>Please consider making a donation, every donation helps further the development of this plugin.</p>
				<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="RHXPTAZPRWYCU">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
				<p>Checkout the <a href="http://websitez.com/wordpress-mobile/?src=wp-login" target="_blank">WP Mobile Detector</a> WordPress plugin from Websitez.com that will instantly create a mobile website!</p>
			</div>
		</div>
		<script type="text/javascript">
		jQuery('.alert-message').each(function(){ 
			var t = setTimeout("jQuery('.alert-message').hide('slow')",2000);
		});
		</script>
		<?php
	}
endif;

if(!function_exists('wzcl_remote_request')):
	function wzcl_remote_request($host,$path){
		$fp = curl_init($host);
		curl_setopt($fp, CURLOPT_POST, true);
		curl_setopt($fp, CURLOPT_POSTFIELDS, $path);
		curl_setopt($fp, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec($fp);
		curl_close($fp);
		
		return $page;
	}
endif;

add_action('init', 'wzcl_check_login');

if(is_admin()):
	add_action('admin_menu', 'wzcl_admin_menu');
	add_action('admin_notices', 'wzcl_check_configuration');
	if($_GET['page']=="wp_login"):
		add_action('init', 'wzcl_admin_head_scripts');
	endif;
endif;
?>