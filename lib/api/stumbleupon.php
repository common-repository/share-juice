<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_StumbleUpon extends BaseShare implements ShareInterface
{


	private $show_count;
	private $via;
	private $hashtags;
	private $recommend;
	public static $language_array = array('EN' => 'English');
	public static $layout_type_array = array(1, 2, 3, 4, 5, 6);
	private $config_array;
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'SU', true);

		$this->config_array = maybe_unserialize($button_config);

	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$twitter_options = !empty($value) ? maybe_unserialize($value) : '';
		$layout_type = isset($twitter_options['layout_type']) ? $twitter_options['layout_type'] :
		'';
		?>
		<div id="custom-admin-detail-options" class="admin-detail-options">
			<div class="share-juice-admin-label-and-field">
				<label for="button_config[layout_type]">
					layout_type:
				</label>
				<input type="text" name="button_config[layout_type]" value="<?php echo !empty($layout_type)? intval($layout_type):1?>"/>
			</div>
			<?php echo self::get_reference_url("SU"); ?>
		</div>

		<?php
	}
	public static
	function validate_button_configuration()
	{

		global $share_juice_error;
		//first validate

		$ser = array();

		if($_POST['button_config']['layout_type'] < 1 || $_POST['button_config']['layout_type'] >
			6)            $share_juice_error->add('E', 'Layout type value has to be between 1 and 6');
		$ser['layout_type'] = $_POST['button_config']['layout_type'];


		return $ser;


	}
	public
	function get_button_HTML()
	{
		ob_start();
		echo '<su:badge layout="'.intval($this->config_array['layout_type']).'"></su:badge>';
		$button_html = ob_get_clean();
		return $button_html;
	}
	public
	function get_js_code()
	{
		$str = "<script type=\"text/javascript\">
		(function() {
		var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
		li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
		})();
		</script>";
		$str = '';
		return $str;

	}
	public
	function echo_js_code()
	{
		echo $this->get_js_code();

	}

}

?>