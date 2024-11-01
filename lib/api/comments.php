<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJ_Comments extends BaseShare implements ShareInterface
{

	public static $count_mode_array = array('vertical'  => 'Vertical','horizontal'=>
		'Horizontal','none'      => 'No Count');
	private $count_mode;
	private $config_array;
	public
	function __construct($url, $button_config)
	{
		parent::__construct($url, 'CC', true);

		$this->config_array = maybe_unserialize($button_config);
		$this->count_mode = $this->config_array['count_mode'];


	}

	public static
	function get_customization_options_html($value)
	{


		ob_start();
		$Comments_options = !empty($value) ? maybe_unserialize($value) : '';
		$count_mode = isset($Comments_options['count_mode']) ? $Comments_options['count_mode'] : '';
		$data_via = isset($Comments_options['data_via']) ? $Comments_options['data_via'] : '';
		?>
		<div class="share-juice-admin-label-and-field">
			<label for="button_config[count_mode]">
				Annotation:
			</label>
			<?php
			$selectHtml = SJFormHelper::createSelectHTML(self::$count_mode_array, $count_mode,'button_config[count_mode]');
			echo wp_kses($selectHtml,array(
                    'select'=>array(
                        "name"=>array()
                    ),
					'option'=>array()
                )
            );
		 ?>
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

		if(!array_key_exists($_POST['button_config']['count_mode'], SJ_Comments::$count_mode_array))            $share_juice_error->add('E', "Count Mode not set");
		$ser['count_mode'] = $_POST['button_config']['count_mode'];

		return $ser;


	}
	public
	function get_button_HTML()
	{
		global $post;
		$post_id       = $post->ID;
		$comment_count_arr = get_comment_count($post_id);

		Switch($this->config_array['count_mode']){
			case "vertical":
			return $this->get_vertical($comment_count_arr['approved']);
			break;
			case "horizontal":
			return $this->get_horizontal($comment_count_arr['approved']);
			break;
			case "none":
			default:
			return $this->get_none($comment_count_arr['approved']);
			break;
		}


	}
	function get_none($comment_count=0)
	{
		global $post;
		$post_id = $post->ID;
		ob_start();
		?>
		<div class="share-juice-comments-share-wrap">
		<div class="share-juice-comments-share-button-wrap">
			<button class="share-juice-comments-share-button">
				Comments
			</button>
		</div>

		<?php
		$button_html       = ob_get_clean();
		return $button_html;
	}

	function get_vertical($comment_count=0)
	{
		global $post;
		$post_id = $post->ID;
		ob_start();
		?>
		<div class="share-juice-comments-share-wrap share-juice-comments-share-vertical">
		<div class="share-juice-comments-share-img" style="background:url(<?php echo SJ_IMG_URL.'/comments.png'; ?>) no-repeat ">
		</div>
		<div class="share-juice-comments-share-counter-wrap">
		<?php echo '<span class="share-juice-comments-share-number">'.intval($comment_count).'</span>';?>
		</div>
		<div class="share-juice-comments-share-button-wrap">
			<?php echo '<button class="share-juice-comments-share-button">Comments</button>' ?>
		</div>

		<?php
		$button_html = ob_get_clean();
		return $button_html;
	}

	function get_horizontal($comment_count=0)
	{
		global $post;
		$post_id = $post->ID;
		ob_start();
		?>
		<div class="share-juice-comments-share-wrap share-juice-comments-share-horizontal">
			<div class="share-juice-comments-share-button-wrap">
				<button class="share-juice-comments-share-button">
					Comments
				</button>
			</div>
			<div class="share-juice-comments-share-img" style="background:url(<?php echo SJ_IMG_URL.'/comments-horizontal.png'; ?>) no-repeat ">
			</div>
			<div class="share-juice-comments-share-counter-wrap">
				<span class="share-juice-comments-share-number">
					<?php echo intval($comment_count); ?>
				</span>
			</div>
		</div>
		<?php
		$button_html = ob_get_clean();
		return $button_html;
	}

	public
	function get_js_code()
	{
		return "";
	}
	public
	function echo_js_code()
	{
		echo $this->get_js_code();

	}

}

?>