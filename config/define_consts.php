<?php
/*---------If there is a problem with pinterest button,
remove minify js from w3tc
-----------------*/
//defining a few constants
DEFINE("SJ_IMG_URL", SJ_PLUGIN_URL_BASE . '/images');
DEFINE("SJ_IMG_DIR", SJ_PLUGIN_DIR_BASE .DIRECTORY_SEPARATOR. 'images');
$upload_dir_arr = wp_upload_dir();
define('SJ_UPLOAD_DIR_BASE', $upload_dir_arr['basedir'] .DIRECTORY_SEPARATOR. 'share_juice');
define('SJ_UPLOAD_URL_BASE', $upload_dir_arr['baseurl']. '/share_juice');

define("SJ_ICON_DIR_BASE", SJ_UPLOAD_DIR_BASE .DIRECTORY_SEPARATOR. 'share_icons');
define("SJ_ICON_URL_BASE", SJ_UPLOAD_URL_BASE . '/share_icons');
DEFINE("SJ_SCRIPTS_URL_BASE", SJ_PLUGIN_URL_BASE . '/scripts');
DEFINE("SJ_SCRIPTS_DIR_BASE", SJ_PLUGIN_DIR_BASE .DIRECTORY_SEPARATOR. 'scripts');
DEFINE("SJ_CUSTOM_URL_BASE", SJ_UPLOAD_URL_BASE . '/custom');
DEFINE("SJ_CUSTOM_DIR_BASE", SJ_UPLOAD_DIR_BASE .DIRECTORY_SEPARATOR. 'custom');
DEFINE("SJ_SPACE", ' ');
DEFINE("ENABLE_JS_FOR_BUTTONS", true);
DEFINE("SJ_NAMESPACE_FOR_CSS_DIV","share-juice-");
DEFINE("SJ_UPGRADE_URL" ,"http://www.webtecho.com/packages");
DEFINE("DIFF_UPDATE_CHECK_IN_DAY",1);
DEFINE("SJ_VIDEO_URL" ,"http://www.webtecho.com/videos/20");


?>