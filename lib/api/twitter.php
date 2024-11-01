<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/
/*
if(file_exists(SJ_PLUGIN_DIR_BASE.'/addons/twitter.php'))
{
require_once (SJ_PLUGIN_DIR_BASE.'/addons/twitter.php');
return;	
	
}
*/

class SJ_Twitter extends BaseShare implements ShareInterface
{
	
	
	private $show_count;
	private $via;
	private $hashtags;
	private $recommend;
	public static $language_array = array('en' => 'English');
	public static $data_count_array = array('vertical' =>'Vertical','horizontal' =>'Horizontal','none'=>'None');
	private $config_array;
	public function __construct($url, $button_config)
	{
		parent::__construct($url, 'TW', true);
		
		$this->config_array = maybe_unserialize($button_config);
		
	}
	
	public static function get_customization_options_html($value)
	{
		
		
		ob_start();
		$twitter_options = !empty($value) ? maybe_unserialize($value) : '';
		$via = isset($twitter_options['via']) ? $twitter_options['via'] : '';
		$recommend = isset($twitter_options['recommend']) ? $twitter_options['recommend'] :
		'';
		$hashtag = isset($twitter_options['hashtag']) ? $twitter_options['hashtag'] : '';
		
	    
		$largebutton = !empty($twitter_options['largebutton']) ? "checked" : '';
		$optout = !empty($twitter_options['optout']) ? "checked" : '';
		?>
		<div class="share-juice-admin-label-and-field"> 
		<label for="button_config[via]">Via:</label>
		<input type="text" name="button_config[via]" value="<?php echo esc_html($via) ?>"/>
		</div>
		<div class="share-juice-admin-label-and-field"> 
		<label for="button_config[recommend]">Recommend:</label>
		<input type="text" name="button_config[recommend]" value="<?php echo esc_html($recommend) ?>"/>
		</div>
		<div class="share-juice-admin-label-and-field"> 
		<label for="button_config[hashtag]">Hash Tags:</label>
		<input type="text" name="button_config[hashtag]" value="<?php echo esc_html($hashtag) ?>"/>
		</div> 
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[language]">Count:</label>
		<?php echo SJFormHelper::createSelectHTML(self::$data_count_array, '','button_config[data_count]') ?>
		</div>    
		<div class="share-juice-admin-label-and-field"> 
		<label for="button_config[largebutton]">Large Button:</label>
		<input type="checkbox" name="button_config[largebutton]" <?php echo esc_html($largebutton) ?>/>
		</div>          
		<div class="share-juice-admin-label-and-field"> 
		<label for="button_config[optout]">Opt Out:</label>
		<input type="checkbox" name="button_config[optout]" <?php echo esc_html($optout) ?>/>
		</div>          
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[language]">Language:</label>
		<?php echo SJFormHelper::createSelectHTML(self::$language_array, '',
		'button_config[language]') ?>
		</div>
		<?php echo self::get_reference_url("TW"); ?>
		
		
		<?php
	}
	public static function validate_button_configuration()
	{
		
		global $share_juice_error;
		//first validate
		
		$ser = array();
		
		
		$ser['via'] = $_POST['button_config']['via'];
		
		$ser['recommend'] = $_POST['button_config']['recommend'];
		$ser['hashtag'] = $_POST['button_config']['hashtag'];
		$ser['largebutton'] = isset($_POST['button_config']['largebutton']) ? $_POST['button_config']['largebutton'] :
		'';
		$ser['optout'] = isset($_POST['button_config']['optout']) ? $_POST['button_config']['optout'] :
		'';
		$ser['data_count'] = isset($_POST['button_config']['data_count']) ? $_POST['button_config']['data_count'] :'none';
		$ser['language'] = $_POST['button_config'];
		
		//no validation needed
		return $ser;
		
		
	}
	public function get_button_HTML()
	{
		
		$share_juice_options = get_option('share-juice-options');
		
		
		$via =   isset($this->config_array['via'])?$this->config_array['via']:'';
		$largebutton = isset($this->config_array['largebutton'])?$this->config_array['largebutton']:'';
		$recommend = isset( $this->config_array['recommend'])?$this->config_array['recommend']:'';
		$hashtag =  isset($this->config_array['hashtag'])?$this->config_array['hashtag']:'';

		global $post;
        $post_id = $post->ID;

		$hashtags_array = maybe_unserialize(get_post_meta($post_id,'share_juice_hashtags',true));

		if(isset($hashtags_array['share_juice_use_hashtags']) && $hashtags_array['share_juice_use_hashtags'] == true )
		{
			if(!empty($hashtags_array['share_juice_hashtags_str']))
			$hashtag .= ','.$hashtags_array['share_juice_hashtags_str'];
		}
		
		$hashtag = preg_replace('/#/','',$hashtag);
		
		
		$optout = isset($this->config_array['optout'])?$this->config_array['optout']:'';
		$data_count = isset($this->config_array['data_count'])?$this->config_array['data_count']:'none';
		
		$data_url = share_juice_get_short_url(get_permalink());
		if(empty($data_url))
		{
			 $data_url = get_permalink();
		
		}
		ob_start();
		echo '<a href="https://twitter.com/share" class="twitter-share-button" '  ; 
		if (!empty($via))
			echo "data-via=\"".esc_html($via)."\"" . ' ';
		if ($largebutton == true)
			echo "data-size=\"large\"" . ' ';
		if (!empty($recommend))
			echo "data-related=\"".esc_html($recommend)."\"" . ' ';
		if (!empty($hashtag))
			echo "data-hashtags=\"".esc_html($hashtag)."\"" . ' ';
		if (!empty($data_count))
			echo "data-count=\"".esc_html($data_count)."\"" . ' ';
		if ($optout == true)
			echo "data-dnt=\"true\"" . ' ';
		echo "data-url =".esc_url($data_url)."". ' ';
		echo "data-counturl=".get_permalink().' ';
		echo 'data-text="'.get_the_title().'" ';
		echo '>Tweet</a>';
		$button_html = ob_get_clean();
		return $button_html;
		}	
	public function get_js_code()
	{
		/*$str = '<br/><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
			if(!d.getElementById(id)){js=d.createElement(s);
				js.id=id;js.src="//platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js,fjs);}}
			(document,"script","twitter-wjs");</script>';*/
			$str='';
			return $str;
			
		}
		public function echo_js_code()
		{
			echo $this->get_js_code();
			
		}
		
	}
		
?>