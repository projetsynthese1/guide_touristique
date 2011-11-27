<?php
/**
 * Plugin Name: Member Access
 * Plugin URI:  http://www.chrisabernethy.com/wordpress-plugins/member-access/
 * Description: Member Access is a WordPress plugin that allows an administrator to require that users be logged-in in order to view certain posts and pages.
 * Version:     1.1.3
 * Author:      Chris Abernethy
 * Author URI:  http://www.chrisabernethy.com/
 * Text Domain: member_access
 * Domain Path: /wp-content/plugins/member_access/locale
 *
 * Copyright 2008 Chris Abernethy
 *
 * This file is part of Member Access.
 *
 * Member Access is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Member Access is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Member Access.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// Include all class files up-front so that we don't have to worry about the
// include path or any globals containing the plugin base path.

require_once 'lib/MemberAccess/Structure.php';
require_once 'lib/MemberAccess/Structure/Options.php';
require_once 'lib/MemberAccess/Structure/View.php';
require_once 'lib/MemberAccess.php';

// Run the plugin.
MemberAccess::run(__FILE__);

/* EOF */