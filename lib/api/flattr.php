<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_Flattr extends BaseShare implements ShareInterface
{

    public static $count_mode_array = array('top'  => 'Top','right'=> 'Right',
        'none' => "None");
    private $data_counter;
    private $config_array;
    public
    function __construct($url, $button_config)
    {
        parent::__construct($url, 'FT', false);

        $this->config_array = maybe_unserialize($button_config);

    }

    public static
    function get_customization_options_html($value)
    {


        ob_start();
        ?>
        <div class="share-juice-admin-label-and-field">
            No options are available for this at the moment
        </div>
        <?php //echo self::get_reference_url("FT"); ?>


        <?php
    }
    public static
    function validate_button_configuration()
    {

        global $share_juice_error;
        //first validate

        $ser = array();

        return $ser;


    }
    public
    function get_button_HTML()
    {
        global $post;
        $post_url   = get_permalink();
        $post_title = get_the_title();
        $language   = get_bloginfo('language');
        //Wordpress is issuing en - US while
        //flattr expects en_US
        $language   = str_replace('-','_',$language);

        if (has_excerpt($post->ID)) {
            $excerpt = $post->excerpt;
        }
        else {
            $excerpt = substr($post->post_content,0,50);
        }


        $excerpt = strip_tags($excerpt);
        ob_start();
        echo "<a class=\"FlattrButton\" href=\""
        .esc_url($post_url)
        ."\" title=\""
        .esc_html($post_title)
        ."\" lang=\""
        .esc_html($language)
        ."\">"
        .esc_html($excerpt)."</a>"
		
		;

        $button_html = ob_get_clean();
        return $button_html;
    }

    public
    function get_js_code()
    {
        //return " < script type = \"text / javascript\" >
        /* <![CDATA[ */
        /*    (function() {
        var s = document.createElement('script');
        var t = document.getElementsByTagName('script')[0];

        s.type = 'text/javascript';
        s.async = true;
        s.src = '//api.flattr.com/js/0.6/load.js?'+
        'mode=auto&uid=gargamel&language=sv_SE&category=text';

        t.parentNode.insertBefore(s, t);
        })();*/
        /* ]]> */
        //</script > ";


    }
    public
    function echo_js_code()
    {
        echo $this->get_js_code();

    }



}

?>