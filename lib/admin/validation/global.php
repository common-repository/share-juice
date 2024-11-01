<?php

/* Function:  Validate global general options */
function share_juice_validate_global_general_options()
{
    global $share_juice_error;

    $global_general_options = array();
   

    /* show_floating_box */
    $global_general_options['show_floating_box'] = isset($_POST['show_floating_box']) ? true : false;
    // Validation: none

    /* show_before_content_box */
    $global_general_options['show_before_content_box'] = isset($_POST['show_before_content_box']) ? true : false;
    // Validation: None

    /* show_after_content_box */
    $global_general_options['show_after_content_box'] = isset($_POST['show_after_content_box']) ? true : false;
    // Validation: None

    /* floating_bar_source*/
    $global_general_options['floating_bar_source'] ='js_button';
    // Validation:
   
    /* before_content_source */
    $global_general_options['before_content_source'] =
    'js_button';
    // Validation:
   
    /* after_content_source */
    $global_general_options['after_content_source'] ='js_button';
    // Validation:
   
    /* exclude_for_floating_box */
    $global_general_options['exclude_for_floating_box'] = !empty($_POST['exclude_for_floating_box']) ? sanitize_text_field($_POST['exclude_for_floating_box']) : '';

    // Validation: To be done later for checking availability of post / slugs


    /* exclude_for_before_content_box */
    $global_general_options['exclude_for_before_content_box'] = !empty($_POST['exclude_for_before_content_box']) ? sanitize_text_field($_POST['exclude_for_before_content_box']): '';
    // Validation: To be done later for checking availability of post / slugs

    /* exclude_for_after_content_box */
    $global_general_options['exclude_for_after_content_box'] = !empty($_POST['exclude_for_after_content_box']) ? sanitize_text_field($_POST['exclude_for_after_content_box']) : '';
    // Validation: To be done later for checking availability of post / slugs

    if (!$share_juice_error->is_severe_error())
    unset($_POST['process_global_general_options']);

    return $global_general_options;
}

/* Function:  Validate pinterest options */
function share_juice_validate_pinterest_options()
{
    global $share_juice_error;

    $pinterest_options = array();
    /* pinterest_button_on_post */
    $pinterest_options['pinterest_button_on_post'] = isset($_POST['pinterest_button_on_post']) ? true : false;
    // Validation: None
    /* pinterest_button_on_home */
    $pinterest_options['pinterest_button_on_home'] = isset($_POST['pinterest_button_on_home']) ? true : false;
    // Validation: None
    /* pinterest_button_on_page */
    $pinterest_options['pinterest_button_on_page'] = isset($_POST['pinterest_button_on_page']) ? true : false;
    // Validation: None
    /* pinterest_count_layout */
    $pinterest_options['pinterest_count_layout'] = !empty($_POST['pinterest_count_layout']) ? $_POST['pinterest_count_layout'] : 'none';
    // Validation:
    if (!in_array($_POST['pinterest_count_layout'],array(
                "horizontal","vertical","none")
        )
    )
    $share_juice_error->add('E',
        SJMessage::get_message_from_string(
            5,
            'general',
            'Count Layout for Pinterest')
    );
    /* pinterest_excl_images */
    $pinterest_options['pinterest_excl_images'] = !empty($_POST['pinterest_excl_images'])?trim(sanitize_text_field($_POST['pinterest_excl_images'])):'';
    // Validation: Check for url validation

    /* pinterest_show_for_img_class */
    $pinterest_options['pinterest_show_for_img_class'] = !empty($_POST['pinterest_show_for_img_class'])?trim(sanitize_text_field($_POST['pinterest_show_for_img_class'])):'';
    // Validation: None

    /* pinterest_hide_for_img_class */
    $pinterest_options['pinterest_hide_for_img_class'] = !empty($_POST['pinterest_hide_for_img_class'])?trim(sanitize_text_field($_POST['pinterest_hide_for_img_class'])):'';
    // Validation: None

    return $pinterest_options;
    /*  */
    // Validation:
}
function share_juice_validate_text_box_options()
{
    global $share_juice_error;

    $text_box_options = array();
    /* text_before_content_enabled */
    $text_box_options['text_before_content_enabled'] = isset($_POST['text_before_content_enabled']) ? true : false;
    // Validation: None
    /* text_after_content_enabled */
    $text_box_options['text_after_content_enabled'] = isset($_POST['text_after_content_enabled']) ? true : false;
    // Validation: None
    /* text_box_before_content */
    $text_box_options['text_box_before_content'] = isset($_POST['text_box_before_content']) ?
    trim($_POST['text_box_before_content']) : '';
    // Validation: None
    /* text_box_after_content */
    $text_box_options['text_box_after_content'] = isset($_POST['text_box_after_content']) ?
    trim($_POST['text_box_after_content']) : '';
    // Validation: None

    return $text_box_options;
    /*  */
    // Validation:

}
function share_juice_validate_float_box()
{
    global $share_juice_error;
    $floating_box_options = array();

    $left_percent = 2;
    $top_percent  = 10;
    /* floating_box_left_margin */
    $floating_box_options['floating_box_left_margin'] = !empty($_POST['floating_box_left_margin']) ?
    sanitize_text_field($_POST['floating_box_left_margin']) : 0;
    // Validation:
    if (!is_numeric($floating_box_options['floating_box_left_margin']))
    $share_juice_error->add('E',
        SJMessage::get_message_from_string(
            15,
            'general',
            'Floating Box Left Margin')
    );

    /* floating_box_bgcolor  */
    $floating_box_options['floating_box_bgcolor'] = isset($_POST['floating_box_bgcolor']) ?
    sanitize_text_field($_POST['floating_box_bgcolor']) : 'fff';
    // Validation: None
    /* floating_box_shadow */
    $floating_box_options['floating_box_shadow'] = isset($_POST['floating_box_shadow'])? TRUE:FALSE;
    // Validation: None
    /* floating_box_shadowcolor */
    $floating_box_options['floating_box_shadowcolor'] = isset($_POST['floating_box_shadowcolor']) ?
    sanitize_text_field($_POST['floating_box_shadowcolor']) : '000';
    // Validation: None
    /* floating_box_textontop */
    $floating_box_options['floating_box_textontop'] = isset($_POST['floating_box_textontop']) ?
    sanitize_text_field($_POST['floating_box_textontop']) : 'Share';
    // Validation: None

    if (!$share_juice_error->is_severe_error())
    unset($_POST['process_floating_box_options']);
    return $floating_box_options;
    /*  */
    // Validation:
}


function share_juice_validate_facebook_like_widget_options()
{
    global $share_juice_error;
    $facebook_like_widget_options = array();

    $facebook_like_widget_options['data-href'] = isset($_POST['data-href']) ?  sanitize_text_field($_POST['data-href']) : '';



    $facebook_like_widget_options['data-width'] = isset($_POST['data-width']) ?  sanitize_text_field($_POST['data-width']) : '292';
    if (!is_numeric($facebook_like_widget_options['data-width']))
    $share_juice_error->add('E',
        SJMessage::get_message_from_string(
            15,
            'general',
            'Data Width')
    );

    $facebook_like_widget_options['data-height'] = isset($_POST['data-height']) ?  sanitize_text_field($_POST['data-height']) : '';
    if (!empty($facebook_like_widget_options['data-height']) && !is_numeric($facebook_like_widget_options['data-height']))
    $share_juice_error->add('E',
        SJMessage::get_message_from_string(
            15,
            'general',
            'Data Width')
    );

    $facebook_like_widget_options['data-colorscheme'] = isset($_POST['data-colorscheme']) ?  true:false;
    $facebook_like_widget_options['data-show-faces'] = isset($_POST['data-show-faces']) ?  true : false;
    $facebook_like_widget_options['data-border-color'] = isset($_POST['data-border-color']) ?  sanitize_text_field($_POST['data-border-color']) : '';
    $facebook_like_widget_options['data-stream'] = isset($_POST['data-stream']) ?  true : false;
    $facebook_like_widget_options['data-header'] = isset($_POST['data-header']) ?  true : false;

    if (!$share_juice_error->is_severe_error())
    unset($_POST['process_facebook_like_widget_options']);

    return  $facebook_like_widget_options;
}




?>