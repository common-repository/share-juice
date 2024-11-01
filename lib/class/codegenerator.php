<?php

/*--- This file is different than codex version ----*/

/* This class is workhorse of the blog. It displays the icons with right values
1)It expands shortcodes to display icons from the groups
2) It displays JS and icons setup from Social Button
*/

class CodeGenerator
{
	public $class_instances = array();
	const BEFORE_CONTENT = 0;
	const AFTER_CONTENT = 1;
	const FLOATBAR = 2;
	
	private $sj_pint_ops;
	//constructor
	public
	function __construct()
	{
		//always include basefile
		require_once SJ_PLUGIN_DIR_BASE . '/lib/base/baseshare.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/base/shareinterface.php';
		//get permalink
		$url = urlencode(get_permalink());
		//this can be optimized further
		//include share api's that will generate HTML
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/facebook.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/googleplus.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/linkedin.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/twitter.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/stumbleupon.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/digg.php';
		
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/print.php';
	
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/buffer.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/pinterest.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/facebookshare.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/comments.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/flattr.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/tumblr.php';
		require_once SJ_PLUGIN_DIR_BASE . '/lib/api/kindle.php';


		//add all the css and js
		add_action('wp_enqueue_scripts', array($this,'add_scripts_and_styles'));
		//important thing about this function

        remove_filter('get_the_excerpt', 'wp_trim_excerpt');
        add_filter('get_the_excerpt', array($this,'fn_excerpt'));
		//add_filter('the_content', array($this,'show_pinterest_button_on_pics'), 0);

		//add_action('wp_footer',array($this,'add_js_pinterest'));
		//add_action('wp_head', array($this, 'echo_js_var'));
		//add_action('wp_head',array($this,'add_pinterest_js_vars'));
		//to replace the effect of wptexturize function ,
		//which converts & to & 38 and causes headache in fb output
		add_filter('the_content',array($this,'replace_strings_mod_by_wp'));

		//shortcodes for buttons
		add_shortcode('SJGROUP', array($this,'show_group'));
		add_shortcode('SJBUTTON', array($this,'show_button'));

		
	
		$this->sj_pint_ops  = new SJ_Pinterest_Ops();

	}
    // This function replaces default function "wp_trim_excerpt"
    // By remove_filter in the __construct above
    // This is exact copy of wp_trim_excerpt
    // except that it sets the flag that this post is an excerpt
    // This is used later in is_excerpt function of this class
    // If it is an excerpt, share buttons are not created

	public
    function fn_excerpt($text)
	{
        global $post;
        //This is used by is_excerpt function later
        if (!defined('SJ_USES_EXCERPT_'.$post->ID))
        define('SJ_USES_EXCERPT_'.$post->ID,true);
        //Everything below is the same as wp_trim_excerpt()
        /*start*/
		$raw_excerpt = $text;
		if('' == $text){
			$text           = get_the_content('');

			$text           = strip_shortcodes($text);

            $text           = apply_filters('the_content', $text);
            $text           = str_replace(']]>', ']]&gt;', $text);
			$excerpt_length = apply_filters('excerpt_length', 55);
			$excerpt_more   = apply_filters('excerpt_more', ' ' . '[...]');
			$text           = wp_trim_words($text, $excerpt_length, $excerpt_more);
		}

		return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
        /*end*/
	}

	function is_excerpt()
	{
		//single should not be excerpt
		if((is_single() ||is_archive()))
		 return false; 
		//something is triggering this on single posts
		//maybe some other plugin
		// so eliminating the possibility of 
		//excerpt on single and page
		if(is_single()|| is_page())
		{
			return false;
		}

		//blog specific correction
		//wonderoftech.com. Will not affect other blogs
		//unless the constant below is defined somewhere
        if (defined('SJ_EXCERPT_CHECK_ON_SINGLE') && SJ_EXCERPT_CHECK_ON_SINGLE == false) {

			return false;

		}
		global $post;
        //if this variable was set in fn_excerpt function
        if (defined('SJ_USES_EXCERPT_'.$post->ID))
        return true;
        else
        return false;
	}


	//Shortcode functions
	public
	function show_button($attr = '')
	{
		//this function is to show buttons individually
		//both javascript and icons
		//this will bypass the usual checks on before content , after content and float bar
		global $wpdb,$share_juice_class_map_arr;
		//the attr array has one parameter ( custom_key also known as button_name) in case of
		//javascript buttons
		//and button_name and size in case of icon Button
		$button_name = isset($attr['button_name'])?$attr['button_name']:'';
		// if there is not button name specified
		if(empty($button_name))			return;
		//get table name
		$table_name = $wpdb->prefix . 'share_juice';
		//prepare sql
		$sql        = "SELECT * from $table_name where custom_key= \"{$button_name}\"";
		//query db
		$button_db  = $wpdb->get_results($sql,ARRAY_A);
		//return if no such button exists in db
		if(empty($button_db))			return;
		//db record is in 0 index
		$button_db = $button_db[0];
		//now get the key
		$key       = $button_db['button_key'];
		//now check if the key is Icon
		// and size is specified
		$size      = isset($attr['size'])?$attr['size']:'';
		//if there is an icon and no size specified
		if($key == 'IC' && empty($size))			return;
		//get the mapping classes
		$class_name            = $share_juice_class_map_arr[$key];
		//prepare constructor params
		// config options
		$button_config_options = maybe_unserialize($button_db['button_config']);
		$url                   = get_permalink();
		//create the instance
		$class_instance        = new $class_name($url, $button_config_options);
		//set additional params by injection
		$class_instance->set_button_record_from_db($button_db);
		//get button html passing in the required size
		$button_html           = $class_instance->get_button_HTML(array('size'=>$attr['size'].'px'));

		$this->register_js_for_key($key,$class_instance);

		return $button_html;
	}
	/*--------------------------------------------
	//functions related to group processing
	//as created in Icon Groups page in admin
	---------------------------------------------*/
	// this expands the short code
	// attending to any array values

	function show_group($att = '')
	{
		global $share_juice_options, $sj_table_name, $wpdb;
		// do not display if group name has not been specified
		// set warning
		if(empty($att))			return;
		//get the group_id
		extract($att);
		//return if no group was specified
		//set warning
		if(empty($group_id))			return;
		if(!isset($share_juice_options['groups'][$group_id]))			return;
		//if there is no icon present
		//set warning
		if(isset($share_juice_options['groups'][$group_id]['icons']) && count($share_juice_options['groups'][$group_id]['icons']) == 0)			return;
		//create database column name from the image size value
		$image_size     = isset($share_juice_options['groups'][$group_id]['image_size'])?$share_juice_options['groups'][$group_id]['image_size']:'';
		$image_col_name = $image_size . "_file_exists";
		//get icons details from the master where
		// image_col_name is true
		$sql            = "select * from {$sj_table_name} where button_key=\"IC\" and $image_col_name = 1";
		$icon_list      = $wpdb->get_results($sql, ARRAY_A);
		//if there are no icons in the master for that key
		//set warning
		if(empty($icon_list))			return;
		$group_display_class = "share-juice-icon-group-" . $share_juice_options['groups'][$group_id]['group_display_type'];
		ob_start();
		?>
        <div class="share-juice-icon-group <?php echo esc_attr($group_display_class) ?>  <?php echo
        "share-juice-icon-group-" . esc_attr($group_id) ?>">
			<ul>
				<?php
				$group = $share_juice_options['groups'][$group_id];
				foreach($group['icons'] as $key => $value){
					$icon_prop = $this->get_icon_image_properties($icon_list, $key, $value, $image_size);
					if($icon_prop['enabled'] == false)				continue;
					//no image url found
					//set warning
					?>
					<li>
                        <a href="<?php echo esc_url($icon_prop['custom_url']); ?>" <?php echo $icon_prop['new_window'] == true ?
			'target="_blank"' : ''; ?>
            title="<?php echo esc_html($icon_prop['title']); ?>" >
                            <img src="<?php echo esc_url($icon_prop['image_url']); ?>" alt="<?php echo $icon_prop['alt_text']; ?>"/>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
			<div class="share-juice-buttons-clear-float">
		</div>
        </div>
        
		<?php

		if(isset($in_content))			return ob_get_clean();
		else		echo ob_get_clean();

	}
	//extract image path from result first
	//then create url from it

	function get_icon_image_properties($result, $key, $value, $image_size)
	{
		$image_prop = array();
		foreach($result as $res){
			if($res['custom_key'] == $key){
				//this value is serialized in db
				$config_array = maybe_unserialize($res['button_config']);
				//create path and remove slashes
				$path         = stripslashes($config_array['path']['path_to_' . $image_size . '_file']);
				//use static method to get image url
				$image_prop['image_url'] = SJ_Icon::get_image_url($path);
				if(!empty($value['custom_url']))					$image_prop['custom_url'] = $value['custom_url'];
                else {
					$image_prop['custom_url'] = SJ_Icon::get_api_url($config_array['api_key']);
				}
				$image_prop['alt_text'] = !empty($value['alt_text']) ? $value['alt_text'] : $config_array['alt_text'];
				$image_prop['title'] = !empty($value['title']) ? $value['title'] : $config_array['title'];
				$image_prop['new_window'] = $value['new_window'];
				$image_prop['enabled'] = $value['enabled'];
				return $image_prop;
			}
		}
	}
	//function to output any icon / Button
	/*--------------------------------------------------------
	//misc functions
	---------------------------------------------------------*/

	function add_scripts_and_styles()
	{
		global $share_juice_options;

		// Create the version by the time which this file was created on the
		// system
		$version = filemtime(__FILE__);
		// enqueue sj_buttons
		// IMPROV check if the footer script will work
		wp_enqueue_script(
			'share-juice-buttons-script',
			SJ_SCRIPTS_URL_BASE . '/sj_buttons.js',
			array('jquery'),
			$version
		); // Respects SSL, Style.css is relative to the current file
		
        $fb_appId = isset($share_juice_options['admin_options']['facebook_appid'])?$share_juice_options['admin_options']['facebook_appid']:'';
		//add javascript parameters for Ajax
		wp_localize_script(
			'share-juice-buttons-script',
			'sj_ajax_object',
            array('ajaxurl'             =>admin_url('admin-ajax.php'),
                'share_juice_fb_appId'=>$fb_appId)
		);


		// Now the register style has a ver
		wp_register_style(
			'share-juice-style',
			SJ_SCRIPTS_URL_BASE . '/style.css',
			'',
			$version
		);
		wp_enqueue_style('share-juice-style');
		//Style add
		$style_add_path = SJ_SCRIPTS_DIR_BASE . DIRECTORY_SEPARATOR.'style-add.css';
		$style_add_url =  SJ_SCRIPTS_URL_BASE.'/style-add.css';
		if(file_exists($style_add_path) && (filesize($style_add_path)>0)){
			wp_register_style(
				'share-juice-style-add',
				$style_add_url,
				array('share-juice-style'),//dependency
				$version
			);
			wp_enqueue_style('share-juice-style-add');
		}

		//custom css to be added last
		$custom_css_url           = SJ_CUSTOM_URL_BASE . '/custom.css';
		$custom_css_file_location = SJ_CUSTOM_DIR_BASE.DIRECTORY_SEPARATOR.'custom.css';
        if (file_exists( $custom_css_file_location) && (filesize($custom_css_file_location)) > 0) {

			wp_register_style(
				'share-juice-style-custom',
				$custom_css_url,
				array('share-juice-style','share-juice-style-add'),
				$version
			);
			wp_enqueue_style('share-juice-style-custom');
		}



	}


	function add_php()
	{
		wp_register_style('share-juice-style-php', plugins_url() . '/share-juice/style.php');
		wp_enqueue_style('share-juice-style-php');
		wp_register_style('share-juice-style-print', plugins_url() . '/share-juice/print.css');
		wp_enqueue_style('share-juice-style-print');
	}
	/*---------------------------------------------------
	// functions for settings based on the wp_share_juice
	-----------------------------------------------------*/
	//called by plugin
	public
	function add_buttons()
	{
		//if buttons in content enabled
		// get the levels from option
		global $share_juice_options;
		$this->add_filter_to_content();
	}

	function add_filter_to_content()
	{
		global $share_juice_options, $is_excerpt,$sj_mobile_detect;


		$prio_share_bar_before_content = !empty($share_juice_options['admin_options']['before_content_share_bar_filter_priority'])?$share_juice_options['admin_options']['before_content_share_bar_filter_priority'] : 10;
		$prio_share_bar_after_content = !empty($share_juice_options['admin_options']['after_content_share_bar_filter_priority'])?$share_juice_options['admin_options']['after_content_share_bar_filter_priority']:10;


		//add_filter('the_content', array($this, 'add_pinterest_div'),0);
		// this step has been added because there is no way to pass data to function used in filter
		if(isset($share_juice_options['global_general_options']['show_before_content_box']) && $share_juice_options['global_general_options']['show_before_content_box'] == true)		add_filter('the_content', array($this,'add_buttons_before_content'), $prio_share_bar_before_content);
		if(isset($share_juice_options['global_general_options']['show_after_content_box']) && $share_juice_options['global_general_options']['show_after_content_box'] == true)		add_filter('the_content', array($this,'add_buttons_after_content'), $prio_share_bar_after_content);
		if(isset($share_juice_options['global_general_options']['show_floating_box']) && $share_juice_options['global_general_options']['show_floating_box'] == true)		add_filter('the_content', array($this,'add_floating_buttons_to_content'));
		//add_filter('wp_footer', array($this, 'floating_buttons'));
	}
	public
	function add_pinterest_div($content)
	{
		return '<div class="sj_pinterest_start_content"></div>' . $content.'<div class="sj_pinterest_end_content"></div>';
	}


	function add_floating_buttons_to_content($content)
	{

		if(!(is_single() || is_page()))		 return $content;
		global $sj_mobile_detect;

		if(($sj_mobile_detect->isMobile()||$sj_mobile_detect->isTablet()))		return $content;



		$html = $this->create_HTML_code(CodeGenerator::FLOATBAR);
		if(!empty($html))			return '<div id="sj_start_content"></div>' . $content . $html .
		'<div id="sj_end_content"></div>';
		else		return $content;
	}

	function floating_buttons()
	{
		$html = $this->create_HTML_code(CodeGenerator::FLOATBAR);
		echo $html;
	}

	function add_buttons_before_content($content)
	{

		global $post;

		//check if the excerpt flag has been set
		if($this->is_excerpt() == true){
			return $content;
		}

		return $this->add_buttons_to_content($content, CodeGenerator::BEFORE_CONTENT);
	}

	function add_buttons_after_content($content)
	{

		//check if the excerpt flag has been set
		if($this->is_excerpt() == true){

			return $content;
		}

		return $this->add_buttons_to_content($content, CodeGenerator::AFTER_CONTENT);
	}

	function add_buttons_to_content($content, $content_position)
	{

		$html = $this->create_HTML_code($content_position);
		$html = preg_replace('/\r\n/', ' ', trim($html)); //if $html is to be placed before content
		if($content_position == CodeGenerator::BEFORE_CONTENT){
			return $html . $content;
		}
		//or after
		if($content_position == CodeGenerator::AFTER_CONTENT){
			return $content . $html;
		}
	}

	function create_HTML_code($content_position)
	{
		global $wpdb,
		$sj_table_name,
		$share_juice_class_map_arr,
		$share_juice_options;

		//where clause
		$add_where      = '';

		$css_class_name = "share-juice-float-left";

		if($this->check_display_box_allowed_from_exclude_fields($content_position) == false)			return NULL;
		if($content_position == CodeGenerator::BEFORE_CONTENT){
			$add_where = 'button_location_before_content = 1'.SJ_SPACE;
			if(isset($share_juice_options['global_general_options']['before_content_source']) && $share_juice_options['global_general_options']['before_content_source'] == 'js_button')				$add_where .= 'and button_key <> "IC"';
			elseif(isset($share_juice_options['global_general_options']['before_content_source']) && $share_juice_options['global_general_options']['before_content_source'] == 'icon')				$add_where .= 'and button_key = "IC"';
			$css_class_name = "share-juice-before-content";
		}
		elseif($content_position == CodeGenerator::AFTER_CONTENT){
			$add_where = 'button_location_after_content  = 1'.SJ_SPACE;
			if(isset($share_juice_options['global_general_options']['after_content_source']) && $share_juice_options['global_general_options']['after_content_source'] == 'js_button')				$add_where .= 'and button_key <> "IC"';
			elseif(isset($share_juice_options['global_general_options']['after_content_source']) && $share_juice_options['global_general_options']['after_content_source'] == 'icon')				$add_where .= 'and button_key = "IC"';
			$css_class_name = "share-juice-after-content";
		}
		elseif($content_position == CodeGenerator::FLOATBAR){
			$add_where = 'button_floating_active = 1'.SJ_SPACE;
			if(isset($share_juice_options['global_general_options']['floating_bar_source']) && $share_juice_options['global_general_options']['floating_bar_source'] == 'js_button')				$add_where .= 'and button_key <> "IC"';
			elseif(isset($share_juice_options['global_general_options']['floating_bar_source']) && $share_juice_options['global_general_options']['floating_bar_source'] == 'icon')				$add_where .= 'and button_key = "IC"';
			$css_class_name = "share-juice-floatbar";
		}

		//Add condition to check if the buttons show up on post/home/page/archive. This case was useful when floating bar was check but for example post was unchecked. This was causing an empty bar in the post screen without button
		if(is_home())		 $add_where .= SJ_SPACE."and button_show_on_home = 1". SJ_SPACE;
		if(is_single())		 $add_where .= SJ_SPACE." and button_show_in_post = 1". SJ_SPACE;
		if(is_page())		 $add_where .= SJ_SPACE." and button_show_on_page = 1". SJ_SPACE;
		if(is_archive())		 $add_where .= SJ_SPACE." and button_show_on_archive = 1". SJ_SPACE;


		//create sql first for js based button
		// and the query wp_share_juice
		$sql = "select * from {$sj_table_name}".SJ_SPACE;
		$sql .= !empty($add_where) ?SJ_SPACE. "where".SJ_SPACE.$add_where .SJ_SPACE : '';
		$sql .= "order by button_normal_order";
		$buttons      = $wpdb->get_results($sql, ARRAY_A);

		//this is the html to be sent
		$html_buttons = ''; //if there is any buttons available
		if(!empty($buttons))			$html_buttons = $this->generate_button_html($buttons, $content_position, false);
		return $html_buttons;
	}

	function show_content_on_sharebox($position)
	{
		global $share_juice_options;



		$class_name = '';


		if($position == CodeGenerator::BEFORE_CONTENT){
			$class_name = "share-juice-text-box-content";
			$text = isset($share_juice_options['text_box_options']['text_box_before_content'])?stripslashes($share_juice_options['text_box_options']['text_box_before_content']):'';
		}
        else
        if ($position == CodeGenerator::AFTER_CONTENT) {
			$class_name = "share-juice-text-box-content";
			$text = isset($share_juice_options['text_box_options']['text_box_after_content'])?stripslashes($share_juice_options['text_box_options']['text_box_after_content']):'';
		}
        return '<div class="'.esc_attr($class_name).'">'.wp_kses($text,wp_kses_allowed_html('post')).'</div>';
	}
	function check_display_box_allowed_from_exclude_fields($content_position)
	{
		global $share_juice_options ;
		$arr_slugs = array();
		if($content_position == CodeGenerator::FLOATBAR){
			$arr_slugs = !empty($share_juice_options['global_general_options']['exclude_for_floating_box'])? explode(',',$share_juice_options['global_general_options']['exclude_for_floating_box']):'';

		}
		if($content_position == CodeGenerator::BEFORE_CONTENT){
			$arr_slugs = !empty($share_juice_options['global_general_options']['exclude_for_before_content_box'])? explode(',',$share_juice_options['global_general_options']['exclude_for_before_content_box']):'';

		}
		if($content_position == CodeGenerator::AFTER_CONTENT){
			$arr_slugs = !empty($share_juice_options['global_general_options']['exclude_for_after_content_box'])? explode(',',$share_juice_options['global_general_options']['exclude_for_after_content_box']):'';

		}
		$slug = '';
		$id   = '';
		if(!empty($arr_slugs)){
			global $post;
			$slug = basename(get_permalink());
			$id   = $post->ID;
			//its in the list
			if(in_array($slug,$arr_slugs) || in_array($id,$arr_slugs))				return false;
		}


		return true;

	}


	//called to generate html for both icon and js buttons

	function generate_button_html($buttons, $content_position, $key_IC = false)
	{
		// Some global variables
		global $share_juice_js_key_arr,
		$share_juice_class_map_arr,
		$share_juice_options,
		$share_juice_css_div_class;

		$button_config_options = array();

		ob_start();

		if($content_position == Codegenerator::FLOATBAR){

			if($key_IC == false)				echo '<div  class="share-juice-floating-bar share-juice-floating-bar-js">';
            else                
			echo '<div  class="share-juice-floating-bar share-juice-floating-bar-icons">';

			echo '<div id="float-open-close-button"><img src ="'.SJ_IMG_URL.'/float-open.png'.'" id="floatbar-img-open" title="Open Sharing Bar"/><img src ="'.SJ_IMG_URL.'/float-close.png'.'" id="floatbar-img-close" title="Close Sharing Bar"/></div>';
            if (isset($share_juice_options['global_floating_box_options']['floating_box_textontop']))            echo "<div id=\"share-juice-float-box-text\">".esc_html($share_juice_options['global_floating_box_options']['floating_box_textontop'])."</div>";
		}
		elseif($content_position == Codegenerator::BEFORE_CONTENT)				echo "<div class=\"share-juice-buttons-box share-juice-buttons-before-content\">";
		elseif($content_position == Codegenerator::AFTER_CONTENT)echo "<div class=\"share-juice-buttons-box share-juice-buttons-after-content\">";

		// Functionality for text box
		if(is_single() || is_page()){
			if($content_position == Codegenerator::BEFORE_CONTENT){
				if(isset($share_juice_options['text_box_options']['text_before_content_enabled']) && $share_juice_options['text_box_options']['text_before_content_enabled'] == true)				echo $this->show_content_on_sharebox($content_position);
			}
			elseif($content_position == Codegenerator::AFTER_CONTENT){

				if(isset($share_juice_options['text_box_options']['text_after_content_enabled']) && $share_juice_options['text_box_options']['text_after_content_enabled'] == true)				echo $this->show_content_on_sharebox($content_position);
			}


		}

		//end of text box functionality
		?>
		<ul>
			<?php
			$instance_count = 0; //loop thru icons / buttons
			foreach($buttons as $button){
				//check visibilty criteria
				if($this->check_if_display_button($button) == false)				continue;
				$key                   = $button['button_key'];
				//get the mapping classes
				$class_name            = $share_juice_class_map_arr[$key];
				$button_config_options = maybe_unserialize($button['button_config']);
				$url                   = get_permalink();
				$class_instance        = new $class_name($url, $button_config_options);
				$class_instance->set_button_record_from_db($button);

				$css_class_name_for_buttons= $share_juice_css_div_class[$key];
				?>
				<li class="<?php echo $css_class_name_for_buttons; ?>">
					<?php
					$button_html = $class_instance->get_button_HTML();
					//this is echoed because it is part of the buffer
                    //comes escaped from apis
					//cannot be escaped here
					echo $button_html;
					//if the JS is not already added in the footer
					//and if indeed js can be put in footer
					//otherwise js will come attached in the call above
					//at get_button_HTML()



					if( $this->echo_script_next_to_button() === true){
						if(!in_array($key,array('LI','FS')))																$class_instance->echo_JS_code();

					}


					$this->register_js_for_key($key,$class_instance);

					?>
				</li>
				<?php
				$this->class_instances[$instance_count] = $class_instance;
			}
			?>
		</ul>
		<div class="share-juice-buttons-clear-float">
		</div></div>
		<?php
		$ret = ob_get_clean();
		return $ret;
	}

	//this function will set up the javascript
	// to be output at footer
	// depending on few conditions
	function register_js_for_key($key,$class_instance)
	{
		global $share_juice_js_key_arr,$share_juice_options;
		//there is no javascript for IC
		if($key == "IC")			return;
		//if the key is not in the already added
		if(!in_array($key, $share_juice_js_key_arr)){
			if(isset($share_juice_options['admin_options']['disable_js_echo'])
				&& $share_juice_options['admin_options']['disable_js_echo'] == true)				return;

			if(!$this->echo_script_next_to_button())			  $this->add_js_in_footer($class_instance);
			$share_juice_js_key_arr[] = $key;

		}
	}

	//to check visibility of button display

	function check_if_display_button($button)
	{
		$display = false;
		if(is_home() && $button['button_show_on_home'] == true)			$display = true;
		if(is_single() && $button['button_show_in_post'] == true)			$display = true;
		if(is_page() && $button['button_show_on_page'] == true)			$display = true;
		if(is_archive() && $button['button_show_on_archive'] == true)			$display = true;
		return $display;
	}
	//put js in footer wherever possible

	function add_js_in_footer($class_instance)
	{
		add_action('wp_footer', array($class_instance,'echo_JS_code'));
	}

	function echo_script_next_to_button()
	{
		global $share_juice_options;

		return isset($share_juice_options['admin_options']['script_next_to_buttons'])
		&& $share_juice_options['admin_options']['script_next_to_buttons'] == true? TRUE:FALSE;
	}

	function replace_strings_mod_by_wp($content)
	{
		//replace facebook string
		$content = str_replace('//connect.facebook.net/en_US/all.js#xfbml=1&#038;appId=','//connect.facebook.net/en_US/all.js#xfbml=1&appId=',$content);
		//replace flattr string
		$content = str_replace('mode=auto&#038;uid=gargamel&#038;language=sv_SE&#038;category','mode=auto&uid=gargamel&language=sv_SE&category',$content);

		return $content;
	}

}
?>