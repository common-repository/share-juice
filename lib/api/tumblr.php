<?php

/**
 * @author Ashvini Saxena
 * @copyright 2012
 */

class SJ_Tumblr extends BaseShare implements ShareInterface
{

    
    private $config_array;
    public function __construct($url, $button_config)
    {
        parent::__construct($url, 'TL', false);

        $this->config_array = maybe_unserialize($button_config);

     }

    public static function get_customization_options_html($value)
    {


        ob_start();
     ?>
       <div class="share-juice-admin-label-and-field">
       No options are available for this at the moment 
       </div>
          <?php //echo self::get_reference_url("TL"); ?>
      
       
  <?php
    }
    public static function validate_button_configuration()
    {

        global $share_juice_error;
        //first validate

        $ser = array();

        return $ser;


    }
    public function get_button_HTML()
    {
		
		global $post;
		$post_url= get_permalink();
		$post_title = get_the_title();
		$language = 'en';
		
		//$excerpt = get_the_excerpt();
		if(has_excerpt($post->ID))
		{
			$excerpt = $post->excerpt;
		}
		else
		{
			$excerpt = substr($post->post_content,0,50);
		}
		
     
     $url =  "<a href=\"http://www.tumblr.com/share/link?url=".urlencode($post_url) ."&name=".urlencode($post_title)."&description=".urlencode($excerpt).'" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:61px; height:20px; background:url(\'http://platform.tumblr.com/v1/share_2.png\') top left no-repeat transparent;">Share on Tumblr</a>';
	  
        return $url;
    }
    public function get_js_code()
    {
        return "";

    }
    public function echo_js_code()
    {
        echo esc_js($this->get_js_code());

    }



}

?>