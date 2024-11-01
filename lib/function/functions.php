<?php

/* List all the add actions here*/
//Need to add function exists ?

//clear w3tc on update of SJP options
//call in this order only
add_action('update_option_share-juice-options', 'share_juice_empty_w3t_cache');
//show message on emptying of cachec
add_action('update_option_share-juice-options', 'share_juice_show_cache_emptied_message');
//call order ends

//Show message on updation of SJP options
//May be causing activation errors
//add_action('update_option_share - juice - options', 'share_juice_show_options_updated');
//This shows settings button on the plugin listing
add_filter( "plugin_action_links_share-juice/index.php", 'share_juice_plugin_add_settings_link' );
//To enable shortcode in sidebar widgets  (FB like)
add_filter('widget_text', 'do_shortcode');
//FB Like widget shortcode
add_shortcode('FACEBOOK_LIKE_WIDGET', 'share_juice_show_facebook_like_widget');
//Add script for like widget
add_action('wp_footer', 'share_juice_add_facebook_like_widget_js');
//Capture activation errors
add_action('activated_plugin', 'share_juice_save_error');

/*
*
* Clear all W3 Total Cache
*
*/

function share_juice_empty_w3t_cache()
{

    // Clear all W3 Total Cache
    /*

    if (class_exists('W3_Plugin_TotalCacheAdmin')) {

    $plugin_totalcacheadmin = & w3_instance('W3_Plugin_TotalCacheAdmin');

    $plugin_totalcacheadmin->flush_all();


    }
    */

}

/*
*
* Show w3tc cleared message
*
*/
function share_juice_show_cache_emptied_message()
{


    if (class_exists('W3_Plugin_TotalCacheAdmin')) {
        //cache must have already been cleared
        //because it was called in the order above
        echo __('<div class="updated"><p>All <strong>W3 Total Cache</strong> caches successfully emptied.</p></div>');

    }


}

/*
*
* Is this neccessary?
*
*/
// IMPROV
function share_juice_show_options_updated()
{

    echo '<div class="updated"><p>Options updated</p></div>';

}

/*
*
* Display like widget esp in sidebar
*
*/


function share_juice_show_facebook_like_widget()
{

    global $share_juice_options;

    $data_href = isset($share_juice_options['facebook_like_widget_options']['data-href'])?$share_juice_options['facebook_like_widget_options']['data-href']:'';

    $data_width = isset($share_juice_options['facebook_like_widget_options']['data-width'])? $share_juice_options['facebook_like_widget_options']['data-width']:'';

    $data_height= isset($share_juice_options['facebook_like_widget_options']['data-height'])?$share_juice_options['facebook_like_widget_options']['data-height']:'';

    $str = '<div id ="share-juice-fb-like-box"><div class="fb-like-box" ';

    $str .= 'data-href="' . esc_url($data_href) .
    '" ';

    if (!empty($share_juice_options['facebook_like_widget_options']['data-width']))        $str .= 'data-width="' .intval($data_width) .'" ';
    ;

    if (!empty($share_juice_options['facebook_like_widget_options']['data-height']))        $str .= 'data-height="' . intval($data_height) .
    '" ';
    ;

    if (isset($share_juice_options['facebook_like_widget_options']['data-colorscheme']))
    if ($share_juice_options['facebook_like_widget_options']['data-colorscheme'] !=
        "light")        $str .= 'data-colorscheme="' . esc_html($share_juice_options['facebook_like_widget_options']['data-colorscheme']) .
    '" ';

    $data_show_faces = isset($share_juice_options['facebook_like_widget_options']['data-show-faces'])&& $share_juice_options['facebook_like_widget_options']['data-show-faces'] == true ?
    "true" : "false";
    $str .= 'data-show-faces="' . $data_show_faces . '" ';

    if (!empty($share_juice_options['facebook_like_widget_options']['data-border-color']))        
    $str .= 'data-border-color="' . $share_juice_options['facebook_like_widget_options']['data-border-color'] .
    '" ';
    ;

    $data_stream = isset($share_juice_options['facebook_like_widget_options']['data-stream']) && $share_juice_options['facebook_like_widget_options']['data-stream'] == true ?
    "true" : "false";
    $str .= 'data-stream="' . $data_stream . '" ';

    $data_header = isset($share_juice_options['facebook_like_widget_options']['data-header']) && $share_juice_options['facebook_like_widget_options']['data-header'] == true ?
    "true" : "false";

    $str .= 'data-header="' . $data_header . '" ';

    $str .= '></div></div>';

    // IMPROV
    if ((isset($share_juice_options['admin_options']['script_next_to_buttons']) && $share_juice_options['admin_options']['script_next_to_buttons'] == true) &&
        (isset($share_juice_options['admin_options']['disable_js_echo']) && $share_juice_options['admin_options']['disable_js_echo'] == true))        $str .= share_juice_add_facebook_like_widget_js(0);
    return $str;


}

/*
*
* Add FB JS
*
*/
// IMPROV
//check if not already added
// WHat if appid is not added
function share_juice_add_facebook_like_widget_js($echo = '')
{
    global $share_juice_options;


    if (isset($share_juice_options['admin_options']['disable_js_echo']) && $share_juice_options['admin_options']['disable_js_echo'] == true) {
        return;
    }

    ob_start();

    ?>
    <div id="fb-root">
    </div>
    <script type="text/javascript">
        (function(d, s, id)
            {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php

                echo $share_juice_options['admin_options']['facebook_appid']

                ?>";;
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
    </script><?php

    if (empty($echo))      echo ob_get_clean();
    else      return ob_get_clean();
}

/*
*
* Get short URLs
*
*/

function share_juice_get_short_url($url)
{

    // This needs to be called as many times because
    // THere is no place to store these URLs in blog
    // IMPROV: Find a place to store them or reduce calls
    // It can create problem when there is home screen

    global $share_juice_options;

    //bitly
    if ($share_juice_options['admin_options']['url_sht_service'] == 'bitly')        $short_url = share_juice_get_bitly_url($url);

    //isgd
    if ($share_juice_options['admin_options']['url_sht_service'] == 'isgd')        $short_url = share_juice_get_isgd_url($url);

    //tiny url
    if ($share_juice_options['admin_options']['url_sht_service'] == 'tinyurl')        $short_url = share_juice_get_tinyurl_url($url);

    return $short_url;

}

/*
*
* isgd
*
*/

function share_juice_get_isgd_url($url)
{

    $ch       = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://is.gd/create.php?format=simple&url={$url}");

    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


    $response = curl_exec($ch);

    curl_close($ch);


    return $response;

}


/*
*
* tinyurl
*
*/
function share_juice_get_tinyurl_url($url)
{


    $ch       = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://tinyurl.com/api-create.php?url={$url}");

    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


    $response = curl_exec($ch);

    curl_close($ch);


    return $response;

}

/*
*
* bitly
*
*/
function share_juice_get_bitly_url($url)
{


    global $share_juice_options;


    $login = trim($share_juice_options['admin_options']['bitly_login'], ' ');

    $apikey= trim($share_juice_options['admin_options']['bitly_apikey'], ' ');


    $query = array("version"=> "3","longUrl"=> $url,"login"  => $login,"apiKey" =>
        $apikey);

    $query    = http_build_query($query);


    $ch       = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://api.bit.ly/shorten?" . $query);

    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    curl_close($ch);

    $response = json_decode($response);



    if ($response->errorCode == 0 && $response->statusCode == "OK") {

        return $response->results->
        {
            $url
        }->shortUrl;

    }
    else {

        return null;

    }


}

/*
*
* call print template
*
*/
function share_juice_get_template_print()
{
    //require_once('C:\xampp\htdocs\wordpress\wp - includes\pluggable.php');
    require_once(SJ_PLUGIN_DIR_BASE.'/template/print_template.php');
    //nothing else to be printed after print template
    exit(0);

}


/*
*
* save activation errors to an option
*
*/
function share_juice_save_error()
{
    update_option('share_juice_activation_error', ob_get_contents());
}


//print_r(get_option('sj_activation_error'));

/*
*
* print admin notices
*
*/
// IMPROV
// No usage of it till now has been found

function share_juice_common_admin_notices()
{
    global $share_juice_upgrade_inst;



    if (!share_juice_is_admin_page()) return;

    $share_juice_options = get_share_juice_options();
    //    $share_juice_upgrade_inst->installation_admin_notices();

    //var_dump($share_juice_options);
    //notice from Facebook app
    if (empty($share_juice_options['admin_options']['facebook_appid'])
        && !(isset($_POST['facebook_appid']) && $_POST['facebook_appid'] != '')) {
        $url = '<a href="'.admin_url().'admin.php?page=sj-admin-settings'.'"> Share Juice Admin </a>';

        share_juice_notice(SJMessage::get_message_from_string(14,'general',$url));
    }



}
function share_juice_notice($notice,$type = '')
{
    echo '<div class="updated">';
    echo '<p>';
    echo  wp_kses($notice,wp_kses_allowed_html('data'));
    echo '</p>';
    echo '</div>';
}
/*
*
* Check if the admin pages belong to share juice
*
*/
// IMPROV
// Without this function there were some problems in accordions by wordpress

function share_juice_is_admin_page()
{
    // pagenow has the current page URL part
    global $pagenow;
    // If !admin page
    if ($pagenow != "admin.php")
    return false;
    //Share Juice Page slug array
    $page_array = array(
        'sj-social-buttons',
        'sj-global-settings',
        'sj-icon-groups',
        'sj-admin-settings',
        'sj-css-edit'
    );

    // if the page value of URL param is in the array
    if (in_array($_GET['page'],$page_array))
    return true;
    else
    return false;
}
function share_juice_is_post_page()
{
    global $pagenow;
    if ($pagenow != "post.php") return false;
    else return true;
}
/*
*
* Add settings link to the plugin listing page
* The action has been added at the top
*
*/
function share_juice_plugin_add_settings_link( $links )
{
    $settings_link = '<a href="admin.php?page=sj-global-settings">Settings</a>';
    array_push( $links, $settings_link );
    return $links;
}

/*
*
* Add admin header common to the admin pages
*
*/
function share_juice_admin_header($page_heading)
{

    $plugin_data = get_plugin_data( SJ_PLUGIN_DIR_BASE.'index.php');
    SJFormHelper::div_start('admin-header');
    ?>
    <div id="share-juice-admin-header-wrap">
        <div id="share-juice-admin-heading">
            <h2>
                <?php echo esc_html($page_heading) ?>
            </h2>

        </div>
        <div id="share-juice-admin-logo">

            <h4>
                Share Juice
            </h4>
        </div>
    </div>
    <div id="share-juice-admin-version-info">
        <span class="share-juice-admin-version-text">
            <?php _e('You are working with version :') ?>
        </span>
        <span class="share-juice-admin-version">
            <?php echo esc_html($plugin_data['Version'])?>
        </span>
    </div>

    <?php
    SJFormHelper::div_close('admin-header');
}

/*
*
* Add video links to sections
*
*/
// IMPROV
// Can be done better
function share_juice_show_video_links($url_desc_array,$echo = true)
{

    echo '<div class="share-juice-admin-helper-box">';
    echo '<a target="_blank" rel="nofollow noindex" class="share-juice-expand-div" href="" style="text-decoration:none;">[+]</a><b>Watch instructions Videos</b>';

    echo '<ul style="display:none">';

    foreach ($url_desc_array as $url=>$description) {
        echo '<li>';
        echo "<a href=\"".esc_url($url)."\" target=\"_blank\" rel=\"nofollow noindex\">".esc_html($description)."</a>";
        echo '</li>';
    }
    /*foreach($slug_desc_array as $slug=>$description){
    $url = SJ_VIDEO_URL."/{$slug}";
    echo '<li>';
    echo "<a href=\"{$url}\">{$description}</a>";
    echo '</li>';
    }
    */
    echo '</ul>';
    echo '</div>';

}

/*
*
* To better fetch share juice options
*
*/
// IMPROV usage
function get_share_juice_options($array_name = NULL)
{
    /* Warning: Never change this function */
    global $share_juice_options;

    if (empty($share_juice_options)) {
        $share_juice_options = get_option('share-juice-options');
    }
    if ($array_name == NULL)     return $share_juice_options;
    if (isset($share_juice_options[$array_name]))     return $share_juice_options[$array_name];
}

?>