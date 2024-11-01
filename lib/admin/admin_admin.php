<?php
/*
This class is to display the Admin Options for
Share Juice
*/
class SJAdminAdmin
{
    private $post_arr = array();
    //copy of global variables to be assigned later
    private $error,$wpdb,$options,$sj_upgrade ;
    public
    function __construct()
    {

        if (isset($_POST['submit']) && $_POST['submit'] == 'Share_Juice_Export_Options')        add_action('admin_init',array($this,'export_options'));
    }
    public
    function validate_and_generate_forms()
    {
        //declare global
        global $wpdb,$share_juice_options,$share_juice_upgrade_inst;

        //copy globals to local so that
        //one does not need to declare them again
        $share_juice_error = new SJError();
        $this->wpdb = $wpdb;
        $this->options = $share_juice_options;
        $this->upgrade = $share_juice_upgrade_inst;

        //check if any form submit was done
        if (!empty($_POST['submit'])) {
            // Look for values in post submit variable
            switch ($_POST['submit']) {
                //validate and save
                //returns $_POST to a common array if there is error

                //Case List
                //SaveAdminOptions
                case "SaveAdminOptions":
                // Only here we validate and savefields
                if (!$this->validate_and_save()) {

                    $share_juice_error->add('E',SJMessage::get_message_from_string(7,'general'));
                }
                break;
                case "PopulateSampleButtons":
                $this->populate_sample_buttons();
                break;
                case "CheckVersionNow":
                //check upgrade info from api
                $this->upgrade->check_for_updates(TRUE);
                // check if any error is returned by upgrade object
                $share_juice_error = $this->upgrade->get_error_object();
                break;

            }
        }






        // Finally show all forms
        $this->show_all_forms();
    }
    // Show all forms
    function show_all_forms()
    {
        global $share_juice_error;
        // If there is any error / information display
        $share_juice_error->show_messages();
        // Create outer div
        SJFormHelper::div_start("admin");
        // output header
        share_juice_admin_header('Admin Options');
        // This is the start of accordion
        SJFormHelper::div_start("accordion");

        // All individual divs and forms
        // Admin form
        $this->show_admin_form();



        //Close Accordion
        SJFormHelper::div_close("accordion");
        // Close wrapper
        SJFormHelper::div_close("admin");
    }

    function show_admin_form()
    {

        $share_juice_admin_options = array();

        if (isset($_POST['process_admin_form'])) {

            $share_juice_admin_options = $_POST;

        }

        else {
            $share_juice_admin_options = $this->options['admin_options'];
        }


        $action_url = $_SERVER['REQUEST_URI'];

        SJFormHelper::heading('h3',"Admin Options");

        SJFormHelper::div_start('admin-options');

        SJFormHelper::form_start_html_new(array('name'      =>'admin-options','action_url'=>$action_url));

        SJFormHelper::heading('h4',"Debug Options");

        SJFormHelper::div_start('','admin-field-grouping');


        SJFormHelper::field_input_html(    array(
                'name'                => 'show_debug_info',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'show-debug-info',
                'label'               => _('Show Debug Info'),
                'get_value_from_array'=>$share_juice_admin_options,
                'helper_text'         => 'This is to show debug info during support. Uncheck if you dont want to see debug info',
            ));

       

        SJFormHelper::div_close('','admin-field-grouping');

        SJFormHelper::heading('h4',"URL shortening service");


        ?>


        <p>
            <label for = "url_service">
                URL shortner Service
            </label>
            <input type="radio" name="url_sht_service" value="bitly"  <?php echo $share_juice_admin_options['url_sht_service'] == 'bitly'? 'checked':'' ?>>Bit.ly
            <input type="radio" name="url_sht_service" value="tinyurl"  <?php echo $share_juice_admin_options['url_sht_service'] == 'tinyurl'? 'checked':'' ?> >Tinyurl
            <input type="radio" name="url_sht_service" value="isgd"  <?php echo $share_juice_admin_options['url_sht_service'] == 'isgd'? 'checked':'' ?>>Is.gd
        </p>
        <div class="sj_url_shortner" id="sj_bitly_api_fields" style="display:none" >
            <p>
                <label for="bitly_login">
                    Bit.ly login name
                </label>
                <input type="text" name="bitly_login" value="<?php echo esc_html($share_juice_admin_options['bitly_login']) ?>"/>
            </p>
            <p>
                <label for="bitly_apikey">
                    Bit.ly API Key
                </label>
                <input type="text" name="bitly_apikey" value="<?php echo esc_html($share_juice_admin_options['bitly_apikey']) ?>"/>
            </p>
        </div>
        <?php

        SJFormHelper::heading('h4','Facebook APPID');
        SJFormHelper::field_input_html(    array(
                'name'                => 'facebook_appid',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'facebook-appid',
                'label'               => _('Facebook AppID'),
                'get_value_from_array'=>$share_juice_admin_options,
                'helper_text'         => 'Get your app id
                <a href="https://developers.facebook.com/apps" target="_blank">
                here
                </a>'
            ));


        SJFormHelper::heading('h4','Filter priorities');
        SJFormHelper::div_start('','admin-field-grouping');
        SJFormHelper::field_input_html(    array(
                'name'                => 'before_content_share_bar_filter_priority',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'before-content-share-bar-filter-priority',
                'label'               => _('Before Content Share bar filter priority'),
                'get_value_from_array'=>$share_juice_admin_options,
                'helper_text'         =>_('This number provides the value at which wordpress calls the Share Juice filter for the_content filter. Since there are many other plugins using the same filter, it might lead to conflicts. You might need to experiment a little bit with the numbers before you find the right value'),
                'size'                =>"3"
            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'after_content_share_bar_filter_priority',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'after-content-share-bar-filter-priority',
                'label'               => _(' After Content Share bar filter priority'),
                'get_value_from_array'=>$share_juice_admin_options,
                'helper_text'         =>_('This number provides the value at which wordpress calls the Share Juice filter for the_content for the_content filter . Since there are many other plugins using the same filter, it might lead to conflicts. You might need to experiment a little bit with the numbers before you find the right value'),
                'size'                =>"3"
            ));



        SJFormHelper::div_close('','admin-field-grouping');

        ?>

        <input type="hidden" name="process_admin_form" value="1" />

        <?php
        echo '<br/>';
        SJFormHelper::button_html('', 'SaveAdminOptions', 'Save Options');






        SJFormHelper::form_close_html();

        SJFormHelper::div_close('admin-options');
    }

    function validate_and_save()
    {
        global $share_juice_error;

        $share_juice_admin_options = share_juice_validate_admin_options();

        if ($share_juice_error->is_severe_error()) {
            return false;
        }
        else {




            $this->options['admin_options'] = $share_juice_admin_options;
            update_option('share-juice-options', $this->options);
            //unset process
            //read from db to restore right value
            $this->options = get_option('share-juice-options');
            return true;
        }

    }
}
?>