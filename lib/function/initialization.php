<?php
//declare global but initialize them in an action
$share_juice_code_gen_inst        = '';
$share_juice_error           = '';
$sj_table_name       = '';
$share_juice_options = '';
$share_juice_admin               = '';
$share_juice_upgrade_inst  = '';

$sj_mobile_detect = '';
$sj_post_meta = '';


add_action('init', 'share_juice_initialize_vars');

function share_juice_initialize_vars()
{
	// NOTE: if you declare a global variable 
	// DO NOT forget to declare it here
	global
	$share_juice_code_gen_inst,//Codegenerator instance
	$share_juice_error,//Share Juice Error
	$wpdb, // Wordpress DB
	$sj_table_name, // Share Juice Main Table Name
	$share_juice_options, // Share Juice Options Global
	$share_juice_admin, //Share Juice Admin 
	$share_juice_upgrade_inst, 
	$sj_mobile_detect,// Share Juice Mobile Detect
	$sj_post_meta;
	
	//First get options
	$share_juice_options = get_option('share-juice-options');
	
	//Codegenerator instance
	$share_juice_code_gen_inst            = new CodeGenerator();
	//Share Juice Error
	$share_juice_error           = new SJError();
	// Share Juice Main Table Name
	$sj_table_name       = $wpdb->prefix . 'share_juice';
	//Share Juice Admin 
	$share_juice_admin               = new SJAdminMain();
	// Share Juice Mobile Detect
	$sj_mobile_detect = new SJ_Mobile_Detect();
	
	
	// call to add button	
	$share_juice_code_gen_inst->add_buttons();

	$sj_mobile_detect = new SJ_Mobile_Detect();


	}

if(isset($_GET['print']))
{
	add_action('init','share_juice_get_template_print');
}




?>