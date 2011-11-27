<?php


/*
Plugin Name: Disable Registration Email
Version: 0.9.0
Plugin URI: http://www.nostate.com/3790/disable-registration-email-wordpress-plugin/
Description: Turns off the automatic "New user registration on your blog" email messages to the admin email address. Useful for large sites.
Author: Mike Gogulski
Author URI: http://www.nostate.com/
*/

/*
	This is free and unencumbered software released into the public domain.

	Anyone is free to copy, modify, publish, use, compile, sell, or
	distribute this software, either in source code form or as a compiled
	binary, for any purpose, commercial or non-commercial, and by any
	means.

	In jurisdictions that recognize copyright laws, the author or authors
	of this software dedicate any and all copyright interest in the
	software to the public domain. We make this dedication for the benefit
	of the public at large and to the detriment of our heirs and
	successors. We intend this dedication to be an overt act of
	relinquishment in perpetuity of all present and future rights to this
	software under copyright law.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
	OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
	ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	OTHER DEALINGS IN THE SOFTWARE.

	For more information, please refer to <http://unlicense.org/>
*/

define('DISABLE_ADMIN_EMAIL_VERSION', '0.9.0');

add_filter('wp_mail', 'disable_registration_email_filter');

function disable_registration_email_filter($result = '') {
	extract($result);
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	if (strstr(sprintf(__('[%s] New User Registration'), $blogname), $subject)) {
		$to = '';
		$subject = '';
		$message = '';
		$headers = '';
		$attachments = array ();
		return compact('to', 'subject', 'message', 'headers', 'attachments');
	}
	return $result;
}

add_filter('plugin_row_meta', 'dre_filter_plugin_links', 10, 2);

// Add support information
function dre_filter_plugin_links($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="http://www.nostate.com/3790/disable-registration-email-wordpress-plugin/">Support</a>';
		$links[] = '<a href="http://www.nostate.com/support-nostatecom/">Donate</a>';
	}
	return $links;
}
?>
