<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_Digg extends BaseShare implements ShareInterface
{


	private $show_count;
	private $size;
	private $hashtags;
	private $recommend;
	public static $language_array = array('EN' => 'English');
	public static $size_array = array("DiggWide" => "Digg Wide", "DiggMedium" =>
		"Digg Medium", "DiggCompact" => "Digg Compact", "DiggIcon" => "DiggIcon");
	private $config_array;
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'DG', true);

		$this->config_array = maybe_unserialize($button_config);

	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$digg_options = !empty($value) ? maybe_unserialize($value) : '';
		$size = isset($digg_options['size']) ? $digg_options['size'] : '';
		?>
		<div id="custom-admin-detail-options" class="admin-detail-options">
			<div class="share-juice-admin-label-and-field">
				<label for="button_config[size]">
					Size:
				</label>
				<?php echo SJFormHelper::createSelectHTML(Digg::$size_array, $size, 'button_config[size]'); ?>
			</div>
		</div>

		<?php
	}
	public static
	function validate_button_configuration()
	{

		global $share_juice_error;
		//first validate

		$ser = array();

		if(!array_key_exists($_POST['button_config']['size'], Digg::$size_array))            $share_juice_error->add('E', 'size key not found');
		$ser['size'] = $_POST['button_config']['size'];


		return $ser;


	}
	public
	function get_button_HTML()
	{
		ob_start();
		?>

		<a class="DiggThisButton <?php echo $this->config_array['size']; ?>">
		</a>
		<?php
		$button_html = ob_get_clean();
		return $button_html;
	}
	public
	function get_js_code()
	{
		$str = '<script type="text/javascript">(function() {
		var s = document.createElement(\'SCRIPT\'), s1 = document.getElementsByTagName(\'SCRIPT\')[0];
		s.type = \'text/javascript\';
		s.async = true;
		s.src = \'http://widgets.digg.com/buttons.js\';
		s1.parentNode.insertBefore(s, s1);
		})();';

		$str = '<script type="text/javascript" src="http://widgets.digg.com/buttons.js"></script>';
		//$str = '';
		return $str;

	}
	public
	function echo_js_code()
	{
		echo $this->get_js_code();

	}

}

?>