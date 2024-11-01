<?php
/*
Plugin Name: Share Juice
Plugin URI: http://www.webtecho.com/share-juice/
Description: A very fast and highly configurable plugin for all your social media needs. <strong>a)</strong> You can configure and add social media buttons/icons with a few clicks.<strong> b)</strong> You can configure a number of buttons such as Facebook, Google Plus, LinkedIn, Pinterest, StumbleUpon. <strong>c)</strong>You can also add pinterest buttons to your images.

Version: 3.1
Author:Ashvini Kumar Saxena
Author URI: http://www.webtecho.com/share-juice/
License: GPL2
*/
?>
<?php
/*  Copyright 2012  Ashvini Kumar Saxena  (email : support@webtecho.com)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

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
DEFINE("SJ_PLUGIN_URL_BASE", plugins_url() . '/share-juice');
DEFINE("SJ_PLUGIN_DIR_BASE",plugin_dir_path(__FILE__));

require_once dirname(__file__) . '/config/define_consts.php';
require_once dirname(__file__) . '/config/includes.php';

register_activation_hook(__file__, 'share_juice_plugin_installation');

?>