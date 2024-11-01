var sj_is_form_submit  = false;
var active_panel_index = 0;
jQuery(document).ready(function()
    {

        set_video_links();
        set_accordion_panels();
        
        set_social_buttons_table_functions();
        set_helper_box();
        set_shortner_fields();


    });

function set_video_links()
{

    jQuery('a.share-juice-expand-div').click(function()
        {
            event.preventDefault();

            var link = this;
            var ul = jQuery(this).siblings('ul');

            if(jQuery(ul).css('display') == "none")
            jQuery(link).text("[-]");
            else
            jQuery(link).text("[+]");

            jQuery(this).siblings('ul').toggle('medium', function()
                {

                    // Animation complete.
                });


        });
}

function set_accordion_panels()
{
    var acc = jQuery( "#share-juice-accordion" );

    var accordion = jQuery( "#share-juice-accordion" );
    if(accordion.length == 0)
    return;

    if(typeof jQuery.cookie('sj_accordion_selected_index') === undefined)
    active_panel_index = 0;
    else
    active_panel_index = jQuery.cookie('sj_accordion_selected_index');

    jQuery(accordion).accordion(
        {
            heightStyle: "content",    autoHeight: false,
            clearStyle: true
        });


    //jQuery(accordion).accordion({event:"click hoverintent"});
    jQuery(accordion).accordion( "option", "active", parseInt(active_panel_index, 10));
    jQuery(accordion).on("click","h3",function(event)
        {
            var active = jQuery(accordion).accordion( "option", "active" );

            jQuery.cookie('sj_accordion_selected_index',active);

        });

    jQuery('body').submit(function(){sj_is_form_submit = true});

    jQuery(window).unload( function ()
        {
            if(typeof jQuery.cookie('sj_accordion_selected_index') !== undefined && sj_is_form_submit == false)
            jQuery.removeCookie('sj_accordion_selected_index');

        });


}
function set_helper_box()
{
    jQuery(".helper").parent().hover(function()
        {
            var help = jQuery(this).find(".helper");

            if(jQuery(help).length != 0)
            {

                jQuery(help).show();
            }

        },
        function()
        {

            var help = jQuery(this).find(".helper");

            if(jQuery(help).length != 0)
            jQuery(help).hide();


        });

}
function set_delete_prompt()
{
    jQuery(".delete-prompt").bind('click',function(e)
        {

            var action = confirm('do you want to delete the item?');
            if(action)
            {
                //delete the item the way you want
            }

        });
}

function set_print_social_buttons()
{
    if(jQuery("#use_sj_print_function").is(':checked'))
    {
        jQuery("#share-juice-print-style-options-group").show();
        jQuery("#share-juice-php-function-field-group").hide();
    }
    else
    {

        jQuery("#share-juice-print-style-options-group").hide();
        jQuery("#share-juice-php-function-field-group").show();


    }
    jQuery("#use_sj_print_function").change(function()
        {

            if(jQuery(this).is(':checked'))
            {
                jQuery("#share-juice-print-style-options-group").show();
                jQuery("#share-juice-php-function-field-group").hide();

            }
            else
            {
                jQuery("#share-juice-print-style-options-group").hide();
                jQuery("#share-juice-php-function-field-group").show();

            }
        });

}
function set_shortner_fields()
{
    //bitly fields show

    if(jQuery("[name=url_sht_service]:checked").val() === "bitly")
    {
        jQuery("#sj_bitly_api_fields").show();
    }

    jQuery("[name=url_sht_service]").change(function()
        {

            if(jQuery("[name=url_sht_service]:checked").val() === "bitly")
            {
                jQuery("#sj_bitly_api_fields").show();

            }
            else
            {
                jQuery("#sj_bitly_api_fields").hide();
            }
        });

}
function set_social_buttons_table_functions()
{
    jQuery(".delete-prompt").bind('click',function(e)
        {

            var action = confirm('do you want to delete the item?');
            if(action)
            {
                //delete the item the way you want
            }
            else
            {
                e.preventDefault();

            }

        });

    function showOKCancelPromt()
    {
        var action = confirm('do you want to delete the item?');
        if(action)
        {
            //delete the item the way you want
        }
        else
        {
            e.preventDefault();

        }
    }

}
/*
function set_twitter_buttons()
{
//twitter button api admin

var twitter_button_type_value = jQuery("[name=twitter_button_type]:checked").val();

show_twitter_fields(twitter_button_type_value);

jQuery("[name=twitter_button_type]").change(function()
{
var twitter_button_type_value = jQuery("[name=twitter_button_type]:checked").val();

show_twitter_fields(twitter_button_type_value);


});


function show_twitter_fields(twitter_button_type_value)
{


if(twitter_button_type_value === "share")
{
jQuery("#share-juice-twitter-share-link-group").show();
jQuery("#share-juice-twitter-follow-group").hide();
jQuery("#share-juice-twitter-hashtag-group").hide();
jQuery("#share-juice-twitter-mention-group").hide();

}

if(twitter_button_type_value === "follow")
{

jQuery("#share-juice-twitter-share-link-group").hide();
jQuery("#share-juice-twitter-follow-group").show();
jQuery("#share-juice-twitter-hashtag-group").hide();
jQuery("#share-juice-twitter-mention-group").hide();
}

if(twitter_button_type_value === "hashtag")
{

jQuery("#share-juice-twitter-share-link-group").hide();
jQuery("#share-juice-twitter-follow-group").hide();
jQuery("#share-juice-twitter-hashtag-group").show();
jQuery("#share-juice-twitter-mention-group").hide();
}
if(twitter_button_type_value === "mention")
{
jQuery("#share-juice-twitter-share-link-group").hide();
jQuery("#share-juice-twitter-follow-group").hide();
jQuery("#share-juice-twitter-hashtag-group").hide();
jQuery("#share-juice-twitter-mention-group").show();


}


}

}
*/

function register_click_social_checkbox()
{
    if(jQuery('#share-juice-show-social-tab').is(':checked'))
    {
        jQuery('#share-juice-social-list-direction').parent().show();
    }
    else
    {
        jQuery('#share-juice-social-list-direction').parent().hide();
    }

    jQuery('#share-juice-show-social-tab').change(function()
        {

            if(jQuery(this).is(':checked'))
            {
                jQuery('#share-juice-social-list-direction').parent().show();
            }
            else
            {
                jQuery('#share-juice-social-list-direction').parent().hide();
            }


        });
}



/*Something useful

wizard = jQuery("#accordion").accordion({
event: 'click',
active: 0,
autoheight: true,
animated: "bounceslide",
icons: { 'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus' },
change: function (event, ui) { gCurrentIndex = jQuery(this).find("h4").index(ui.newHeader[0]); }
});

*/
/*
jQuery.event.special.hoverintent = {
setup: function() {
jQuery( this ).bind( "mouseover", jQuery.event.special.hoverintent.handler );
},
teardown: function() {
jQuery( this ).unbind( "mouseover", jQuery.event.special.hoverintent.handler );
},
handler: function( event ) {
var currentX, currentY, timeout,
args = arguments,
target = jQuery( event.target ),
previousX = event.pageX,
previousY = event.pageY;
function track( event ) {
currentX = event.pageX;
currentY = event.pageY;
};
function clear() {
target
.unbind( "mousemove", track )
.unbind( "mouseout", clear );
clearTimeout( timeout );
}
function handler() {
var prop,
orig = event;
if ( ( Math.abs( previousX - currentX ) +
Math.abs( previousY - currentY ) ) < 7 ) {
clear();
event = jQuery.Event( "hoverintent" );
for ( prop in orig ) {
if ( !( prop in event ) ) {
event[ prop ] = orig[ prop ];
}
}
// Prevent accessing the original event since the new event
// is fired asynchronously and the old event is no longer
// usable (#6028)
delete event.originalEvent;
target.trigger( event );
} else {
previousX = currentX;
previousY = currentY;
timeout = setTimeout( handler, 700 );
}
}
timeout = setTimeout( handler, 700 );
target.bind({
mousemove: track,
mouseout: clear
});
}
};
*/

