<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/
class SJ_Facebook extends BaseShare implements ShareInterface
{

	public static $default_width = 450;
	private $config_array;
	public static $data_layout_arr = array("standard"    => "standard","box_count"   =>
		"box_count","button_count"=> "button_count");
	public static $data_action_arr = array("like"     => "like","recommend"=>
		"recommend");
	public static $data_colorscheme_arr = array("light"=> "light","dark" => "dark");
	public static $data_font_arr = array("arial"        => "arial","lucida grande"=>
		"lucida grande","segoeui"      => "segoe ui","tahoma"       => "tahoma","trebuchetms"  =>
		"trebuchet ms","verdana"      => "verdana");


	public
	function __construct($url, $button_config)
	{


		parent::__construct($url, 'FB', true);
		$this->config_array = !empty($button_config) ? maybe_unserialize($button_config) :
		array();

	}


	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$facebook_config_arr = !empty($value) ? maybe_unserialize($value) : '';

		$data_send = isset($facebook_config_arr['data_send']) && ($facebook_config_arr['data_send'] == true )?
		"CHECKED" : '';
		$data_layout = isset($facebook_config_arr['data_layout']) ? $facebook_config_arr['data_layout'] :
		'';
		$data_width = isset($facebook_config_arr['data_width']) ? $facebook_config_arr['data_width'] :
		SJ_Facebook::$default_width;
		$data_show_faces = isset($facebook_config_arr['data_show_faces']) && ($facebook_config_arr['data_show_faces'] == true) ?
		"CHECKED" : '';
		$data_action = isset($facebook_config_arr['data_action']) ? $facebook_config_arr['data_action'] :
		'';
		$data_colorscheme = isset($facebook_config_arr['data_colorscheme']) ? $facebook_config_arr['data_colorscheme'] :
		'';
		$data_font = isset($facebook_config_arr['data_font']) ? $facebook_config_arr['data_font'] : '';

		?>
		<div class="share-juice-admin-label-and-field">
			<label for="data_send">
				Send Button:
			</label>
			<input type="checkbox" name="button_config[data_send]" <?php
			echo $data_send; ?>/>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="data_layout">
				Layout Style:
			</label>
			<?php echo SJFormHelper::createSelectHTML(self::$data_layout_arr, $data_layout,
				'button_config[data_layout]', 'data_layout') ?>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="data_width">
				Width:
			</label>
			<input type="text" size="5" name="button_config[data_width]" value="<?php
			echo $data_width; ?>"/>
		</div>
		<div class="share-juice-admin-label-and-field">
		<label for="data_show_faces">
			Show Faces:
		</label>
		<input type="checkbox" name="button_config[data_show_faces]" <?php
		echo $data_show_faces; ?>/>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="data_action">
				Verb:
			</label>
			<?php echo SJFormHelper::createSelectHTML(self::$data_action_arr, $data_action,
				'button_config[data_action]', 'data_action') ?>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="data_colorscheme">
				Color Scheme:
			</label>
			<?php echo SJFormHelper::createSelectHTML(self::$data_colorscheme_arr, $data_colorscheme,
				'button_config[data_colorscheme]', 'data_color_scheme') ?>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="data_font">
				Font:
			</label>
			<?php echo SJFormHelper::createSelectHTML(self::$data_font_arr, $data_font,
				'button_config[data_font]', 'data_font') ?>
		</div>

		<?php echo self::get_reference_url("FB"); ?>

		<?php
		return ob_get_clean();


	}

	public static
	function validate_button_configuration()
	{


		global $share_juice_error;
		//first validate
		$ret_arr = array();

		$facebook_config_arr = $_POST['button_config'];

		$ret_arr['data_send'] = isset($facebook_config_arr['data_send']) ? true : false;
		$ret_arr['data_layout'] = isset($facebook_config_arr['data_layout']) ? $facebook_config_arr['data_layout'] :
		'';
		$ret_arr['data_width'] = isset($facebook_config_arr['data_width']) ? $facebook_config_arr['data_width'] :
		$this->default_width;
		$ret_arr['data_show_faces'] = isset($facebook_config_arr['data_show_faces']) ? true : false;
		$ret_arr['data_action'] = isset($facebook_config_arr['data_action']) ? $facebook_config_arr['data_action'] :
		'';
		$ret_arr['data_colorscheme'] = isset($facebook_config_arr['data_colorscheme']) ? $facebook_config_arr['data_colorscheme'] :
		'';
		$ret_arr['data_font'] = isset($facebook_config_arr['data_font']) ? $facebook_config_arr['data_font'] :
		'';


		return $ret_arr;


	}

	public
	function get_button_HTML()
	{
		$url         = urlencode($this->url);

		$button_HTML = "<div class=\"fb-like\"";
		$button_HTML .= $this->config_array['data_send'] == true ? 'data-send="true"' :
		'';
		$button_HTML .= SJ_SPACE;
		if($this->config_array['data_layout'] != "standard")            $button_HTML .= "data-layout=\"" . $this->config_array['data_layout'] . "\"";
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


		//$button_HTML = ' < div class = "fb - like" data - send = "true" data - width = "450" data - show - faces = "true" data - font = "arial"></div > ';
		return $button_HTML;
	}

	public
	function get_js_code()
	{

		global $share_juice_options;
		$fb_appid = $share_juice_options['admin_options']['facebook_appid'];

		/*$ret = '<div id="fb-root"></div>
		<script>(function(d, s, id)
		{
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId='.$fb_appid. '";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));</script>';



		return $ret;*/
	}

	public
	function echo_js_code()
	{

		echo $this->get_js_code();
	}
}


?>