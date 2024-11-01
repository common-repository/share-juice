<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_Pinterest extends BaseShare implements ShareInterface
{

	public static $count_mode_array = array('vertical' => 'Vertical', 'horizontal' =>'Horizontal', 'none' => 'No Count');
	private $count_mode;
	private $config_array;
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'PI', true);

		$this->config_array = maybe_unserialize($button_config);

		$this->count_mode = $this->config_array['count_mode'];
		if($this->count_mode== "vertical")		  $this->count_mode = "above";
		if($this->count_mode == "horizontal")		$this->count_mode= "beside";


	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$Pinterest_options = !empty($value) ? maybe_unserialize($value) : '';
		$count_mode = isset($Pinterest_options['count_mode']) ? $Pinterest_options['count_mode'] :'none';

		?>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[count_mode]">
				Annotation:
			</label>
			<?php
			echo SJFormHelper::createSelectHTML(self::$count_mode_array, $count_mode,
				'button_config[count_mode]') ?>
		</div>
		<?php echo self::get_reference_url("PI"); ?>

		<?php
	}
	public static
	function validate_button_configuration()
	{

		global $share_juice_error;
		//first validate

		$ser = array();

		if(!array_key_exists($_POST['button_config']['count_mode'], SJ_Pinterest::$count_mode_array))			$share_juice_error->add('E', "Count Mode not set");
		$ser['count_mode'] = $_POST['button_config']['count_mode'];

		return $ser;


	}
	public
	function get_button_HTML()
	{
		global $post;
		$url = $this->get_image_url();

		ob_start();
		?>
		<div class="share-juice-content-pinit-outer-<?php echo esc_html($this->count_mode)?>"><div class="share-juice-content-pinit-inner-<?php echo esc_html($this->count_mode) ?>"><a href="http://pinterest.com/pin/create/button/?url=<?php
		echo rawurlencode(esc_url($this->url));
		?>&media=<?php echo rawurlencode(esc_url($url));?>&description=<?php
		echo rawurlencode(get_the_title());?>" data-pin-do="buttonPin" <?php		echo 'data-pin-config="'.esc_html($this->count_mode).'"';
		?>><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png"/></a></div></div>
		<?php
		$button_html = ob_get_clean();
		return $button_html;
	}
	public
	function get_js_code()
	{
		//return '<BR/><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>';

	}
	public
	function echo_js_code()
	{
		echo $this->get_js_code();

	}

	public
	function get_image_url()
	{
		global $post;
		$mime_type_arr = array('image/jpeg','image/gif','image/png');

		$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $post->ID
		);
		$url='';
		$attachments = get_posts($args);
		//var_dump($attachments);
		if($attachments)
		{
			foreach($attachments as $attachment)
			{
				if(in_array($attachment->post_mime_type,$mime_type_arr)){
					$url_arr = wp_get_attachment_image_src( $attachment->ID );
					$url = $url_arr[0];
					break;
				}
			}
		}
		return $url;

	}
}

?>