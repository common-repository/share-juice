<?php

/*
Purpose of this class:
----------------------
This classs helps in adding pinterest button to the
images on the post
*/
/*
Process:
--------
a) The class first reads the content supplied by the filter 'the_content'
b) Then it gets the classes value from the option provided in the advanced section
of global options ( excluded and included fields) . By default the classes have
'wp-image-' as included and 'wp-smiley' as excluded classes because the wordpress has
these classes added as default for images.
c) Using comma separeted values, more classes can be added to these advanced fields
d) The content provided by the filter is checked for the img tag. Once it is found, the classes
are matched for inclusion and exclusion. Based on the returned result, a class
"share-juice-pinterest-img" is added to each image.
e) The content is returned to the normal flow of WordPress
f) At the front end (browser), jQuery gets all the images with the class
"share-juice-pinterest-img" and adds pinterest button to it.
*/
/*
Why is this so complicated
--------------------------
Adding Pinterest button is the most complicated process for Share Juice Plugin
a) Getting attachment using get_posts provides all attachments only ( that is new
images loaded by   ) media . It does not provide those attachments are already
existing in other post and inserted into new post
b) The images could be marked as http://abc.com/..../ image-height*width.png based on the
height and width that has been put in the attachment setting window. However get_posts
returns the url of image that is without these attributes added to the image url. Thus
huge processing is needed for finding out the match of images
c) It is not easy to use regex because regex is complicated and is considered to be very
costly in processing time
d) The above method covers all scenarios. Classes can be added if needed.
*/
/* Another huge complication with Pinterest

Pinterest JS should never be called twice. It will mess up the buttons.
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>

The problem was that it was called as many times as many buttons were displayed each for share bars , float bar and on images. 
So now the JS will be only called in the front end
a) All dumb buttons in share bars(from codegenerator) /images ( from jquery sj_pinterest.js) are loaded ( No call to JS yet)
b) The final function of sj_pinterest.js is call to get script( all buttons exist before it)

JS will not be ever output from API(pinterest.php). It will only be rendered after all buttons are available in DOM ( hidden or visible)

This is the only way it works.  
*/

class SJ_Pinterest_Ops
{


	// Private variables
	private $excluded_url_array;
	private $attachments;
	private $count_layout;
	private $options;

	/* Constructor */
	public
	function __construct()
	{

		// Get the value of Share Juice Options
		$this->options = get_share_juice_options('global_pinterest_options');

		// Set the value of count layout
		// Previously pinterest has the values of vertical, horizontal and none
		// Now it has Above, Besides and None
		//Stupid pinterest problems
		$this->count_layout = isset($this->options['pinterest_count_layout'])?$this->options['pinterest_count_layout']:'none';
		if($this->count_layout == "vertical")		  $this->count_layout = "above";
		if($this->count_layout == "horizontal")		$this->count_layout = "beside";

		// No matter what always call this function
		// read notes above
		// IMPROV
		add_action('wp_enqueue_scripts', array($this,'add_scripts_and_styles'));

		// Check if pinterest button is set on images
		// This is different from the function check_display_on_post_page_home
		// as the function checks if pinterest button is need  for in content filter
		// The code below is just to avoid adding sj_pinterest.js and the filter if
		// it is not added in functionality
		// The if below checks if any of the display(page/post/home) are true and then
		// negates the result.
		// Hence if true is returned, that means function should go on.  The negative of true
		// is false
		// If the return is false negative of false is true, thus the flow ends here

		if(	!(
				// Check on home
				(
					isset($this->options['pinterest_button_on_home']) && $this->options['pinterest_button_on_home'] == true
				)//check isset and true
				||
				(
					//check on post
					isset($this->options['pinterest_button_on_post']) && $this->options['pinterest_button_on_post'] == true
				)//check isset and true
				||
				(
					//check on page
					isset($this->options['pinterest_button_on_page']) && $this->options['pinterest_button_on_page'] == true

				)//check isset and true
			)//check big condition
		)//check if

		return;

		//Set this function to be called during processing of content
		add_filter('the_content',array($this,'add_pinterest_to_post_images'));



	}


//always call this function
	public
	function add_scripts_and_styles()
	{
		
		$version = filemtime(__FILE__);
		wp_enqueue_script(
			'share-juice-pint-script',
			SJ_SCRIPTS_URL_BASE . '/sj_pinterest.js',
			array('jquery'),
			$version
		);
		// Create an array to be read by SJ_Pinterest.js
		// This has an object sj_pint_object with the count layout value
		// set above
		wp_localize_script('share-juice-pint-script','sj_pint_object', array('count_layout'=>$this->count_layout));

	}
	/*
	**
	Function: Check_display_on_post_page_home
	Description: To check if the pinterest button is to be displayed on page/post/home
	**
	**/

	public
	function check_display_on_post_page_home()
	{

		global $share_juice_options;

		if(is_home() && isset($this->options['pinterest_button_on_home']) && $this->options['pinterest_button_on_home'] == true )			return true;

		if(is_single() && isset($this->options['pinterest_button_on_post']) && $this->options['pinterest_button_on_post'] == true)			return true;

		if(is_page() && isset($this->options['pinterest_button_on_page']) && $this->options['pinterest_button_on_page'] == true)			return true;

		return false;

	}

	/*
	**
	Function: add_pinterest_to_post_images
	Description: To add pinterest button to the content. This button is called by
	Wordpress after addition to the_content filter in the construct of this
	class
	**
	**/


	public
	function add_pinterest_to_post_images($content)
	{

		global $post;
		$loc_url               = get_permalink();

		//First check if the pinterest button is to be added at all
		if($this->check_display_on_post_page_home() != true)		 return $content;

		// This fills up excluded images array from the option
		$this->get_pinterest_excluded_images();

		//redundant rremove after testif(!$this->check_display_on_post_page_home())		 return $content;

		//remove???	if(empty($content))
		//return $content;
		// Load the parser class
		$html = new share_juice_simple_html_dom();
		// load the content string into HTML parser class
		$html->load($content);
		//Find all images
		$images = $html->find('img');

		// Loop through all the images found
		foreach($images as $img){
			//Get source attribute of the image
			$src = $img->getAttribute('src');
			//Do not process images if they are in excluded list
			if($this->is_image_excluded($src))				continue;
			//Image is not excluded
			// Get class
			$class = $img->getAttribute('class');

			// Check class inclusion and exclusion based on the
			// values in the option
			if(!$this->is_class_included($class))			  continue;
			if($this->is_class_excluded($class))			  continue;

			// If it has reached this point
			// This image is candidate for pinterest
			// add a class to it
			$class .= " share-juice-pinterest-img";

			$img->setAttribute('class',$class);

		}
		// convert html back to string
		return $html->save();

	}

	/*
	**
	Function: is_class_included
	Description: To add pinterest button to the content. This button is called by
	Wordpress after addition to the_content filter in the construct of this
	class
	**
	**/

	private
	function is_class_included($class_string)
	{
		if(empty($this->options['pinterest_show_for_img_class'])){
			return true;
		}
		else
		{
			$included_class_array = explode(',',$this->options['pinterest_show_for_img_class']);
		}
		foreach($included_class_array as $incl_class){
			if(preg_match("/{$incl_class}/",$class_string)) return true;;
		}
		return false;
	}

	/*
	**
	Function: is_class_included
	Description: To add pinterest button to the content. This button is called by
	Wordpress after addition to the_content filter in the construct of this
	class
	**
	**/
	private
	function is_class_excluded($class_string)
	{
		if(empty($this->options['pinterest_hide_for_img_class'])){
			return false;
		}
		else
		{
			$excluded_class_array = explode(',',$this->options['pinterest_hide_for_img_class']);
		}
		foreach($excluded_class_array as $excl_class){
			if(preg_match("/{$excl_class}/",$class_string)) return true;;
		}
		return false;
	}

	/*
	**
	Function: is_class_included
	Description: To add pinterest button to the content. This button is called by
	Wordpress after addition to the_content filter in the construct of this
	class
	**
	**/

	function get_pinterest_excluded_images()
	{

		global $share_juice_options;

		//get URLs from options

		$excluded_url = !empty($this->options['pinterest_excl_images'])?$this->options['pinterest_excl_images']:'';

		if($excluded_url == NULL)		 return NULL;

		//explode to form an array

		$this->excluded_url_array = explode(',',$excluded_url);

	}

	/*
	**
	Function: is_class_included
	Description: To add pinterest button to the content. This button is called by
	Wordpress after addition to the_content filter in the construct of this
	class
	**
	**/
	function is_image_excluded($image_source)
	{

		if(empty($this->excluded_url_array))		 return false;



		$image_src_break = explode('?',$image_source);



		$image_source_to_be_compared = $image_src_break[0];

		if(!in_array($image_source_to_be_compared,$this->excluded_url_array))			 return false;

		else		return true;

	}




	/* The functions below are just kept as reference and are not used anymore*/

	/*

	private
	function is_image_attachment($src)
	{
	foreach($this->attachments as $att)
	if($src == $att->guid)			 return true;

	return false;

	}
	private

	function add_pinit_button($img)
	{

	$url = $img->getAttribute('src');

	$desc = $this->get_image_caption($url);

	$href = rawurlencode(get_permalink()).'&media='.rawurlencode($url).'&description='.rawurlencode($desc);

	$pinit_html = '<div style="display:none" class="pinit-button-wrap"><a target="_blank" href="//pinterest.com/pin/create/button/?url='.$href.'" data-pin-do="buttonPin" count-layout="'.$this->count_layout.'"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></div>';
	return $pinit_html;

	}

	private

	function wrap_image($img_html)
	{

	return '<div style="position:relative" class="share-juice-pinterest-img-parent-div">'.$img_html.'</div>';

	}

	public

	function add_js_pinterest()
	{

	?>

	<script src="//assets.pinterest.com/js/pinit.js">

	</script>

	<?php

	}







	function get_image_caption($url)
	{

	if(empty($this->attachments))		 return;
	//first strip the url of '?' if any
	$loc = strpos($url,'?');
	if($loc !== false)
	{

	$url = substr($url,0,$loc);

	}
	foreach($this->attachments as $att)
	{
	if($url == $att->guid)
	{
	if(!empty($att->post_excerpt))				 return $att->post_excerpt;

	else				 return $att->post_title;

	}



	}

	}

	*/
}



?>