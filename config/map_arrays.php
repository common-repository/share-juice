<?php
//This is the description shown in the drop down
$share_juice_social_buttons_arr = array(
	'PR'=>"Print Button",
	
	'FB'=>'Facebook Like',
	'GP'=> 'Google Plus',
	'TW'=> 'Twitter',
	'LI'=>'LinkedIn',
	'SU'=>'StumbleUpon',
	'PI'=>'Pinterest',
	'BF'=>'Buffer',
	'FS'=>'Facebook Share',
	'CC'=>'Comments Counter',
	'FR'=>'Flattr',
	'TR'=>'Tumblr',
	'KI'=>'Send to Kindle'

);

//Internal class matching
$share_juice_class_map_arr = array(
	'PR'=>"SJ_PrintButton",
	
	'IC'=>'SJ_Icon',
	'FB'=>'SJ_Facebook',
	'GP'=> 'SJ_GooglePlus',
	'TW'=> 'SJ_Twitter',
	'LI'=>'SJ_LinkedIn',
	'SU'=>'SJ_StumbleUpon',
	'PI'=>'SJ_Pinterest',
	'BF'=>'SJ_Buffer',
	'FS'=>"SJ_FacebookShare",
	'CC'=>'SJ_Comments',
	'FR'=>'SJ_Flattr',
	'TR'=>'SJ_Tumblr',
	'KI'=>'SJ_Kindle'
);

$share_juice_button_content_location_arr = array(
	"BC"=> "Before Content",
	"AC"=> "After Content"

);

$share_juice_api_arr = array(
	'NO'=>'None',
	'FB'=>'Facebook',
	'GP'=> 'Google',
	'TW'=> 'Twitter',
	'LI'=>'LinkedIn',
	'SU'=>'StumbleUpon',
	'BL'=> 'Blog RSS Feed',
	'BC'=> 'Blog Comments Feed'
);

$share_juice_css_div_class = array(
	'PR'=>"share-juice-button-print",
	
	'FB'=>'share-juice-button-facebook-like',
	'GP'=>'share-juice-button-google-plus',
	'TW'=> 'share-juice-button-twitter',
	'LI'=>'share-juice-button-linkedin',
	'SU'=>'share-juice-button-stumbleupon',
	'PI'=>'share-juice-button-pinterest',
	'BF'=>'share-juice-button-buffer',
	'FS'=>'share-juice-button-facebook-share',
	'CC'=>'share-juice-button-comments',
	'FR'=>'share-juice-button-flattr',
	'TR'=>'share-juice-button-tumblr',
	'KI'=>'share-juice-button-kindle',
	'IC'=>'share-juice-icon'

);


$share_juice_js_key_arr = array();
//this is for the buttons requested by short code
//if the js has already been added , then it wont added again
$share_juice_shortcode_button_key_arr = array();
//some processing
asort($share_juice_social_buttons_arr);

?>