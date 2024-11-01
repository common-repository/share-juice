/* This JS is to show the pinterest button for images marked by SJ_Pinterest.php class */
/* Process:
--------

All images where Pinterest button is to be added are marked with class "share-juice-pinterest-img". It may be inside a attachment class or inside a url, it does not matter.
a) Find the images with these classes
b) Add a pinterest button in a wrapper to it
c) Cover the image and PI button in a wrapper
d) Float it based on image properties ( left or right)
f) Set the complete wrapper CSS to relative
g) Set Pin it wrapper css to absolute and calculate params for its position on image
h) Set functions on hover
i) Call pinterest script to show bubbles ( vertical/horizontal)

*/

// a variable to keep track of display of pinterest button
var visible =false;
jQuery(document).ready(function()
	{
		// get all images
		var sj_post_images = jQuery(".share-juice-pinterest-img");
		// set up images
		share_juice_setup_pinterest_buttons(sj_post_images);
		// call Pinterest Script
		jQuery.getScript("//assets.pinterest.com/js/pinit.js");

	});

/*
Function : share_juice_setup_pinterest_buttons
Description : Set up the pinterest button for images
*/
function share_juice_setup_pinterest_buttons( sj_post_images)
{

	jQuery(sj_post_images).each(function()
		{
			var parent_a;
			//check if the image is inside a hyperlink
			if(jQuery(this).parent("a").length)
			{
				parent_a = jQuery(this).parent("a");
			}

			//get caption text for images
			var caption_text = share_juice_get_caption_text(this,parent_a);
			//get_pinit_html
			var pinit_html = share_juice_get_pinit_html(this,caption_text);

			// wrap the image
			// the return value is image and hence cannot be cached :(
			jQuery(this).wrap('<div class="share-juice-pinterest-complete-wrap">');
			// add pinit html
			jQuery(this).after(pinit_html);

			//Get the complete wrap
			var wrap_div = jQuery(this).parents(".share-juice-pinterest-complete-wrap");
			//Get pinterest button wrap
			var pinit_button_wrap_div = jQuery(this).siblings(".share-juice-pinit-button-wrap");
			//set css for image, wrapper and pint button
			share_juice_set_pinterest_css(this,wrap_div,pinit_button_wrap_div);

			//set functions on mouse hover
			share_juice_set_on_mouse(this,wrap_div,pinit_button_wrap_div);

		});
	/*
	Function : share_juice_set_pinterest_css
	Description :  Setting CSS of various images which have pinterest button added to them
	*/
	function share_juice_set_pinterest_css(post_image,wrap_div,pinit_button_wrap_div)
	{
		// if image has a float, assign to the complete wrapper
		if( jQuery(post_image).css('float') == 'left'
			|| jQuery(post_image).css('float') == 'right')
		{
			var float_img = jQuery(post_image).css('float');

			jQuery(wrap_div).css('float',float_img);
			jQuery(post_image).css('float','none');

			// can't do anything with center aligned image as
			//jquery cannot detect margins

		}

		//Now set the wraps position

		jQuery(wrap_div).css({position:"relative"});

		var left = jQuery(post_image).offset().left - jQuery(wrap_div).offset().left;
		var top = jQuery(post_image).offset().top - jQuery(wrap_div).offset().top;

		//alert(jQuery(post_image).offset().left);
		//alert(jQuery(wrap_div).offset().left);
		jQuery(pinit_button_wrap_div).css({position:"absolute",left:left,top:top});

	}
	/*
	Function : share_juice_set_on_mouse
	Description : On hover over the complete wrap ( includes post image and pinterest div)
	*/


	function share_juice_set_on_mouse(post_image,wrap_div,pinit_button_wrap_div)
				{

		// On hover of wrap function
		jQuery(wrap_div).hover(function()
			{
				//hover in
				// set pinit-button wrap to inline-block (display it)
				// and darken image
				jQuery(pinit_button_wrap_div).css({display:"inline-block"});
				jQuery(post_image).animate({opacity: 0.3}, 500);
			},
			function()
			{
				//move out
				// set pinit-button wrap to none(hide it)
				// and lighten image
				jQuery(pinit_button_wrap_div).css({display:"none"});
				jQuery(post_image).animate({opacity: 1}, 500);


			});
	}

	/*
	Function : share_juice_get_caption_text
	Description :  The caption text should be a sibling of image , if it is not a hyperlink
	or it should be a sibling of hyperlink if the image is a inside a link
	Right now only wp-caption-text is captured. More enhancement next
	*/
	function share_juice_get_caption_text(post_image,parent_a)
	{
		if(jQuery(parent_a).length != 0 )
		{
			sibling_a = jQuery(parent_a).siblings('wp-caption-text');
			return jQuery(parent_a).siblings('.wp-caption-text').text();
		}
		else
		{
			return jQuery(post_image).siblings('.wp-caption-text').text();
		}

	}
	/*
	Function : share_juice_get_pinit_html
	Description : Providing pinit html enclosed inside a wrapper
	*/
	function share_juice_get_pinit_html(post_image,caption_text)
	{

		var img_src =  jQuery(post_image).attr('src');
		var page_url = document.location;

		var pinit_html = '<div class="share-juice-pinit-button-wrap" style="display:none"><a target="_blank" rel="nofollow" href="//pinterest.com/pin/create/button/?url='+encodeURIComponent(page_url)+'&media='+encodeURIComponent(img_src)+'&description='+escape(caption_text)+'" data-pin-do="buttonPin" data-pin-config="'+sj_pint_object.count_layout+'"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></div>';

		return pinit_html;

	}
}