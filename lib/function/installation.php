<?php

add_action( 'activated_plugin', 'share_juice_save_activation_error' );


function share_juice_save_activation_error()
{
    update_option( 'plugin_error',  ob_get_contents() );
}



function share_juice_plugin_installation()
{


    share_juice_create_base_table();
    
    share_juice_setup_options_on_activation();
    share_juice_create_dynamic_css();

}
function share_juice_create_dynamic_css()
{
    $css_php_inst = new CSSDynamic();
    $css_php_inst->save();
}
function share_juice_setup_options_on_activation()
{

    $share_juice_options = get_option('share-juice-options');

    /* Global Options */
  
    //Set all show share boxes to TRUE
    // Floating Box
    if (!isset($share_juice_options['global_general_options']['show_floating_box']))    $share_juice_options['global_general_options']['show_floating_box'] = true;
    // Before Content Box
    if (!isset($share_juice_options['global_general_options']['show_before_content_box']))        $share_juice_options['global_general_options']['show_before_content_box'] = true;
    // After Content Box
    if (!isset($share_juice_options['global_general_options']['show_after_content_box']))        $share_juice_options['global_general_options']['show_after_content_box'] = true;

    // Mark the sources of each box to JS Buttons
   // if (!isset($share_juice_options['global_general_options']['floating_bar_source']))
    $share_juice_options['global_general_options']['floating_bar_source'] = 'js_button';
   // if (!isset($share_juice_options['global_general_options']['before_content_source']))   
        $share_juice_options['global_general_options']['before_content_source'] = 'js_button';
  //  if (!isset($share_juice_options['global_general_options']['after_content_source']))   
       $share_juice_options['global_general_options']['after_content_source'] = 'js_button';

    // Set default shortner service
    if (!isset($share_juice_options['admin_options']['url_sht_service']))        $share_juice_options['admin_options']['url_sht_service'] = 'isgd';

    // Set classes for pinterent
    // Show on this class
    if (!isset($share_juice_options['global_pinterest_options']['pinterest_show_for_img_class']))        $share_juice_options['global_pinterest_options']['pinterest_show_for_img_class'] = 'wp-image-';
    // Hide on this class
    if (!isset($share_juice_options['global_pinterest_options']['pinterest_hide_for_img_class']))        $share_juice_options['global_pinterest_options']['pinterest_hide_for_img_class'] = 'wp-smiley';

    
    // Set priorities for content filters
    // Before content Share Box
    if (!isset($share_juice_options['admin_options']['before_content_share_bar_filter_priority']))         $share_juice_options['admin_options']['before_content_share_bar_filter_priority'] = 10;
    // After content Share box
    if (!isset($share_juice_options['admin_options']['after_content_share_bar_filter_priority']))         $share_juice_options['admin_options']['after_content_share_bar_filter_priority'] = 10;
   
    update_option('share-juice-options', $share_juice_options);
    //reread
}
function share_juice_create_base_table()
{
    global $wpdb;

    // Share Juice Base Table
    $table_name_base = $wpdb->prefix . 'share_juice';
    $sql_base        = "CREATE  TABLE IF NOT EXISTS {$table_name_base} (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `button_key` VARCHAR(45) NOT NULL ,
    `custom_key` VARCHAR(45) NOT NULL ,
    `button_normal_active` TINYINT NULL ,
    `button_floating_active` TINYINT NULL ,
    `button_normal_order` INT NULL default 1,
    `button_floating_order` INT NULL default 1 ,
    `button_location_before_content` TINYINT NULL ,
    `button_location_after_content` TINYINT NULL ,
    `button_show_on_home` TINYINT NULL,
    `button_show_in_post` TINYINT NULL,
    `button_show_on_archive` TINYINT NULL,
    `button_show_on_page` TINYINT NULL,
    `16px_file_exists` TINYINT NULL,
    `32px_file_exists` TINYINT NULL ,
    `64px_file_exists` TINYINT NULL ,
    `button_config` LONGTEXT NULL ,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `custom_key_UNIQUE` (`custom_key` ASC)
    );";

    $result          = $wpdb->query($sql_base);


}


?>