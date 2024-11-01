<?php
/**
* @author Ashvini Saxena
* @copyright 2012
*/
class SJ_PrintButton extends BaseShare implements ShareInterface
{
	private $config_array;
	public function __construct($url, $button_config)
	{
		parent::__construct($url, 'PR', false);
		$this->config_array = maybe_unserialize($button_config);
	}
	public static function get_customization_options_html($value)
	{
		ob_start();
		$print_options = !empty($value) ? maybe_unserialize($value) : '';
		// if it is a post request or first time load
		if(isset($_POST['process']) || empty($print_options))
		{
			
			
			$print_options['use_sj_print_function'] = 
			isset($_POST['button_config']['use_sj_print_function'])? 
			TRUE:FALSE;
			$print_options['use_theme_css'] = 
			isset($_POST['button_config']['use_theme_css'])? 
			TRUE:FALSE;
			$print_options['use_theme_print_css'] = 
			isset($_POST['button_config']['use_theme_print_css'])? 
			TRUE:FALSE;
			$print_options['remove_images'] = 
			isset($_POST['button_config']['remove_images'])? 
			TRUE:FALSE;
			$print_options['remove_videos'] = 
			isset($_POST['button_config']['remove_videos'])? 
			TRUE:FALSE;			
			$print_options['custom_print_function'] = 
			isset($_POST['button_config']['custom_print_function']) ? 
			$_POST['button_config']['custom_print_function'] :'';
		}
		?>
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[use_sj_print_function]">Use Share Juice print function:</label>
		<input type="checkbox" name="button_config[use_sj_print_function]" <?php
		echo $print_options['use_sj_print_function'] == true? "CHECKED":'';  ?> id="use_sj_print_function"/> 
		</div>
		<div id="share-juice-print-style-options-group" class="share-juice-custom-field-group ">
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[use_theme_css]">Add theme css?</label>
		<input type="checkbox" name="button_config[use_theme_css]" <?php
		echo $print_options['use_theme_css'] == true? "CHECKED":'';  ?>
		id="use_theme_css"/>
		<span class="helper">Apply the theme's CSS file for styling the print output. In case you are having any problem with print output you can test if checking this box makes it look better, otherwise leave it unchecked</span>
		</div>
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[use_theme_print_css]">Add theme Print css?</label>
		<input type="checkbox" name="button_config[use_theme_print_css]" <?php
		echo $print_options['use_theme_print_css'] == true? "CHECKED":'';  ?> id="use_theme_print_css"/>
		<span class="helper">Use the theme print CSS file for styling the print output. In case you are having any problem with print output you can test if checking this box makes it look better, otherwise leave it unchecked</span>
		</div>
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[remove_images]">Remove images from print?</label>
		<input type="checkbox" name="button_config[remove_images]" <?php
		echo $print_options['remove_images'] == true? "CHECKED":'';  ?> id="remove_images"/>
		<span class="helper">Remove images from print output</span>
		</div>
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[remove_videos]">Remove videos from print?</label>
		<input type="checkbox" name="button_config[remove_videos]" <?php
		echo $print_options['remove_videos'] == true? "CHECKED":'';  ?> id="remove_videos"/>
		<span class="helper">Remove videos from print output</span>
		</div>
		</div>
		<div id = "share-juice-php-function-field-group" class="share-juice-custom-field-group ">
		<div class="share-juice-admin-label-and-field">
		<label for="button_config[custom_print_function]">PHP Function:</label>
		<input type="text" name="button_config[custom_print_function]" value="<?php echo esc_html($print_options['custom_print_function']) ?>" id="custom_print_function"/> 
		</div>
		</div>
		<?php
		$button_html = ob_get_clean();
		return $button_html;
	}
	public static function validate_button_configuration()
	{
		global $share_juice_error;
		//first validate
		$ser = array();
		$ser['use_sj_print_function'] = isset($_POST['button_config']['use_sj_print_function'])?TRUE:FALSE;
		$ser['use_theme_print_css'] = isset($_POST['button_config']['use_theme_print_css'])?TRUE:FALSE;
		$ser['use_theme_css'] = isset($_POST['button_config']['use_theme_css'])?TRUE:FALSE;
		$ser['remove_images'] = isset($_POST['button_config']['remove_images'])?TRUE:FALSE;
		$ser['remove_videos'] = isset($_POST['button_config']['remove_videos'])?TRUE:FALSE;
		
		
		if (!isset($_POST['button_config']['use_sj_print_function']))
			if (empty($_POST['button_config']['custom_print_function']) 
		||
		!function_exists(trim($_POST['button_config']['custom_print_function']))
			)
			$share_juice_error->add('E', "\"PHP function\" is either empty or does not exist");
		
		$ser['custom_print_function'] = trim($_POST['button_config']['custom_print_function']);	
		
		return $ser;
	}
	public function get_button_HTML()
	{
		global $post,$ret;
		if(isset($this->config_array['use_sj_print_function']) && $this->config_array['use_sj_print_function'] == true)
		{
			$permalink = get_permalink();
			$use_theme_print_css = $this->config_array['use_theme_print_css'];
			$use_theme_css = $this->config_array['use_theme_css'];
			$img_loc = SJ_PLUGIN_URL_BASE.'/images/printer_64px.png';
			
			$custom_key = $this->button_rec_db['custom_key'];			
			$ret = "<a href=\"{$permalink}?print=1&id={$post->ID}&custom_key={$custom_key}\" target=\"_blank\" rel=\"nofollow noidex noarchive\" title=\"Print\" ><img src=\"{$img_loc}\"/></a>";
		}
		else{
			
			//first check if the function echoes the result rather than 
			//returning it
			$func = $this->config_array['custom_print_function'];
			if(!function_exists($func))
			 return;
			ob_start();
			call_user_func_array($func,array());
			$ret = ob_get_clean();
			//if it does not echo it instead returns it
			if (empty($ret)) 
			$ret  = call_user_func_array($func,array());
		}
		return $ret;
	}
	public function get_js_code()
	{
		return '';
	}
	public function echo_js_code()
	{
		echo $this->get_js_code();
	}
	
}
?>