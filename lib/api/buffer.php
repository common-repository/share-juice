<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_Buffer extends BaseShare implements ShareInterface
{

	public static $count_mode_array = array('vertical' => 'Vertical', 'horizontal' =>
		'Horizontal', 'none' => 'No Count');
	private $count_mode;
	private $config_array;
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'BF', true);

		$this->config_array = maybe_unserialize($button_config);
		$this->count_mode = $this->config_array['count_mode'];


	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$Buffer_options = !empty($value) ? maybe_unserialize($value) : '';
		$count_mode = isset($Buffer_options['count_mode']) ? $Buffer_options['count_mode'] : '';
		$data_via = isset($Buffer_options['data_via']) ? $Buffer_options['data_via'] : '';
		?>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[count_mode]">
				Annotation:
			</label>
			<?php
			echo SJFormHelper::createSelectHTML(self::$count_mode_array, $count_mode,
				'button_config[count_mode]') ?>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for= "button_config[data_via]">
				Twitter Username
			</label>
            <input type="text" name="button_config[data_via]" value="<?php echo esc_html($data_via) ?>"/>
			<span class="helper">
				Twitter username to be mentioned. Please add "@" to start , like "@my_name"
			</span>
		</div>
		<?php echo self::get_reference_url("BF"); ?>


		<?php
	}
	public static
	function validate_button_configuration()
	{

		global $share_juice_error;
		//first validate

		$ser = array();

		if(!array_key_exists($_POST['button_config']['count_mode'], SJ_Buffer::$count_mode_array))            $share_juice_error->add('E', "Count Mode not set");
		$ser['count_mode'] = $_POST['button_config']['count_mode'];

		$ser['data_via'] = $_POST['button_config']['data_via'];
		return $ser;


	}
	public
	function get_button_HTML()
	{
		ob_start();
        echo '<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="'.esc_html($this->config_array['count_mode']).'"';
		if(isset($this->config_array['data_via']))		
        echo ' '.'data-via="'.esc_html(ltrim($this->config_array['data_via'],'@')).'"';
		echo '>Buffer</a>';
		$button_html = ob_get_clean();
		return $button_html;
	}
	public
	function get_js_code()
	{
		//return "<script type=\"text/javascript\" src=\"http://static.bufferapp.com/js/button.js\"></script>";
	}
	public
	function echo_js_code()
	{
        echo esc_js($this->get_js_code());

	}

}

?>