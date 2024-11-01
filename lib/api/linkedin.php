<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_LinkedIn extends BaseShare implements ShareInterface
{

	public static $count_mode_array = array('top' => 'Top', 'right' => 'Right',
		'none' => "None");
	private $data_counter;
	private $config_array;
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'LI', false);

		$this->config_array = maybe_unserialize($button_config);

		$this->data_counter = isset($this->config_array['data_counter']) ? $this->
		config_array['data_counter'] : 'none';


	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$linkedin_options = !empty($value) ? maybe_unserialize($value) : '';
		$counter = isset($linkedin_options['data_counter']) ? $linkedin_options['data_counter'] :
		'';

		?>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[data_counter]">
				Annotation:
			</label>
			<?php
			echo SJFormHelper::createSelectHTML(self::$count_mode_array, $counter,
				'button_config[data_counter]') ?>
		</div>
		<?php echo self::get_reference_url("LI"); ?>


		<?php
	}
	public static
	function validate_button_configuration()
	{

		global $share_juice_error;
		//first validate

		$ser = array();

		if(!array_key_exists($_POST['button_config']['data_counter'], SJ_LinkedIn::$count_mode_array))            $share_juice_error->add('E', "Count Mode not set");
		$ser['data_counter'] = $_POST['button_config']['data_counter'];

		return $ser;


	}
	public
	function get_button_HTML()
	{
		ob_start();
		echo '<script src="//platform.linkedin.com/in.js" type="text/javascript">
		</script>';
		echo '<script type="IN/Share"'.SJ_SPACE;
 		if($this->data_counter == "top" || $this->data_counter == "right")            
		echo 'data-counter="'.esc_html($this->data_counter).'">'; 
		echo '</script>';
		$button_html = ob_get_clean();

		return $button_html;
	}
	public
	function get_js_code()
	{
		return '';

	}
	public
	function echo_js_code()
	{
		echo $this->get_js_code();

	}



}

?>