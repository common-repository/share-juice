<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_GooglePlus extends BaseShare implements ShareInterface
{

	public static $annotation_array = array('bubble' =>'Bubble','inline' => 'Inline', 'none' => 'None',  );
	private $width;
	private $size;
	private $language;
	private $annotation;
	public static $language_array = array('en-US' => 'English (US)','en-GB' => 'English (UK)');
	private $config_array;
	public static $size_array = array("small"=>"Small","medium"=>"Medium","standard"=>"Standard","tall"=>"Tall");
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'GP', true);

		$this->config_array = maybe_unserialize($button_config);


	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$google_options = !empty($value) ? maybe_unserialize($value) : '';
		$size = isset($google_options['size']) ? $google_options['size'] : '';
		$width = isset($google_options['width']) ? $google_options['width'] : 450;
		$annotation_array = isset($google_options['anotation']) ? $google_options['anotation'] :
		'';
		?>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[size]">
				Size:
			</label>
			<input type="radio" name="button_config[size]" value="small"
			<?php
			if($size == "small" || empty($size))            echo "checked"; ?>  />Small(15px)
			<input type="radio" name="button_config[size]" value="medium"
			<?php
			if($size == "medium")            echo "checked"; ?> />Medium (20px)
			<input type="radio" name="button_config[size]" value="standard"
			<?php
			if($size == "standard")            echo "checked"; ?> />Standard (24px)
			<input type="radio" name="button_config[size]" value="tall" <?php
			if($size ==
				"tall")            echo "checked"; ?>/>Tall (60px)
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[annotation]">
				Annotation:
			</label>
			<?php
			echo SJFormHelper::createSelectHTML(self::$annotation_array, $annotation_array,
				'button_config[annotation]') ?>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[language]">
				Langauge:
			</label>
			<?php echo SJFormHelper::createSelectHTML(self::$language_array, '',
				'button_config[language]') ?>
		</div>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[width]">
				Width:
			</label>
            <input type="text" name="button_config[width]" value="<?php echo intval($width) ?>"/>
		</div>
		<?php echo self::get_reference_url("GP"); ?>

		<?php
	}
	public static
	function validate_button_configuration()
	{

		global $share_juice_error;
		//first validate

		$ser = array();

		if(!array_key_exists($_POST['button_config']['size'],
				SJ_GooglePlus::$size_array))            $share_juice_error->add('E', "Please choose icon size");
		$ser['size'] = isset($_POST['button_config']['size'])?$_POST['button_config']['size']:'small';

		if(!array_key_exists($_POST['button_config']['annotation'],
				SJ_GooglePlus::$annotation_array))            $share_juice_error->add('E', "Annotation key not found");

		$ser['annotation'] = $_POST['button_config']['annotation'];

		if(!array_key_exists($_POST['button_config']['language'],
				SJ_GooglePlus::$language_array))            $share_juice_error->add('E', "Language key not found");

		$ser['language'] = $_POST['button_config']['language'];

		$width = $_POST['button_config']['width'];
		if($width  < 100 || $width  > 800)            $share_juice_error->add('E', "Width should be between 100 and 800");

		$ser['width'] = $_POST['button_config']['width'];

		return $ser;


	}
	public
	function get_button_HTML()
	{
		ob_start();
		?>
		<g:plusone  <?php
 if (!empty($this->config_array['annotation']))                echo "annotation=".esc_html($this->config_array['annotation']).' ' ;
            echo "size=".esc_html($this->config_array['size']).' ';
            echo "language=".esc_html($this->config_array['language']).' ';
            ?>>
		</g:plusone><?php

		$button_html = ob_get_clean();
		return $button_html;
	}
	public
	function get_js_code()
	{
		//$str = '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
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