<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class BaseShare
{

	protected $url;
	private $shareID;
	private $js_in_footer;
	protected $script_text;
	const FACEBOOK_BUTTON = 0;
	const GOOGLEPLUS_BUTTON = 1;
	const PINTEREST_BUTTON = 2;
	const LINKEDIN_BUTTON = 3;
	const TWITTER_BUTTON = 4;
	private $is_js_set = false;
	protected $button_rec_db;
	private static $reference_url_array = array("FB" =>
		"https://developers.facebook.com/docs/reference/plugins/like/",
	"TW"=>"https://twitter.com/about/resources/buttons#tweet",
		"LI"=>"http://developer.linkedin.com/plugins/share-plugin-generator",
		"PI"=>"http://pinterest.com/about/goodies/",
		"DG"=>"http://about.digg.com/downloads/button/smart",
		"SU"=>"http://www.stumbleupon.com/dt/badges/create",
		"GP"=>"http://www.google.com/intl/en/webmasters/+1/button/index.html",
		"BF"=>"http://bufferapp.com/extras/button",
		"KI" =>"http://www.amazon.com/gp/sendtokindle/developers/button"
	);

	public function __construct($url, $shareID, $js_in_footer = '')
	{
		$this->shareID = $shareID;
		$this->url = $url;
		$this->js_in_footer = $js_in_footer;
	}

	public
	function is_js_in_footer_ok()
	{
		return $this->js_in_footer;
	}

	public
	function is_js_set_in_code()
	{
		return $this->is_js_set;
	}
	
	public
	function set_js_in_code($js_in_footer)
	{
		if($js_in_footer == true)			$this->is_js_set = true;
		else		$this->is_js_set = false;


	}
	
	public
	function get_reference_url($button_id)
	{

		$url = isset(self::$reference_url_array[$button_id]) ? self::$reference_url_array[$button_id] : '';
		return "<p><a target=\"_blank\" href=\"{$url}\"><b>Take me to the site's configuration page</b></a></p>";

	}
	
	public	function set_button_record_from_db($button_rec_db)
	{
		$this->button_rec_db = $button_rec_db;
	}


	public static
	function get_button_cofiguration($db,$custom_key)
	{
		$table_name =  $db->prefix.'share_juice';
		$sql = "select * from {$table_name} where custom_key=\"{$custom_key}\"";

		$result   = $db->get_results($sql,ARRAY_A);
			if(!empty($result[0]))			return $result[0];
	}

	public static
	function get_fixed_url_field($share_url)
	{
		ob_start();
		?>
		<div class="share-juice-admin-label-and-field">
			<label for= "button_config[share_url]">
				Fixed Share URL:
			</label>
			<input type="text" name="button_config[share_url]" value="<?php echo $share_url ?>" size="80"/>
			<span class="helper">
				Enter a URL here, if you want to share this URL instead of the permalink
			</span>
		</div>
		<?php
		return ob_get_clean();
	}

}

?>