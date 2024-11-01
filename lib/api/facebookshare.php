<?php

/**
 * @author Ashvini Saxena
 * @copyright 2012
 */
class SJ_FacebookShare extends BaseShare implements ShareInterface
{

    public static $default_width = 450;
    private $config_array;
    public static $data_layout_arr = array("box_count" =>
        "box_count", "button_count" => "button_count","button"=>"button","icon_link"=>"icon_link","icon"=>"icon");
     

    public function __construct($url, $button_config)
    {


        parent::__construct($url, 'FB', true);
        $this->config_array = !empty($button_config) ? maybe_unserialize($button_config) :
            array();

    }


    public static function get_customization_options_html($value)
    {


        ob_start();
        $share_juice_optionss_arr = !empty($value) ? maybe_unserialize($value) : '';

        $data_layout = isset($share_juice_optionss_arr['data_layout']) ? $share_juice_optionss_arr['data_layout'] :
            '';
       
?>
        <p> 
        <label for="data_layout">Layout Style:</label>
        <?php echo SJFormHelper::createSelectHTML(self::$data_layout_arr, $data_layout,
        'button_config[data_layout]', 'data_layout') ?>
        </p>
        
        <?php echo "This button is deprecated, hence should be avoided. Documentation has been removed from the Facebook Site" ?>
       
        <?php
        return ob_get_clean();


    }

    public static function validate_button_configuration()
    {


        global $sj_error;
        //first validate
        $ret_arr = array();

        $share_juice_optionss_arr = $_POST['button_config'];

        $ret_arr['data_layout'] = isset($share_juice_optionss_arr['data_layout']) ? $share_juice_optionss_arr['data_layout'] :
            '';
        
        return $ret_arr;


    }

    public function get_button_HTML()
    {
        $url = urlencode($this->url);
		$layout = $this->config_array['data_layout'];
        /*
        $button_HTML = "<div class=\"fb-like\"";
        $button_HTML .= $this->config_array['data_send'] == true ? 'data-send="true"' :
            '';
        $button_HTML .= SJ_SPACE;
        if ($this->config_array['data_layout'] != "standard")
            $button_HTML .= "data-layout=\"" . $this->config_array['data_layout'] . "\"";
        $button_HTML .= SJ_SPACE;
        $button_HTML .= "data-width=\"450\"";
        $button_HTML .= SJ_SPACE;
        $button_HTML .= $this->config_array['data_show_faces'] == true ?
            'data-show-faces="true"' : '';
        $button_HTML .= SJ_SPACE;
        $button_HTML .= $this->config_array['data_action'] == "recommend" ?
            "data-action=\"" . $this->config_array['data_action'] . "\"" : '';
        $button_HTML .= SJ_SPACE;
        $button_HTML .= $this->config_array['data_layout'] == "dark" ?
            'data-colorscheme="dark"' : '';
        $button_HTML .= SJ_SPACE;
        $button_HTML .= !empty($this->config_array['data_font']) ? "data-font=\"" . self::
            $data_font_arr[$this->config_array['data_font']] . "\"" : '';
        $button_HTML .= SJ_SPACE;
        $button_HTML .= "></div>";


//$button_HTML ='<div class="fb-like" data-send="true" data-width="450" data-show-faces="true" data-font="arial"></div>';

		*/
		 
		ob_start();
		?>
        <div class="share-juice-facebook-share-<?php echo esc_html($this->config_array['data_layout']);?>">
            <a name="fb_share" type="<?php echo esc_html($this->config_array['data_layout'])?>" share_url="<?php echo get_permalink(); ?>" >
                Share
            </a>
            <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript">
            </script>
		</div>
	    <?php
		$button_HTML = ob_get_clean();
		return $button_HTML;
    
	/*
	----------------------------------------------------------------
	----------------- Just for reference, don't delete ------------------
	-----------------------------------------------------------------
		<a name="fb_share" type="<?php echo "box_count";//echo $this->config_array['data_layout']?>" share_url="<?php the_permalink() ?>" >Share</a>
    	<a name="fb_share" type="<?php echo "button_count";//echo $this->config_array['data_layout']?>" share_url="<?php the_permalink() ?>" >Share</a>
		<a name="fb_share" type="<?php echo "button";//echo $this->config_array['data_layout']?>" share_url="<?php the_permalink() ?>" >Share</a>
		<a name="fb_share" type="<?php echo "icon_link";//echo $this->config_array['data_layout']?>" share_url="<?php the_permalink() ?>" >Share</a>
		<a name="fb_share" type="<?php echo "icon";//echo $this->config_array['data_layout']?>" share_url="<?php the_permalink() ?>" >Share</a>
	
	------------------------------------------------------------------
	*/
	
	}

    public function get_js_code()
    {

       //$str = '<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
        $str = '';
		return $str;
    }

    public function echo_js_code()
    {

        echo $this->get_js_code();
    }
}


?>