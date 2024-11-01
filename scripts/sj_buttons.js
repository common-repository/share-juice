var sj_floatbar_slide_direction = 'left';
var sj_floatbar_width ;
jQuery(document).ready(function()
    {

        //To set floating bar
        sj_set_floating_bar();

     

        //sj_add_script_to_facebook_share();

        sj_comment_button_attach_click();

        

     

        

        sj_get_all_js();


    });

//This function sets the floating bar in position and
//shows it
function sj_set_floating_bar()
{
    //First cache it
    var floating_bar_div = jQuery(".share-juice-floating-bar")

    //If the monitor resolution is less than 800
    // don't show
    if(screen.width <= 800)
    return;

    //if the length == 0 return
    if(floating_bar_div.length == 0)
    return;

    var sj_start_content_div = jQuery("#sj_start_content");
    var sj_end_content_div = jQuery("#sj_end_content");

    var top = jQuery(sj_start_content_div).offset().top;
    var left = jQuery(sj_start_content_div).offset().left;
    jQuery(floating_bar_div).appendTo('body');

    jQuery(floating_bar_div).css({position:'absolute',top:top,left:left - 60});

    setTimeout(function()
        {

            jQuery(floating_bar_div).fadeIn('slow',function(){});
        }, 100);


    jQuery(window).scroll(function ()
        {
            var wintop = jQuery(window).scrollTop();
            var bar_start_top = jQuery(floating_bar_div).offset().top;
            var content_start_top = jQuery(sj_start_content_div).offset().top;

            var content_end_top = jQuery( sj_end_content_div).offset().top;

            length =  jQuery(floating_bar_div).length;
            //console.log("wintop="+wintop);
            //console.log("content_start_top="+content_start_top);
            // console.log("bar_start_top="+bar_start_top );
            // console.log("content_end_top="+content_end_top );

            var in_content =false;
            if(wintop > content_start_top && wintop < content_end_top )
            {
                jQuery(floating_bar_div).css({position:'fixed',top:0});
            }
            if(bar_start_top >  content_end_top)
            {
                jQuery(".share-juice-floating-bar").css({position:'absolute',top:content_end_top -length});
            }

            if(bar_start_top <  content_start_top)
            {
                jQuery(floating_bar_div).css({position:'absolute',top:content_start_top });
            }


        });

    jQuery( "#floatbar-img-close" ).click(function()
        {
            sj_floatbar_width = jQuery(floating_bar_div).width();

            jQuery( floating_bar_div).animate(
                {
                    width:10
                }, 500,function()
                {
                    // Animation complete.
                    jQuery('#floatbar-img-open').css({display:"block"});
                    jQuery('#floatbar-img-close').css({display:"none"});
                    jQuery('#share-juice-float-box-text').hide();

                });

        });
    jQuery( "#floatbar-img-open" ).click(function()
        {

            jQuery( floating_bar_div).animate(
                {
                    width:sj_floatbar_width
                }, 500,function()
                {


                    jQuery('#floatbar-img-open').css({display:"none"});
                    jQuery('#floatbar-img-close').css({display:"block"});
                    jQuery('#share-juice-float-box-text').show();
                });


        });

 // changed from just resize in 3.0 to bind as it seems to work better in IE... needs to be tested
    jQuery(window).bind('resize', function ()
        {

            var top = jQuery(sj_start_content_div).offset().top;
            var left = jQuery(sj_start_content_div).offset().left;
            jQuery(floating_bar_div).css({top:top,left:left - 60});

        });



}
function sj_add_script_to_facebook_share()
{
    var fb = jQuery("a[name='fb_share']");
    jQuery(fb).each(function()
        {
            if(jQuery(this).length == 0)
            jQuery.getScript('http://static.ak.fbcdn.net/connect.php/js/FB.Share');

        });
}
function    sj_comment_button_attach_click()
{
    jQuery(".sj_comment").each(function()
        {

            jQuery(this).click(function()
                {
                    jQuery('html, body').animate({scrollTop: jQuery("#comments").offset().top}, 2000);

                });

        });
}


function sj_get_all_js()
{

    //Linked in does not work like this, tumblr has no js

    // Problem in Kindle
    if(jQuery(".share-juice-button-kindle").length > 0)
    {
        jQuery.getScript("https://d1xnn692s7u6t6.cloudfront.net/widget.js");



        (function k(){window.$SendToKindle&&window.$SendToKindle.Widget?$SendToKindle.Widget.init({"asin":sj_amazon_listing}):setTimeout(k,500);})();
    }


    //facebook like , make app id mandatory
    if(jQuery(".share-juice-button-facebook-like").length > 0)
    {
        var appId = null;
        if(typeof sj_ajax_object.share_juice_fb_appId !== undefined)
        appId = sj_ajax_object.share_juice_fb_appId;
        jQuery('<div id="fb-root"></div>').appendTo('body');
        (function(d, s, id)
            {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId="+appId;
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
    }

    // Flattr
    if(jQuery(".share-juice-button-flattr").length > 0)
    {
        (function()
            {
                var s = document.createElement('script');
                var t = document.getElementsByTagName('script')[0];

                s.type = 'text/javascript';
                s.async = true;
                s.src = '//api.flattr.com/js/0.6/load.js?'+
                'mode=auto&uid=gargamel&language=sv_SE&category=text';

                t.parentNode.insertBefore(s, t);
            })();

    }

    // Google Plus
    if(jQuery(".share-juice-button-google-plus").length > 0)
    jQuery.getScript("https://apis.google.com/js/plusone.js");

    // Twitter Anonymous function
    if(jQuery(".share-juice-button-twitter").length > 0)
    {
        var x = 0;

        !(function(d,s,id)
            {
                var js,
                fjs=d.getElementsByTagName(s)[0],
                p=/^http:/.test(d.location)?'http':'https';

                if(!d.getElementById(id))
                {
                    js=d.createElement(s);
                    js.id=id;
                    js.src=p+'://platform.twitter.com/widgets.js';
                    fjs.parentNode.insertBefore(js,fjs);
                }
            }(document, 'script', 'twitter-wjs'))        ;
    }

    // Buffer
    if(jQuery(".share-juice-button-buffer").length > 0)
    jQuery.getScript("http://static.bufferapp.com/js/button.js");

    // Stumble Upon
    if(jQuery(".share-juice-button-stumbleupon").length > 0)
    {
        (function()
            {
                var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
                li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
            })();
    }

    /* Not available lazy :(
    //Facebook Share
    if(jQuery(".share-juice-button-facebook-share").length > 0)
    {
    jQuery.getScript("http://static.ak.fbcdn.net/connect.php/js/FB.Share" );
    }

    */


}