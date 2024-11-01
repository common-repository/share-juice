<?php
/*--- This file is different than codex version ----*/
class SJAdminButtons
{
    private $post_arr = array();
    public
    function validate_and_generate_forms()
    {
        //declare global
        global $share_juice_error;
        global $wpdb;
        global $share_juice_social_buttons_arr;
        //check if any submit ops
        if (!empty($_POST)) {
            switch ($_POST['submit']) {
                //validate and save
                // returns $_POST to a common array if there is error
                case "ShareButtonDetailsSave":
                //check for the error from validation
                if ($this->validate_and_save() == true) {
                    $url = admin_url('admin.php?page=sj-social-buttons');
                    $share_juice_error->add('I','Social Button added/updated successfully');
                    $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
                    $share_juice_error->add('I', $str);
                    $share_juice_error->show_messages();
                    return;

                }
                else {

                    //if there is any error
                    $share_juice_error->show_messages();
                    $this->show_button_form_details_all();
                    return;
                }
                break;
                //call back all forms
                case "ShareButtonDetailsCancel":
                $url = admin_url('admin.php?page=sj-social-buttons');
                echo "<meta http-equiv='refresh' content='0;url=".esc_url($url)." />";
                //very important
                // no retur here
                //only exit;
                exit;

                break;
                //for edit and delete, submit will have the key attached

                break;
            } //end of switch
        } //end of submit check
        if (isset($_GET['operation'])) {
            //if get paramaters are set
            if ($_GET['operation'] == 'create') {
                //if create
                if (isset($_GET['button_key'])) {
                    //button key is present
                    //but is it valid
                    if (array_key_exists($_GET['button_key'],$share_juice_social_buttons_arr) || $_GET['button_key'] == 'IC') {

                        $this->show_button_form_details_all();
                        return;
                    }
                    else {
                        //the button key not valid
                        //show error
                        $button_str = implode(",",array_keys($share_juice_social_buttons_arr)).",IC";
                        $url        = admin_url('admin.php?page=sj-social-buttons');
                        $share_juice_error->add('E','Button key is not valid. The values need to be one of these '.$button_str);
                        $str        = "Go back to <a href=\"{$url}\">Social Buttons</a>";
                        $share_juice_error->add('E', $str);
                        $share_juice_error->show_messages();
                        return;
                    }

                }
                else {
                    //the button key not present
                    //show error
                    $url = admin_url('admin.php?page=sj-social-buttons');
                    $share_juice_error->add('E','Button key is missing. Parameter button_key');
                    $str = "Go back to <a href=\"{$url}\">Social Buttons</a>";
                    $share_juice_error->add('E', $str);
                    $share_juice_error->show_messages();
                    return;
                }

            }

            elseif ($_GET['operation'] == 'edit') {
                //operation is an edit
                if (!empty($_GET['custom_key'])) {
                    //custom key is present
                    //show forms
                    $this->show_button_form_details_all();

                    return;
                }
                else {
                    // custom key absent
                    // show errors
                    $url = admin_url('admin.php?page=sj-social-buttons');
                    $share_juice_error->add('E','The button you are trying to edit does not exist, custom_key parameter is missing or null');
                    $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
                    $share_juice_error->add('E', $str);
                    $share_juice_error->show_messages();
                    return;
                }
            }
            elseif ($_GET['operation'] == 'delete') {
                // if delete
                if (isset($_GET['custom_key'])) {
                    //custom key available
                    //delete this
                    $this->delete_button();
                    return;


                }
                else {
                    //custom key not available
                    //show error
                    $url = admin_url('admin.php?page=sj-social-buttons');
                    $share_juice_error->add('E','The button you are trying to delete does not exist, custom_key parameter missing');
                    $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
                    $share_juice_error->add('E', $str);
                    $share_juice_error->show_messages();
                    return;
                }
            }
            else {
                //operation variable has unrecognizable value
                $url = admin_url('admin.php?page=sj-social-buttons');
                $share_juice_error->add('E','The operation can have only three values create,edit and delete. Either the value is missing or is incorrect');
                $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
                $share_juice_error->add('E', $str);
                $share_juice_error->show_messages();
                return;
            }
        }

        //if the execution has reached this
        //it has failed all if's
        //or it would have returned
        // and is probably the main page
        $this->show_main_page_form();
        return;
    }


    function show_main_page_form()
    {

        SJFormHelper::div_start("admin");
        share_juice_admin_header('Social Buttons');
        SJFormHelper::div_start("accordion");
        //to show the create button forms
        //for social buttons
        $this->show_create_buttons_form();
        //show table
        $this->show_buttons_table();
        SJFormHelper::div_close("accordion");
        SJFormHelper::div_close("admin");

    }


    function show_create_buttons_form()
    {
        SJFormHelper::heading('h3',"Create Social Buttons");
        SJFormHelper::div_start('admin-global-general-options');
        /*  share_juice_show_video_links(
        array('https://www.youtube.com/watch?v=_zWkG9-6i3o'=>'Social Button Configuration 1/3',
        'https://www.youtube.com/watch?v=Ef8nkQEw9uI'=>'Social Button Configuration 2/3',
        'https://www.youtube.com/watch?v=m_JvluNRvp4'=>'Social Button Configuration 1/3'));
        */
        //social buttons
        $this->show_social_button_create_form();
         SJFormHelper::div_close();


    }

    function show_social_button_create_form()
    {
        global $share_juice_social_buttons_arr;
        $action_url = $_SERVER['REQUEST_URI'];

        SJFormHelper::show_form_start_html($action_url,'get','','','share-juice-form');

        //there is a hidden field called as page
        //when you submit the form ( which posts by 'get' )
        //page is attached as page = 'sj - social - buttons' as get param to url
        //and thus right page is called
        // Good trick from stackoverflow site
        $page       = sanitize_text_field($_GET['page']);
        ?>

        <input type="hidden" name="page" value="<?php echo esc_attr($page) ?>"/>

        <div class="share-juice-admin-label-and-field">
            <label for="key">
                Create Social Button:
            </label>
            <?php $selectHtml = SJFormHelper::createSelectHTML($share_juice_social_buttons_arr, '',
                'button_key');
            echo $selectHtml;
            ?>
        </div>
        <button type="submit" name="operation" value="create">
            Create
        </button>
        </form>
        <?php

    }
    //show button table

    function show_buttons_table($show_edit_del_button = true)
    {
        ob_start();
        global $wpdb, $share_juice_social_buttons_arr, $share_juice_button_content_location_arr, $sj_table_name;
        $buttons = $wpdb->get_results("SELECT * FROM {$sj_table_name} ", ARRAY_A);
        //if no button found return;
        if (empty($buttons))            return;
        //HTML controls
        SJFormHelper::heading('h3',"List of existing Social Buttons");
        SJFormHelper::div_start('admin-global-general-options');


        ?>
        <table>
            <thead>
                <tr>
                    <th>
                        Your button name
                    </th>
                    <th>
                        Button Type
                    </th>
                    <th>
                        Floating Active?
                    </th>
                    <th>
                        Show Before Content
                    </th>
                    <th>
                        Show After Content
                    </th>
                    <th>
                        Normal Order
                    </th>
                    <th>
                        Floating Order Number
                    </th>
                    <th>
                        Show On Home
                    </th>
                    <th>
                        Show In Post
                    </th>
                    <th>
                        Show On Page
                    </th>
                    <th>
                        Show On Archive
                    </th>
                    <?php
                    if ($show_edit_del_button == true) {
                        ?>
                        <th>
                        </th>
                        <th>
                        </th>
                        <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($buttons as $button) {
                    ?>
                    <tr>
                        <td>
                            <?php echo esc_html($button['custom_key']) ?>
                        </td>
                        <td>
                            <?php echo  esc_html($share_juice_social_buttons_arr[$button['button_key']]) ?>
                        </td>
                        <td >
                            <?php echo $button['button_floating_active'] == true ? "Yes" : "No"; ?>
                        </td>
                        <td>
                            <?php echo $button['button_location_before_content'] == true ? "Yes" :
                            "No"; ?>
                        </td>
                        <td>
                            <?php echo $button['button_location_after_content'] == true ? "Yes" :
                            "No"; ?>
                        </td>
                        <td class="share-juice-number">
                            <?php echo intval($button['button_normal_order']); ?>
                        </td>
                        <td class="share-juice-number">
                            <?php echo intval($button['button_floating_order']); ?>
                        </td>
                        <td>
                            <?php echo $button['button_show_on_home'] == true ? "Yes" : "No"; ?>
                        </td>
                        <td>
                            <?php echo $button['button_show_in_post'] == true ? "Yes" : "No"; ?>
                        </td>
                        <td>
                            <?php echo $button['button_show_on_page'] == true ? "Yes" : "No"; ?>
                        </td>
                        <td>
                            <?php echo $button['button_show_on_archive'] == true ? "Yes" : "No"; ?>
                        </td>
                        <?php
                        if ($show_edit_del_button == true) {
                            ?>
                            <td>
                                <a href = "<?php echo esc_url($_SERVER['REQUEST_URI']).
                "&operation=edit&custom_key=".$button['custom_key'];?>">
                                    Edit
                                </a>
                            </td>
                            <td>
                                <a href = "<?php echo esc_url($_SERVER['REQUEST_URI']).
                "&operation=delete&custom_key=".$button['custom_key'];?>" class="delete-prompt">
                                    Delete
                                </a>
                            </td>
                            <?php
                        } ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
        //SJFormHelper::form_close_html();
        echo ob_get_clean();
        SJFormHelper::div_close();

    }
    //show details form

    function show_button_form_details_all()
    {
        global $share_juice_error, $sj_table_name;
        $post_arr = array();
        //if there is a request for edit then read it from db

        if (isset($_POST['process'])) {
            $post_arr = $this->post_arr;
            //button config is in serialized form only
            $post_arr['button_config'] = $this->post_arr['button_config'];
        }

        elseif ($_GET['operation'] == 'edit') {
            // if edit is requested , then read from db
            global $wpdb;
            //$key_array = explode('_', $_POST['submit']);
            $custom_key = $_GET['custom_key'];
            // create sql and fire to get

            $sql        = "select * from {$sj_table_name} where custom_key = %s";

            $sql_prep   = $wpdb->prepare($sql,$custom_key);
            $result     = $wpdb->get_results($sql_prep,ARRAY_A);
            if (empty($result)) {

                $url = admin_url('admin.php?page=sj-social-buttons');
                $share_juice_error->add('E',"The button \"{$custom_key}\" you are trying to edit  does not exist");
                $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
                $share_juice_error->add('I', $str);
                $share_juice_error->show_messages();
                exit;
            }
            $post_arr = $result[0];
        }


        SJFormHelper::div_start("admin");
      
        SJFormHelper::div_start("accordion");

        if ($_GET['operation'] == "create")        SJFormHelper::heading('h3',"Create");
        else
        if ($_GET['operation'] == "edit")        SJFormHelper::heading('h3',"Edit");

        SJFormHelper::div_start('admin-global-general-options');

        SJFormHelper::form_start_html('','','share-juice-form', '');
        // show detail form functions
        $this->show_standard_options_details_form($post_arr);
        SJFormHelper::div_start('',"admin-field-grouping");

        $this->show_custom_admin_options_details_form($post_arr);
        SJFormHelper::div_close("share-juice-admin-grouping");

        //show save cancel buttons
        $this->show_option_details_save_cancel_buttons();
        SJFormHelper::form_close_html();

        SJFormHelper::div_close();

        //show button table
        $this->show_buttons_table(false);
        //closing form controls

        SJFormHelper::div_close("accordion");
        SJFormHelper::div_close("admin");
    }
    // Show Standard options form on the details

    function show_standard_options_details_form($post_arr)
    {
        //all global variables
        global $share_juice_button_content_location_arr, $share_juice_social_buttons_arr;
        //button key will get the value from $_GET if there is an edit
        //it will be taken from post_arr ( which is filled in the function calling this fn)

        $button_key = '';
        if ($_GET['operation'] == 'create')            $button_key = $_GET['button_key'];
        else
        if ($_GET['operation'] == 'edit')            $button_key = $post_arr['button_key'];
        //start the output
        ob_start();
        if (isset($post_arr['custom_key']))            echo "<div class=\"updated\"><p><b>Note:</b>You can show this button individually anywhere in your blog(for example in a widget or post) by using the short code<br/><b><center> [SJBUTTON button_name=\"".esc_html($post_arr['custom_key'])."\"]</center></b></p></div>";
        ?>
        <div class="share-juice-admin-field-grouping">

            <h4>
                Basic Customization Options
            </h4>

            <input name="id" type="hidden" readonly value="<?php echo !empty($post_arr['id']) ?
            esc_attr($post_arr['id']) : '' ?>"/>

            <input name="button_key" type="hidden" readonly value="<?php echo esc_attr($button_key) ;?>"/>



            <div class="share-juice-admin-label-and-field">
                <label for="key_description">
                    Button Type:
                </label>
                <input name="key_description" type="text" readonly value="<?php
                    echo esc_attr($share_juice_social_buttons_arr[$button_key]);
                ?>"/>
                <span class="helper">
                    The description of share button that you are creating. You don't have to enter any value here
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="custom_key">
                    Your button name:
                </label>
                <input name="custom_key" type="text" value="<?php echo !empty($post_arr['custom_key']) ?
                esc_attr($post_arr['custom_key']) : ''; ?>"/>
                <span class="helper">
                    You can create button with different configuration. This name helps you identify which button contains which configuration. Just give it a name of choice like "Twitter_horizontal" or "Facebook_like_dark_scheme". You can enter upto 50 characters as button name"
                </span>
            </div>
            <input type="hidden" name="custom_key_original" value="<?php
            if (!empty($_POST['custom_key_original']))            echo esc_attr(sanitize_text_field($_POST['custom_key_original']));
            else
            if (!empty($post_arr['custom_key']))            echo esc_attr($post_arr['custom_key']);
            ?>"/>


            <h4>
                Button Type and Order
            </h4>

            <div class="share-juice-admin-label-and-field">
                <label for="button_floating_active">
                    Button show in Floating Bar?
                </label>
                <input name="button_floating_active" type="checkbox" value=""
                <?php echo isset($post_arr['button_floating_active']) && ($post_arr['button_floating_active'] ==
                    1) ? "CHECKED" : ''; ?>
                />

                <span class="helper">
                    This will enable the button for floating bar and it will be shown in the floating bar. Please note that floating bar will only appear in posts and pages and not in home and archive pages
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_location_before_content">
                    Show before content ?
                </label>
                <input name="button_location_before_content" type="checkbox" value=""
                <?php echo isset($post_arr['button_location_before_content']) && ($post_arr['button_location_before_content'] ==
                    1) ? "CHECKED" : ''; ?>/>
                <span class="helper">
                    This button will be enabled in the before content section
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_location_after_content">
                    Show after content ?
                </label>
                <input name="button_location_after_content" type="checkbox" value=""
                <?php echo isset($post_arr['button_location_after_content']) && ($post_arr['button_location_after_content'] ==
                    1) ? "CHECKED" : ''; ?>/>
                <span class="helper">
                    This button will be enabled in the after content section
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_normal_order">
                    Content Button Order:
                </label>
                <input name="button_normal_order" type="text" size="3"
                value="<?php echo !empty($post_arr['button_normal_order']) ? intval($post_arr['button_normal_order']) :
                1 ?> "/>
                <span class="helper">
                    Buttons in content sections will be ordered by this number. It takes value from 0 till 100
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_floating_order">
                    Floating Button Order:
                </label>
                <input name="button_floating_order" type="text" size="3"
                value="<?php echo !empty($post_arr['button_floating_order']) ? intval($post_arr['button_floating_order']) :
                1 ?> "/>
                <span class="helper">
                    Buttons in floating bar will be ordered by this number. It takes value from 0 till 100
                </span>
            </div>

            <h4>
                Show on Home, Post etc.
            </h4>
            <div class="share-juice-admin-label-and-field">
                <label for="button_show_on_home">
                    Show On Home:
                </label>
                <input name="button_show_on_home" type="checkbox" value=""
                <?php echo isset($post_arr['button_show_on_home']) && ($post_arr['button_show_on_home'] ==
                    1) ? "CHECKED" : ''; ?>/>
                <span class="helper">
                    This button will be shown on home page ( except that floating bar will not be shown on home page)
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_show_in_post">
                    Show In Post:
                </label>
                <input name="button_show_in_post" type="checkbox" value=""
                <?php echo isset($post_arr['button_show_in_post']) && ($post_arr['button_show_in_post'] ==
                    1) ? "CHECKED" : ''; ?>/>
                <span class="helper">
                    This button will be shown on post page if Button Floating and Content options are selected
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_show_on_page">
                    Show On Page:
                </label>
                <input name="button_show_on_page" type="checkbox" value=""
                <?php echo isset($post_arr['button_show_on_page']) && ($post_arr['button_show_on_page'] ==
                    1) ? "CHECKED" : ''; ?>/>
                <span class="helper">
                    This button will be shown on pages if Button Floating and Content options are selected
                </span>
            </div>
            <div class="share-juice-admin-label-and-field">
                <label for="button_show_on_archive">
                    Show On Archive:
                </label>
                <input name="button_show_on_archive" type="checkbox" value=""
                <?php echo isset($post_arr['button_show_on_archive']) && ($post_arr['button_show_on_archive'] ==
                    1) ? "CHECKED" : ''; ?>/>
                <span class="helper">
                    This button will be shown on archives if Button Floating and Content options are selected
                </span>
            </div>
            <?php
            //if key = IC add the fields below
            if ($button_key == "IC") {
                ?>
                <div class="share-juice-admin-label-and-field">
                    <label for="16px_file_exists">
                        16px file exists?
                    </label>
                    <input name="16px_file_exists" type="checkbox" disabled="disabled"  value=""
                    <?php echo isset($post_arr['16px_file_exists']) && ($post_arr['16px_file_exists'] == true) ?
                    "CHECKED" : ''; ?>/>
                    <span class="helper">
                        This indicates if there is a valid image file size 16px * 16px. This is set automatically. You don't have to do anything
                    </span>
                </div>
                <div class="share-juice-admin-label-and-field">
                    <label for="32px_file_exists">
                        32px file exists?
                    </label>
                    <input name="32px_file_exists" type="checkbox" value="" disabled="disabled"
                    <?php echo isset($post_arr['32px_file_exists']) && ($post_arr['32px_file_exists'] == true) ?
                    "CHECKED" : ''; ?>/>
                    <span class="helper">
                        This indicates if there is a valid image file size 32px * 32px. This is set automatically. You don't have to do anything
                    </span>
                </div>
                <div class="share-juice-admin-label-and-field">
                    <label for="64px_file_exists">
                        64px file exists?
                    </label>
                    <input name="64px_file_exists" type="checkbox" value="" disabled="disabled"
                    <?php echo isset($post_arr['64px_file_exists']) && ($post_arr['64px_file_exists'] == true) ?
                    "CHECKED" : ''; ?>/>
                    <span class="helper">
                        This indicates if there is a valid image file size 64px * 64px. This is set automatically. You don't have to do anything
                    </span>
                </div>
                <input type="hidden" name="process_buttons" value="1" />
                <?php
            }
            ?>
            <input type="hidden" name="process" value="1"/>

        </div>
        <?php

        //print what is collected in buffer
        echo ob_get_clean();
    }
    //custom admin form details per button

    function show_custom_admin_options_details_form($post_arr = '')
    {
        global $share_juice_class_map_arr, $share_juice_social_buttons_arr;
        //same logic as above
        if ($_GET['operation'] == 'create')            $button_key = $_GET['button_key'];
        else
        if ($_GET['operation'] == 'edit')            $button_key    = $post_arr['button_key'];

        $button_config = isset($post_arr['button_config']) ? $post_arr['button_config'] :'';
        //get the corresponding class
        $class_name      = $share_juice_class_map_arr[$button_key];
        // form controls
        $key_description =  $share_juice_social_buttons_arr[$button_key];

        SJFormHelper::heading('h4',$key_description." customization Options");
        // calling functions of class
        //for compatibility to php < 5.0 needed to use a function
        echo call_user_func(array($class_name,'get_customization_options_HTML'),$button_config);
        //form control close

    }

    function show_option_details_save_cancel_buttons()
    {
        ob_start();
        ?>
        <button type="submit" name="submit" value="ShareButtonDetailsSave">
            Save Options
        </Button>
        <button type="submit" name="submit" value="ShareButtonDetailsCancel">
            Cancel
        </Button>
        <?php
        echo ob_get_clean();
    }
    //validate the input

    function validate_and_save()
    {
        global $share_juice_error;
        $arr = array();
        $this->post_arr = $this->validate_standard_config();
        $button_config_arr = $this->validate_button_config();
        //additional filling for IC fields
        if ($this->post_arr['button_key'] == 'IC') {
            $this->post_arr['16px_file_exists'] = !empty($button_config_arr['path']['path_to_16px_file']) ? true : false;
            $this->post_arr['32px_file_exists'] = !empty($button_config_arr['path']['path_to_32px_file']) ? true : false;
            $this->post_arr['64px_file_exists'] = !empty($button_config_arr['path']['path_to_64px_file']) ? true : false;
        }
        //serialize
        $this->post_arr['button_config'] = maybe_serialize($button_config_arr);
        //if there is no error
        if ($share_juice_error->is_severe_error() === false) {
            $ret = $this->save();
            return true;
        }
        else {
            return false;
        }
    }

    function validate_standard_config()
    {
        global $share_juice_button_content_location_arr, $share_juice_social_buttons_arr, $share_juice_error, $sj_table_name,
        $wpdb;
        $arr = array();
        //custom key
        //length check
        if (strlen($_POST['custom_key']) < 3 || strlen($_POST['custom_key']) > 30)            $share_juice_error->add('E', "Length of \"Your button name\" needs to be between 3 and 30");
        //character,numeric , underscore checking
        if (!preg_match("/^[a-zA-Z0-9_]*$/",$_POST['custom_key']))            $share_juice_error->add('E', "Only alphabets, numbers and underscores allowed in button name");

        //check if the key already exists
        $check_unique = false;

        if ($_GET['operation'] == 'edit') {


            if ($_POST['custom_key_original'] != $_POST['custom_key'])                $check_unique = true;
        }
        else
        if ($_GET['operation'] == 'create') {
            $check_unique = true;
        }
        if ($check_unique == true) {

            $custom_key = $_POST['custom_key'];

            $sql        = "select * from {$sj_table_name} where custom_key = %s";
            $sql_prep   = $wpdb->prepare($sql,$custom_key);
            $result     = $wpdb->get_results($sql_prep,ARRAY_A);


            if (!empty($result)) {


                $share_juice_error->add('E', "The custom key already exist , enter a different key");

            }
        }

        //normal order should be between 1 and 100
        if ($_POST['button_normal_order'] < 1 || $_POST['button_normal_order'] > 100)            $share_juice_error->add('E', "Content Button Order value should be between 1 and 100");
        //floating order same as normal
        if ($_POST['button_floating_order'] < 1 || $_POST['button_floating_order'] > 100)            $share_juice_error->add('E', "floating Button Order value should be between 1 and 100");

        //if($share_juice_error->is_severe_error())
        //    return ;

        //id
        $arr['id'] = isset($_POST['id']) ? $_POST['id'] : '';
        //button_key
        $arr['button_key'] = isset($_POST['button_key'])? $_POST['button_key']:'';
        //custom_key
        $arr['custom_key'] = isset($_POST['custom_key'])? $_POST['custom_key']:'';
        //checkboxes
        $arr['button_floating_active'] = isset($_POST['button_floating_active']) ?true : false;
        //order numbers
        $arr['button_normal_order'] = $_POST['button_normal_order'];
        $arr['button_floating_order'] = $_POST['button_floating_order'];
        //location in content
        $arr['button_location_before_content'] = isset($_POST['button_location_before_content']) ? true : false;
        $arr['button_location_after_content'] = isset($_POST['button_location_after_content']) ? true : false;
        //visibility on post,home, page etc
        $arr['button_show_on_home'] = isset($_POST['button_show_on_home']) ? true : false;
        $arr['button_show_in_post'] = isset($_POST['button_show_in_post']) ? true : false;
        $arr['button_show_on_page'] = isset($_POST['button_show_on_page']) ? true : false;
        $arr['button_show_on_archive'] = isset($_POST['button_show_on_archive']) ? true : false;
        //return validated array
        return $arr;
    }

    function validate_button_config()
    {
        global $share_juice_class_map_arr;
        //get the class name from button_key
        $class_name = $share_juice_class_map_arr[$_POST['button_key']];
        //call custom class for validation
        $arr        = call_user_func_array(array($class_name,'validate_button_configuration'),
            array());
        return $arr;
    }

    function save()
    {
        global $wpdb, $share_juice_error, $sj_table_name, $share_juice_options;


        $rows_affected    = '';
        $result           = '';

        //these fields will have cascading changes on
        //groups which use these keys
        $update_requested = false;
        $insert_requested = false;

        if ($_GET['operation'] == 'create') {
            //unique checking has already been done in validation
            //so just insert here
            $rows_affected = $wpdb->insert($sj_table_name, $this->post_arr);

        }
        elseif ($_GET['operation'] == 'edit') {
            //we have to use id as custom_key is allowed to be changed
            $id            = $this->post_arr['id'];
            $rows_affected = $wpdb->update($sj_table_name, $this->post_arr, array('id'=> $id));

        }

        // in insert or update do this
        share_juice_empty_w3t_cache();
        return true;

    }



    function get_key($post_arr)
    {

        global $share_juice_social_buttons_arr,$share_juice_error;

        if (isset($_GET['button_key']) && (array_key_exists($_GET['button_key'],$share_juice_social_buttons_arr) || $_GET['button_key'] == "IC")) {
            return $_GET['button_key'];
        }

        elseif (isset($_POST['button_key']))            return $_POST['button_key'];

        else {
            $url = admin_url('admin.php?page=sj-social-buttons');
            $share_juice_error->add('E','This key does not exist');
            $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
            $share_juice_error->add('I', $str);
            $share_juice_error->show_messages();
            exit;
        }






        update_option('share-juice-options', $share_juice_options);
        $share_juice_options = get_option('share-juice-options');
    }


    function delete_button()
    {
        global $share_juice_options,$share_juice_error;
        //if there is a request for edit then read it from db

        global $wpdb, $sj_table_name;
        $custom_key = $_GET['custom_key'];
        $table_name = $wpdb->prefix . 'share_juice';
        $sql        = "select * from {$sj_table_name} where custom_key = %s ";
        $sql_prep   = $wpdb->prepare($sql,$custom_key);
        $select_arr = $wpdb->get_results($sql_prep, ARRAY_A);

        if (empty($select_arr)) {
            $url = admin_url('admin.php?page=sj-social-buttons');
            $share_juice_error->add('E','The button you are trying to delete does not exist or it may have been already deleted');
            $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
            $share_juice_error->add('E', $str);
            $share_juice_error->show_messages();
            return;
        }

        $sql = "delete from {$sj_table_name} where custom_key = \"{$custom_key}\"";
        $arr = $wpdb->get_results($sql, ARRAY_A);
        $this->delete_key_from_group($custom_key);

        $url = admin_url('admin.php?page=sj-social-buttons');
        $share_juice_error->add('I',"Button {$custom_key} deleted");
        $str = "Go back to <a href=\"{$url}\">Social Button Main Screen</a>";
        $share_juice_error->add('I', $str);
        $share_juice_error->show_messages();

    }


}
?>