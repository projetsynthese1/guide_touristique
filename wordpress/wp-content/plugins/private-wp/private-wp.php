<?php
/*
Plugin Name: Private WP
Plugin URI: http://www.kruse-net.dk/wordpress-plugins/private-wp/
Description: Makes your blog accessible only to logged in users.
Version: 1.1
Author: Jakob Kruse
Author URI: http://www.kruse-net.dk/
*/
?>
<?php
/*  Copyright 2008  Jakob Kruse  (email : kruse@kruse-net.dk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
function private_wp() {
  if (!is_user_logged_in()) {
    auth_redirect();
  }
}

add_action('get_header', 'private_wp');
?>