<?php

class SJAdminGlobal
{


    // called from the admin.php for validating
    public
    function validate_and_generate_forms()
    {

        global $share_juice_error;

        if (!empty($_POST)) {
            // if any button for save was pressed
            switch ($_POST['submit']) {
                case "SaveGlobalGeneralOptions":
                $this->save_global_general_options();
                break;
                case "SaveTwitterGlobalOptions":
                //$this->save_twitter_global_options();
                break;
                case "SaveGlobalFloatingOptions":
                $this->save_global_float_bar_options();
                $this->save_css_file();
                break;
                case "SaveTextBoxOptions":
                $this->save_text_box_options();
                break;
                case "SavePinterestOptions":
                $this->save_pinterest_options();
                break;
                case "SaveFacebookLikeWidgetOptions":
                $this->save_facebook_like_widget_options();
                break;

                default:
                break;
            }

        }
        $this->show_share_juice_all_forms();
        //save data or show error


    }

    function show_share_juice_all_forms()
    {
        global $share_juice_error;
        $share_juice_error->show_messages();

        SJFormHelper::div_start("admin");
        share_juice_admin_header('Global Options');

        SJFormHelper::div_start("accordion");
        $this->show_global_general_options();
        $this->show_pinterest_options();
        $this->show_text_box_options();
        $this->show_global_float_bar_settings();
        //$this->show_global_twitter_options();
        $this->share_juice_share_juice_show_facebook_like_widget_settings();


        //$this->show_google_authorship_options();
        SJFormHelper::div_close();
        SJFormHelper::div_close();
    }
    function show_global_general_options()
    {

        global $share_juice_options;

        //Validation if correct will unset this option
        if (isset($_POST['process_global_general_options'])) {

            $global_general_options = $_POST;
        }
        else {
            $global_general_options = !empty(
                $share_juice_options['global_general_options']
            )?
            $share_juice_options['global_general_options']:array();

        }

        //start form

        SJFormHelper::heading('h3',"General ");
        SJFormHelper::div_start('admin-global-general-options');

        SJFormHelper::form_start_html('admin-global-general-option');


        //Heading: Switch
        SJFormHelper::show_heading('h4','Switch for each share box ');

        $icon_source_array = array("icon"     =>"Picture Icons","js_button"=>"SocialMedia Buttons");
        //Group of Field:
        SJFormHelper::div_start('','admin-field-grouping');
        //Field: Show Floating box
        SJFormHelper::field_input_html(    array(
                'name'                => 'show_floating_box',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'show-floating-box',
                'label'               => _('Enable Floating Box'),
                'get_value_from_array'=> $global_general_options,
                'helper_text'         =>_('If unchecked this will hide floating box (overrides settings in buttons)')

            ));

        //Field: Exclude floating bar
        SJFormHelper::field_input_html(    array(
                'name'                => 'exclude_for_floating_box',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'exclude-for-floating-box',
                'label'               => _('Exclude on these pages/posts'),
                'get_value_from_array'=> $global_general_options,
                'helper_text'         =>_('Floating bar will exclude from appearing on these pages. Values can be comma separated list such as 358,hello-world ( either post id or slug)'),
                'size'                =>"50"
            ));



        //Group of Fields: Close
        SJFormHelper::div_close("");


        //Group of Fields
        SJFormHelper::div_start('','admin-field-grouping');

        //Field : Show Before Content

        SJFormHelper::field_input_html(    array(
                'name'                => 'show_before_content_box',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'show-before-content-box',
                'label'               => _('Enable Before Content Box'),
                'get_value_from_array'=> $global_general_options,
                'helper_text'         =>_('If unchecked this will hide before content box (overrides settings in buttons)'),
                'size'                =>"50"
            ));

        $exclude_for_before_content_box = isset($global_general_options['exclude_for_before_content_box'])
        ?$global_general_options['exclude_for_before_content_box']:'';

        //Field: Exclude floating bar
        SJFormHelper::field_input_html(    array(
                'name'                => 'exclude_for_before_content_box',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'exclude-for-before-content-box',
                'label'               => _('Exclude these pages/posts'),
                'get_value_from_array'=> $global_general_options,
                'helper_text'         =>_('Before content bar will exclude from appearing on these pages. Values can be comma separated list such as 358,hello-world ( either post id or slug)'),
                'size'                =>"50"
            ));




        //end of grouping
        SJFormHelper::div_close();

        //group of field
        SJFormHelper::div_start('','admin-field-grouping');

        //Field : Show After content
        SJFormHelper::field_input_html(    array(
                'name'                => 'show_after_content_box',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'show-floating-box',
                'label'               => _('Enable After Content Box'),
                'get_value_from_array'=> $global_general_options,
                'helper_text'         =>_('If unchecked this will hide after content box (overrides settings in buttons)'),
                'size'                =>"50"

            ));


        //exclude floating bar for these pages
        SJFormHelper::field_input_html(    array(
                'name'                => 'exclude_for_after_content_box',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'exclude-for-after-content-box',
                'label'               => _('Exclude on these pages/posts'),
                'get_value_from_array'=> $global_general_options,
                'helper_text'         =>_('After content bar will exclude from appearing on these pages. Values can be comma separated list such as 358,hello-world ( either post id or slug)'),
                'size'                =>"50"
            ));
        $after_content_source = isset($global_general_options['after_content_source'])? $global_general_options['after_content_source']:"js_button";


        //end of grouping
        SJFormHelper::div_close();


        SJFormHelper::input_hidden_field_html('process_global_general_options','process_global_general_options',1);


        SJFormHelper::button_html('', 'SaveGlobalGeneralOptions', 'Save ');


        SJFormHelper::form_close_html();

        SJFormHelper::div_close('admin-global-options');
    }

    function save_global_general_options()
    {

        global $share_juice_error, $share_juice_options;

        $global_general_options = share_juice_validate_global_general_options();

        if ($share_juice_error->is_severe_error())
        return;

        $share_juice_options['global_general_options'] = $global_general_options;

        update_option('share-juice-options', $share_juice_options);
        //re - read from db to restore recent value
        $share_juice_options = get_option('share-juice-options');


    }


    /*
    //Pinterest
    */

    function show_pinterest_options()
    {
        global  $share_juice_options;
        $pinterest_array = array(
            "horizontal"=>"Horizontal",
            "vertical"  =>"Vertical",
            "none"      =>"none"
        )    ;


        if (isset($_POST['process_global_pinterest_options'])) {

            $pinterest_options = $_POST;

        }
        else {
            $pinterest_options = !empty(
                $share_juice_options['global_pinterest_options']
            )
            ?
            $share_juice_options['global_pinterest_options']:array();

        }

        SJFormHelper::heading('h3',"Pinterest ");
        SJFormHelper::div_start('admin-global-pinterest-options');
        SJFormHelper::form_start_html('admin-global-pinterest-options');

        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_button_on_home','name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'pinterest-button-on-home',
                'label'               => _('Show on home page'),
                'get_value_from_array'=>$pinterest_options,
                'helper_text'         =>    _('Check to show pinterest button on home page (probably will not show up on excerpts')
                //
            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_button_on_post','name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'pinterest-button-on-home',
                'label'               => _('Show on posts'),
                'get_value_from_array'=>$pinterest_options,
                'helper_text'         =>    _('Check to show pinterest button on single posts')
                ,

            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_button_on_page','name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'pinterest-button-on-page',
                'label'               => _('Show on pages'),
                'get_value_from_array'=>$pinterest_options,
                'helper_text'         =>    _('Check to show pinterest button on pages'),

            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_count_layout','name_array'          => '',
                'type'                =>"select",
                'id'                  =>'pinterest-count-layout',
                'label'               => _('Pinterest Count Parameter'),
                'select_array'        =>$pinterest_array,
                'get_value_from_array'=>$pinterest_options,
                'helper_text'         =>    _('Choose the count layout')
            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_excl_images',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'pinterest-excl-images',
                'label'               => _('Pinterest Image exclusion URLs'),
                'get_value_from_array'=> $pinterest_options,
                'helper_text'         =>    _('Enter image URLs seperated by commas e.g. image url 1, image url 2,image url 3')

            ));

        SJFormHelper::show_heading('h4','Advanced Options');
        SJFormHelper::div_start('','admin-field-grouping');
        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_show_for_img_class',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'pinterest_show_for_img_class',
                'label'               => _('Show Pinterest on image with classes(contains)'),
                'get_value_from_array'=>$pinterest_options,
                'helper_text'         =>_('Show Pinterest on image with classes. Pinterest button will be added to all images that contain these classes. If you have not modified the standard classes, you don\'t need to add anything here. Default: "wp-image-"'),
                'size'                =>30
            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'pinterest_hide_for_img_class',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'pinterest_hide_for_img_class',
                'label'               => _('Hide Pinterest on image with classes(contains)'),
                'get_value_from_array'=>$pinterest_options,
                'helper_text'         =>    _('Hide Pinterest on image with classes. Pinterest button will be not be added to all images that contain these classes. If you have not modified the standard classes, you don\'t need to add anything here. Default: "wp-smiley"'),
                'size'                =>30
            ));
        SJFormHelper::div_close('admin-field-grouping');

        SJFormHelper::field_input_html(    array(
                'name'      => '"process_global_pinterest_options"',
                'id'        => '"process-global-pinterest-options"',
                'name_array'=> '',
                'type'      =>"hidden",
                'value'     => '1'
            ));



        SJFormHelper::button_html('', 'SavePinterestOptions', 'Save ');

        SJFormHelper::form_close_html();
        SJFormHelper::div_close();
    }


    function save_pinterest_options()
    {

        global $share_juice_error, $share_juice_options;

        $pinterest_options = share_juice_validate_pinterest_options();

        if ($share_juice_error->is_severe_error())
        return;

        $share_juice_options['global_pinterest_options'] = $pinterest_options;
        update_option('share-juice-options', $share_juice_options);
        //re - read from db to restore recent value
        $share_juice_options = get_option('share-juice-options');

    }




    /*
    //Content Text Box
    */

    function show_text_box_options()
    {
        global  $share_juice_options;


        if (isset($_POST['process_text_box_options'])) {

            $text_box_options = $_POST;
        }
        else {
            $text_box_options = !empty($share_juice_options['text_box_options'])?$share_juice_options['text_box_options']:array();
        }

        SJFormHelper::heading('h3',"Text Box ");
        SJFormHelper::div_start('admin-global-text-box-options');
        SJFormHelper::form_start_html('admin-global-text-box-options');


        echo '<p>This works only for single pages (or on one post )</p>';

        SJFormHelper::field_input_html(    array(
                'name'                => 'text_before_content_enabled',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'text-before-content-enabled',
                'label'               => _('Show text before content'),
                'get_value_from_array'=>$text_box_options,
                'helper_text'         =>    _('Allows text mentioned below before content share buttons. You can utilize the text for call to action such as "Please share if you like'),

            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'text_box_before_content',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'text-box-before-content',
                'label'               => _('Before Content Text'),
                'get_value_from_array'=> $text_box_options,
                'field_has_html_text' =>true,
                'helper_text'         =>    _('This text is shown above share buttons shown before content of the post')

            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'text_after_content_enabled',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'text-after-content- enabled',
                'label'               => _('Show text after content'),
                'get_value_from_array'=>$text_box_options,
                'helper_text'         =>    _('Allows text mentioned below after content share buttons. You can utilize the text for call to action such as "Please share if you like'),

            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'text_box_after_content',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'text-box-after-content',
                'label'               => _('After Content Text'),
                'get_value_from_array'=> $text_box_options,
                'field_has_html_text' =>true,
                'helper_text'         =>    _('This text is shown above share buttons shown after content of the post')

            ));


        SJFormHelper::field_input_html(    array(
                'name'      => 'process_text_box_options',
                'id'        => 'process-text-box-options',
                'name_array'=> '',
                'type'      =>"hidden",
                'value'     => '1'
            ));

        SJFormHelper::button_html('', 'SaveTextBoxOptions', 'Save ');



        SJFormHelper::form_close_html();
        SJFormHelper::div_close();
    }

    function save_text_box_options()
    {
        global $share_juice_options,$share_juice_error;

        $text_box_options = share_juice_validate_text_box_options();

        if ($share_juice_error->is_severe_error())
        return;

        $share_juice_options['text_box_options'] = $text_box_options;
        update_option('share-juice-options', $share_juice_options);
        //re - read from db to restore recent value
        $share_juice_options = get_option('share-juice-options');


    }

    /*
    //Float bar
    */


    function share_juice_share_juice_show_facebook_like_widget_settings()
    {
        global $share_juice_options;

        $colorscheme_arr = array('light'=>'Light','dark' =>'Dark');



        if (isset($_POST['process_facebook_like_widget_options'])) {

            $facebook_like_widget_options = $_POST;
        }
        else {
            $facebook_like_widget_options = !empty($share_juice_options['facebook_like_widget_options'])
            ?$share_juice_options['facebook_like_widget_options']:
            array();
        }

        SJFormHelper::heading('h3',"Facebook Like Widget ");
        SJFormHelper::div_start('admin-facebook-like-widget-options');
        SJFormHelper::form_start_html('admin-facebook-like-widget-options');
        ?>
        <div class="updated">
            <p>
                <center>
                    Use shortcode below in a sidebar widget or anywhere else to display the like widget
                    <p>
                        <b>
                            [FACEBOOK_LIKE_WIDGET]
                        </b>
                    </p>
                </center>
            </p>
        </div>
        <p>
            For filling up this data please refer
            <a href="https://developers.facebook.com/docs/reference/plugins/like-box/" target="_blank" >
                Facbook Documentation
            </a>
        </p>
        <p>
            <label for="data-href" >
                Facebook Page URL
            </label>
            <input type="text" name="data-href" size="60" value="<?php echo isset($facebook_like_widget_options['data-href']) ? esc_url($facebook_like_widget_options['data-href']):''?>" />
        </p>
        <p>
            <label for="data-width" >
                Width
            </label>
            <input type="text" name="data-width" size="3" value="<?php echo isset($facebook_like_widget_options['data-width'])?intval($facebook_like_widget_options['data-width']):''?>" />
        </p>
        <p>
            <label for="data-height" >
                Height
            </label>
            <input type="text" name="data-height" size="3" value="<?php echo isset($facebook_like_widget_options['data-height'])?intval($facebook_like_widget_options['data-height']):''?>" />
        </p>
        <p>
            <label for="data-colorscheme" >
                Color Scheme
            </label>
            <?php
            $data_colorscheme = isset($facebook_like_widget_options['data-colorscheme'])?$facebook_like_widget_options['data-colorscheme']:'';
            $selectHtml = SJFormHelper::createSelectHTML($colorscheme_arr,$data_colorscheme,'data-colorscheme');

            echo wp_kses($selectHtml,array(
                    'select'=>array(
                        "name"=>array()
                    ),
                    'option'=>array()
                )
            );

            ?>
        </p>
        <p>
            <label for="data-show-faces" >
                Show Faces
            </label>
            <input type="checkbox" name="data-show-faces"  <?php echo isset($facebook_like_widget_options['data-show-faces']) && $facebook_like_widget_options['data-show-faces'] == true? "CHECKED":'';?> />
        </p>
        <p>
            <label for="data-border-color" >
                Border Color
            </label>
            <input type="text" name="data-border-color"value="<?php echo isset($facebook_like_widget_options['data-border-color']) ?esc_attr($facebook_like_widget_options['data-border-color']):''?>" />
        </p>
        <p>
            <label for="data-stream" >
                Show Stream
            </label>
            <input type="checkbox" name="data-stream" <?php echo isset($facebook_like_widget_options['data-stream']) && $facebook_like_widget_options['data-stream'] == true? "CHECKED":'';?> />
        </p>
        <p>
            <label for="data-header" >
                Header
            </label>
            <input type="checkbox" name="data-header" <?php echo isset($facebook_like_widget_options['data-header']) && $facebook_like_widget_options['data-header'] == true? "CHECKED":'';?> />
        </p>
        <input type="hidden" name="process_facebook_like_widget_options" value="1" />
        <?php
        SJFormHelper::button_html('', 'SaveFacebookLikeWidgetOptions', 'Save ');


        SJFormHelper::form_close_html();
        SJFormHelper::div_close();
    }

    function save_facebook_like_widget_options()
    {

        global $share_juice_error, $share_juice_options;

        $facebook_like_widget_options = share_juice_validate_facebook_like_widget_options();

        if ($share_juice_error->is_severe_error())            return;


        $share_juice_options['facebook_like_widget_options'] = $facebook_like_widget_options;
        update_option('share-juice-options', $share_juice_options);
        //read from db to restore right value
        $share_juice_options = get_option('share-juice-options');


    }
    /*
    //Float bar
    */

    function show_global_float_bar_settings()
    {
        global $share_juice_error, $share_juice_options;


        if (isset($_POST['process_floating_box_options'])) {

            $floating_box_options = $_POST;

        }
        else {
            $floating_box_options = !empty($share_juice_options['global_floating_box_options'])?$share_juice_options['global_floating_box_options']:array();

        }

        SJFormHelper::heading('h3',"Floating bar ");
        SJFormHelper::div_start('admin-global-floating-bar-options');
        SJFormHelper::form_start_html('admin-global-floating-bar-options');

        ?>
        <p>
            <label for="floating_box_left_margin" >
                Left Margin :
            </label>
            <input type="text" name="floating_box_left_margin" size="3"
            value="<?php echo isset($floating_box_options['floating_box_left_margin']) ?
            intval($floating_box_options['floating_box_left_margin']) : 0 ?>"/>px
        </p>

        <p>
            <label for="floating_box_bgcolor" >
                Background color(Without #):
            </label>
            <input type="text" name="floating_box_bgcolor" size="3"
            value="<?php echo isset($floating_box_options['floating_box_bgcolor']) ?
            esc_attr($floating_box_options['floating_box_bgcolor']) : 'fff' ?>"/>
        </p>
        <p>
            <label for="floating_box_shadow" >
                Add shadow to box    :
            </label>
            <input type="checkbox" name="floating_box_shadow" size="3"
            <?php echo isset($floating_box_options['floating_box_shadow']) && $floating_box_options['floating_box_shadow'] == TRUE ?  "CHECKED" : ''?>/>
        </p>
        <p>
            <label for="floating_box_shadowcolor" >
                Shadow color (Without #):
            </label>
            <input type="text" name="floating_box_shadowcolor" size="3"
            value="<?php echo isset($floating_box_options['floating_box_shadowcolor']) ?
            esc_attr($floating_box_options['floating_box_shadowcolor']) : '000' ?>"/>
        </p>
        <p>
            <label for="floating_box_textontop" >
                Text on top of float box :
            </label>
            <input type="text" name="floating_box_textontop"
            value="<?php echo isset($floating_box_options['floating_box_textontop']) ?
            esc_attr($floating_box_options['floating_box_textontop']) : 'Share' ?>"/>
        </p>



        <input type="hidden" name="process_floating_box_options" value="1" />
        <?php
        SJFormHelper::button_html('', 'SaveGlobalFloatingOptions', 'Save ');


        SJFormHelper::form_close_html();
        SJFormHelper::div_close();
    }

    function save_global_float_bar_options()
    {

        global $share_juice_error, $share_juice_options;


        $floating_box_options = share_juice_validate_float_box();

        if ($share_juice_error->is_severe_error())
        return;



        $share_juice_options['global_floating_box_options'] = $floating_box_options;
        update_option('share-juice-options', $share_juice_options);
        //read from db to restore right value
        $share_juice_options = get_option('share-juice-options');


    }


    function save_css_file()
    {

        $css_php_inst = new CSSDynamic();
        $css_php_inst->save();
    }


    /*
    // Email
    */
    function show_global_email_options()
    {

        global $share_juice_error,$share_juice_options;
        if (isset($_POST['process_email_options'])) {
            $global_email_options = $_POST;
        }

        else {
            $global_email_options = isset($share_juice_options['global_email_options'])?$share_juice_options['global_email_options']:'';

        }

        SJFormHelper::heading('h3',"Email ");
        SJFormHelper::div_start('admin-global-email-options');

        SJFormHelper::form_start_html('admin-global-email-options');

        SJFormHelper::field_input_html(    array(
                'name'                => 'email_simulation_mode',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'share-juice-email_simulation_mode',
                'label'               => _('Simulation mode'),
                'get_value_from_array'=>$global_email_options,
                'helper_text'         =>    'Check this to allow you to test IP filters',


            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'approve_before_send',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'share-juice-approve-before-send',
                'label'               => _('Approve emails before send'),
                'get_value_from_array'=>$global_email_options,
                'helper_text'         =>    _('Approve emails before sending'),

            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'min_time_before_next_send',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'share-juice-min-time-before-next-send',
                'label'               => _('Time interval in between two emails (in seconds)'),
                'get_value_from_array'=>$global_email_options,
                'helper_text'         =>    'Time interval in between two emails in seconds',
                'size'                =>30
            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'banned_ip',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'share-juice-banned-ip',
                'label'               => _('Banned IPs'),
                'get_value_from_array'=> $global_email_options,
                'helper_text'         =>    'Enter banned IPs, separated by comma list. You can use (*) to ban a complete range. For example 111.* will ban all IPs like 111.122.133.144'

            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'banned_email_domains',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'banned-emails-domains',
                'label'               => _('Banned Domains'),
                'get_value_from_array'=> $global_email_options,
                'helper_text'         =>    'Enter banned Domains separated by comma such as gmail.com,yahoo.com'

            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'email_disclaimer',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'email-disclaimer',
                'label'               => _('Email Disclaimer'),
                'get_value_from_array'=> $global_email_options,
                'helper_text'         => 'Enter email disclaimer',
                'size'                => '40'
            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'email_salt',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'email-salt',
                'label'               => _('Phrase for Unsubscription Token'),
                'get_value_from_array'=>$global_email_options,
                'helper_text'         =>    'Enter a small unique string for increasing randomness of unsubscribe token. Once set please do not change it frequently'
            ));


        SJFormHelper::field_input_html(    array(
                'name'      => 'process_email_options',
                'id'        => 'process-email-options',
                'name_array'=> '',
                'type'      =>"hidden",
                'value'     => '1'
            ));
        SJFormHelper::button_html('', 'SaveGlobalEmailOptions', 'Save ');


        SJFormHelper::form_close_html();
        SJFormHelper::div_close();


    }

    function save_global_email_options()
    {
        global $share_juice_options,$share_juice_error;

        $global_email_options = share_juice_validate_global_email();

        if ($share_juice_error->is_severe_error())
        return;
        $share_juice_options['global_email_options'] = $global_email_options;

        update_option('share-juice-options', $share_juice_options);
        //re - read from db to restore recent value
        $share_juice_options = get_option('share-juice-options');


    }

    function show_author_box_options()
    {

        global $share_juice_options,$share_juice_error;

        if (isset($_POST['process_global_author_box_options'])) {
            $global_author_box_options = $_POST;
        }

        else {
            $global_author_box_options = isset($share_juice_options['global_author_box_options'])?$share_juice_options['global_author_box_options']:'';

        }

        //some defaulting needed
        global $share_juice_authorbox_inst;
        $default_headings_array = $share_juice_authorbox_inst->get_defaultheadings_array();

        if (empty($global_author_box_options['author_heading']))         $global_author_box_options['author_heading'] = $default_headings_array['author'];
        if (empty($global_author_box_options['social_connections_heading']))         $global_author_box_options['social_connections_heading'] = $default_headings_array['social'];
        if (empty($global_author_box_options['posts_by_author_heading']))         $global_author_box_options['posts_by_author_heading'] = $default_headings_array['posts_by'];
        SJFormHelper::heading('h3',"Author Box");
        SJFormHelper::div_start('admin-global_author_box_options');

        SJFormHelper::form_start_html('admin-global_author_box_options');

        SJFormHelper::div_start('','admin-field-grouping');

        SJFormHelper::heading('h4','Position of Author Box');

        $author_box_location_arr = array('BC'=>'Before Content','AC'=>'After Content','NO'=>'Don\'t Show Anywhere');
        SJFormHelper::field_input_html(    array(
                'name'                => 'author_box_location',
                'name_array'          => '',
                'type'                =>"radio",
                'id'                  =>'author-box-location',
                'label'               => _('Author Box Location'),
                'radio_array'         => $author_box_location_arr,
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>_('Choose Author Box location')));

        $author_box_display_type_array = array('simple'   =>'Simple','accordion'=>'Accordion','tabs'     =>'Tabs');

        SJFormHelper::field_input_html(    array(
                'name'                => 'author_box_display_type',
                'name_array'          => '',
                'type'                =>"select",
                'id'                  =>'author_box_display_type',
                'label'               => _('Author Box Display Type'),
                'select_array'        =>$author_box_display_type_array,
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    _('Choose the Author Box Display Type')
            ));

        SJFormHelper::div_close();

        SJFormHelper::div_start('','admin-field-grouping');

        SJFormHelper::heading('h4','Heading Captions');

        SJFormHelper::field_input_html(    array(
                'name'                => 'author_heading',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'author-heading',
                'label'               => _('Heading For Author Box'),
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    'Enter Heading for authorbox',
                'size'                =>30
            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'social_connections_heading',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'social-connections-heading',
                'label'               => _('Heading For Social Connections'),
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    'Enter Heading for Social connections',
                'size'                =>30
            ));
        SJFormHelper::field_input_html(    array(
                'name'                => 'posts_by_author_heading',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'posts-by-author-heading',
                'label'               => _('Heading For Posts By Author'),
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    'Enter Heading for Posts by Author',
                'size'                =>30
            ));

        SJFormHelper::div_close();
        SJFormHelper::div_start('','admin-field-grouping');

        SJFormHelper::heading('h4','Show/Hide following tabs in authorbox');
        SJFormHelper::field_input_html(    array(
                'name'                => 'show_social_tab',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'show-social-tab',
                'label'               => _('Show Social Tab'),
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    'Show social tab in the authorbox tabs',


            ));



        $social_list_direction_array = array('vertical'  =>'Vertical','horizontal'=>'Horizontal');

        SJFormHelper::field_input_html(    array(
                'name'                => 'social_list_direction',
                'name_array'          => '',
                'type'                =>"select",
                'id'                  =>'social-list-direction',
                'label'               => _('Social List Direction'),
                'select_array'        =>$social_list_direction_array,
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    _('Choose to display social links list horizontal or vertical')
            ));

        SJFormHelper::field_input_html(    array(
                'name'                => 'show_posts_by_author_tab',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'show-posts-by-author-tab',
                'label'               => _('Show Posts by author Tab'),
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    'Show Other posts written by the same author in authorbox tabs',


            ));

        SJFormHelper::div_close('','admin-field-grouping');
        SJFormHelper::div_start('','admin-field-grouping');
        SJFormHelper::heading('h4','Author Bio Template');
        echo '<p> Following tags are allowed in the Author Bio template. Press the buttons to insert them into the box </p>';
        global $share_juice_authorbox_inst;
        echo $share_juice_authorbox_inst->get_tags_as_buttons();


        SJFormHelper::field_input_html(    array(
                'name'                => 'author_box_template',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'author-box-template',
                'label'               => _('Author Bio Template'),
                'get_value_from_array'=> $global_author_box_options,
                'helper_text'         =>    _('Create Author Bio Template. Press buttons above to insert them into the box. The value in between the hash are connected to user profile fields')

            ));

        SJFormHelper::button_html('', 'ValidateAuthorBox', 'Validate Tags','share-juice-validate-tags');

        SJFormHelper::button_html('', 'FillDefaultTemplate', 'Fill     DefaultTemplate','share-juice-fill-default-template');

        SJFormHelper::field_input_html(    array(
                'name'                => 'show_for_authors_list',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'show-for-authors-list',
                'label'               => _('Show author boxes for these authors'),
                'get_value_from_array'=>$global_author_box_options,
                'helper_text'         =>    'Authorbox will be shown for these authors. Please enter their user names separated by comma',
                'size'                =>30
            ));

        SJFormHelper::input_hidden_field_html('process_global_author_box_options','process_global_author_box_options',1);
        SJFormHelper::div_close('','admin-field-grouping');


        SJFormHelper::button_html('', 'SaveAuthorBoxOptions', 'Save');


        SJFormHelper::form_close_html();
        SJFormHelper::div_close();




    }

    function save_global_author_box_options()
    {
        global $share_juice_options,$share_juice_error;

        if ($share_juice_error->is_severe_error())
        return;
        //$global_author_box_options
        $share_juice_options['global_author_box_options'] = share_juice_validate_global_author_box_options();
        update_option('share-juice-options', $share_juice_options);
        //re - read from db to restore recent value
        $share_juice_options = get_option('share-juice-options');


    }

    function show_open_graph_tag_options()
    {


        global $share_juice_error,$share_juice_options;
        if (isset($_POST['process_global_open_graph_options'])) {
            $global_open_graph_options = $_POST;
        }

        else {
            $global_open_graph_options = isset($share_juice_options['global_open_graph_options'])?$share_juice_options['global_open_graph_options']:'';
        }
        SJFormHelper::heading('h3',"Open Graph Tags");
        SJFormHelper::div_start('admin-global-open-graph-tags');
        SJFormHelper::form_start_html('admin-global-open-graph-tags');

        echo '<p> This checkbox is not enabled by default. Please read the note that appears upon hovering over the label below and check/uncheck the box as needed </p>';
        SJFormHelper::field_input_html(
            array(
                'name'                => 'add_og_schema_to_head',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'add-og-schema-to-head',
                'label'               => _('Add OG schema to header'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('If checked it will add open graph schema to the head of the blog. If you are using other plugins to add og schema please check in the source code of your web page ( at the first line ) if the schema is being added duplicate. If yes, either uncheck this box or check the settings of the other plugin you are using ( for example comment luv ). Please make sure that only one schema is added'
                )
            )
        );



        SJFormHelper::div_start('','admin-field-grouping');

        SJFormHelper::heading('h3',"Open Graph Tags on Home Page");

        SJFormHelper::field_input_html(
            array(
                'name'                => 'add_tags_on_homepage',
                'name_array'          => '',
                'type'                =>"checkbox",
                'id'                  =>'add-tags-on-homepage',
                'label'               => _('Add tags on homepage'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('If you want Share Juice to output tags on the homepage, you may fill the details below and check this box. The individual posts will have their own open Graph tags in the post '
                )
            )
        );
        SJFormHelper::field_input_html(
            array(
                'name'                => 'share_juice_og_title_home',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'share_juice_og_title_home',
                'label'               => _('Title on homepage'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('If you want Share Juice to output tags on the homepage, you may fill the details below and check this box. The individual posts will have their own open Graph tags in the post '
                ),
                'size'                =>80
            )
        );

        SJFormHelper::field_input_html(
            array(
                'name'                => 'share_juice_og_url_home',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'share_juice_og_url_home',
                'label'               => _('URL of homepage'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('URL of the home page. If it is empty, permalink will be used'),
                'size'                =>80
            )

        );

        SJFormHelper::field_input_html(
            array(
                'name'                => 'share_juice_og_image_url_home',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'share_juice_og_image_url_home',
                'label'               => _('Image URL at homepage'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('Image of the home page. If it is empty, nothing will be output')
                ,
                'size'                =>80
            )
        );

        SJFormHelper::field_input_html(
            array(
                'name'                => 'share_juice_facebook_admin_users',
                'name_array'          => '',
                'type'                =>"text",
                'id'                  =>'facebook-incl-admin-users',
                'label'               => _('Include these users as Admin'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('Enter Facebook userids as Admins that will always be added to OG tags. The userid will help in providing insights to the users for the posts. You can add additional user ids in the post for e.g. to allow them to view insights. Enter user ids separated by commas')
                ,
                'size'                =>80
            )
        );
        SJFormHelper::field_input_html(
            array(
                'name'                => 'share_juice_og_desc_home',
                'name_array'          => '',
                'type'                =>"textarea",
                'id'                  =>'share_juice_og_desc_home',
                'label'               => _('Description of homepage'),
                'get_value_from_array'=> $global_open_graph_options
                ,
                'helper_text'         =>_('Description of homepage. If it is left empty, nothing will be output'
                )
            )
        );


        SJFormHelper::div_close();

        SJFormHelper::input_hidden_field_html('process_global_open_graph_options','process-global-open-graph-options',1);

        SJFormHelper::button_html('', 'SaveOpenGraphOptions', 'Save');

        SJFormHelper::form_close_html();
        SJFormHelper::div_close();

    }

    function save_global_open_graph_options()
    {
        global $share_juice_options,$share_juice_error;

        if ($share_juice_error->is_severe_error())
        return;
        //$global_author_box_options
        $share_juice_options['global_open_graph_options'] = share_juice_validate_global_open_graph_options();
        update_option('share-juice-options', $share_juice_options);
        //re - read from db to restore recent value
        $share_juice_options = get_option('share-juice-options');


    }


    function show_google_authorship_options()
    {

        /*        global $share_juice_error,$share_juice_options;
        if ($share_juice_error->is_severe_error()) {
        $global_google_author_options = $_POST;
        }

        else {
        $global_google_author_options = isset($share_juice_options['global_google_author_options'])?$share_juice_options['global_google_author_options']:'';

        }

        SJFormHelper::heading('h3',"Google Authorship");
        SJFormHelper::div_start('admin-global-google-author');
        SJFormHelper::form_start_html('admin-global-google-author');

        SJFormHelper::field_input_html(    array(
        'name'                => 'enable_google_author_link',
        'name_array'          => '',
        'type'                =>"checkbox",
        'id'                  =>'enable-google-author-link',
        'label'               => _('Enable Google Author Link'),
        'get_value_from_array'=> $global_google_author_options,
        'helper_text'         =>_('When checked it will place following code into your pages / post

        <link rel="author" href="https://[Authors Gplus URL taken from user metadata]" />)')

        ));

        SJFormHelper::field_input_html(    array(
        'name'      => 'process_global_google_author_options',
        'id'        => 'process_global_google_author_options',
        'name_array'=> '',
        'type'      =>"hidden",
        'value'     => '1'
        ));



        SJFormHelper::button_html('', 'SaveGoogleAuthorOptions', 'Save');



        SJFormHelper::form_close_html();
        SJFormHelper::div_close();

        */
    }

    function save_global_google_author_options()
    {
        /*
        global $share_juice_options;

        $temp = array();


        if (isset($_POST['process_global_google_author_options'])) {

        $temp = $_POST;

        }
        else {
        $temp = $share_juice_options['global_author_box_options'];

        }

        $global_author_box_options['enable_google_author_link'] = isset($temp['enable_google_author_link']) ?
        $temp['enable_google_author_link'] : false;

        $share_juice_options['global_google_author_options'] = $global_author_box_options;
        update_option('share-juice-options', $share_juice_options);

        */
    }


}
?>