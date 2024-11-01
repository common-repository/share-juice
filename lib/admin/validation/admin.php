<?php
function share_juice_validate_admin_options()
{
	global $share_juice_error;
	
	// Validation Starts
	 /* show_debug_info */
    $share_juice_admin_options = array();
    $share_juice_admin_options['show_debug_info'] = isset($_POST['show_debug_info']) ?  true:false;
    // Validation: None
    
	/* disable_js_echo */
    $share_juice_admin_options['disable_js_echo'] = isset($_POST['disable_js_echo']) ?  true:false;
    // Validation: None
    
	/*url_sht_service */
    $share_juice_admin_options['url_sht_service'] = isset($_POST['url_sht_service'])?sanitize_text_field($_POST['url_sht_service']):'isgd';
    // Validation: To Be Done

    /*  */
    $share_juice_admin_options['bitly_login'] = isset($_POST['bitly_login'])?sanitize_text_field($_POST['bitly_login']):'';
    // Validation: Happens later
    
	/*  */
    $share_juice_admin_options['bitly_apikey'] = isset($_POST['bitly_apikey'])?sanitize_text_field($_POST['bitly_apikey']):'';
    // Validation: Happens now
    
	if ($share_juice_admin_options['url_sht_service'] == 'bitly'
        && (empty($share_juice_admin_options['bitly_login'])
            || empty($share_juice_admin_options['bitly_apikey'])
        )
    )
	$share_juice_error->add('E',_('Bitly login name and bitly api key need to filled'));

    /* facebook_appid */
    $share_juice_admin_options['facebook_appid'] = isset($_POST['facebook_appid'])?sanitize_text_field($_POST['facebook_appid']):'';
    // Validation: 
    
	/*  */
    $share_juice_admin_options['captcha_public_key'] = isset($_POST['captcha_public_key'])?sanitize_text_field($_POST['captcha_public_key']):'';
    // Validation:
    
	/*  */
    $share_juice_admin_options['captcha_private_key'] = isset($_POST['captcha_private_key'])?sanitize_text_field($_POST['captcha_private_key']):'';
    // Validation:
    
	/*  */
    $share_juice_admin_options['script_next_to_buttons'] = isset($_POST['script_next_to_buttons']) ?  true:false;
    // Validation: None


	    
  

	//hidden debugging options
	$share_juice_admin_options['use_https'] = isset($_POST['use_https'])? true:FALSE;
	$share_juice_admin_options['use_test_curl'] = isset($_POST['use_test_curl'])? true:FALSE;

    /* after_content_share_bar_position  */
    $share_juice_admin_options['after_content_share_bar_position'] = !empty($_POST['after_content_share_bar_position'])?sanitize_text_field($_POST['after_content_share_bar_position']):10;
    // Validation:
		if(!is_numeric($share_juice_admin_options['after_content_share_bar_position']))
	$share_juice_error->add('E',SJMessage::get_message_from_string(15,'general','Filter Priority'));
	
    /* before_content_share_bar_filter_priority */
    $share_juice_admin_options['before_content_share_bar_filter_priority'] = !empty($_POST['before_content_share_bar_filter_priority'])?sanitize_text_field($_POST['before_content_share_bar_filter_priority']):10;
    // Validation:
		if(!is_numeric($share_juice_admin_options['before_content_share_bar_filter_priority']))
	$share_juice_error->add('E',SJMessage::get_message_from_string(15,'general','Filter Priority'));
	
	
	/*  after_content_share_bar_filter_priority */
    $share_juice_admin_options['after_content_share_bar_filter_priority'] = !empty($_POST['after_content_share_bar_filter_priority'])?sanitize_text_field($_POST['after_content_share_bar_filter_priority']):10;
	  // Validation:
	if(!is_numeric($share_juice_admin_options['after_content_share_bar_filter_priority']))
	$share_juice_error->add('E',SJMessage::get_message_from_string(15,'general','Filter Priority'));
    
	
    /*  */
    // Validation:
	
	if(!$share_juice_error->is_severe_error())
	 unset($_POST['process_admin_form']);
	
	return $share_juice_admin_options;

}
?>