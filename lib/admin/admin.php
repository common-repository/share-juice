<?php

/**
* @author Ashvini Saxena
* @copyright 2012
*/

class SJAdminMain
{

    private $sj_admin_buttons;
    private $sj_admin_global;
    private $sj_admin_groups;
    private $sj_admin_css;
    private $sj_admin_admin;
    private $sj_admin_social;

    public
    function __construct()
    {

        require_once ('admin_buttons.php');
        require_once ('admin_global.php');
        require_once ('admin_css.php');
        require_once ('admin_admin.php');
		require_once (dirname(__FILE__).'/validation/admin.php')	;
		require_once (dirname(__FILE__).'/validation/global.php');

        if (defined('SJ_ADDON_ADMIN_SHARE_ACTIVE')) {
            $this->sj_admin_social = new SJ_Admin_Social();
        }

        $this->sj_admin_buttons = new SJAdminButtons();
        $this->sj_admin_global = new SJAdminGlobal();
        $this->sj_admin_admin = new SJAdminAdmin();
        $this->sj_admin_css = new SJAdminCSS();

        add_action('admin_menu', array($this,'register_custom_menu_page'));
        add_action('admin_print_styles', array($this,'admin_css'));
        add_action('admin_enqueue_scripts',array($this,'admin_js'));

        add_action( 'admin_notices','share_juice_common_admin_notices' );

    }

    public
    function admin_js()
    {

        //if(!share_juice_is_admin_page())    return;

        wp_enqueue_script('jquery-ui-accordion');

        wp_enqueue_script(
            'share-juice-jquery-cookie'
            ,SJ_SCRIPTS_URL_BASE .'/jquery.cookie.js',
            array('jquery')
        );
        wp_enqueue_script(
            'share-juice-widgets',
            SJ_SCRIPTS_URL_BASE .'/widgets.js',
            array(
                'jquery',
                'jquery-ui-accordion'
            )
        );

        wp_enqueue_script(
            'share-juice-admin-js',
            SJ_SCRIPTS_URL_BASE .'/sj_admin.js',
            array(
                'jquery',
                'jquery-ui-accordion',
                'share-juice-jquery-cookie',
                'share-juice-widgets'
				)
        );

        
    }
    public
    function admin_css()
    {
        $is_admin = share_juice_is_admin_page();
        $is_post  = share_juice_is_post_page();
        if (!
            (
                $is_admin || $is_post
            )
        )
        return;

        wp_enqueue_style('share-juice-ui', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css');
        wp_enqueue_style('share-juice-widgets',  SJ_SCRIPTS_URL_BASE . '/widgets.css',array('share-juice-ui'));

        wp_enqueue_style('share-juice-admin-css', SJ_SCRIPTS_URL_BASE . '/admin_style.css',array('share-juice-ui','share-juice-widgets'));

    }

    public
    function register_custom_menu_page()
    {
        global $share_juice_options;
        //add main menu page
        add_menu_page('Share Juice', 'Share Juice', 'administrator', 'share-juice', array($this,'show_options'),SJ_IMG_URL.'/sjp_menu_icon.png');
        //global settings page
        add_submenu_page('share-juice', 'Global Settings', 'Global Settings', 'administrator',
            'sj-global-settings', array($this->sj_admin_global,'validate_and_generate_forms'));
        //Social buttons page
        add_submenu_page('share-juice', 'Social Buttons', 'Social Buttons', 'administrator',
            'sj-social-buttons', array($this->sj_admin_buttons,
                'validate_and_generate_forms'));
      
        //Admin pages
        add_submenu_page('share-juice', 'Share Juice Admin Options', 'Admin', 'administrator',
            'sj-admin-settings', array($this->sj_admin_admin,'validate_and_generate_forms'));
        //Css edit page
        add_submenu_page('share-juice', 'CSS Edit', 'CSS Edit', 'administrator',
            'sj-css-edit', array($this->sj_admin_css,'validate_and_generate_forms'));
        //remove the link that wordpress inserts
        remove_submenu_page('share-juice','share-juice');
        // debug info page if condition is met
        if (isset($share_juice_options['admin_options']['show_debug_info']) && $share_juice_options['admin_options']['show_debug_info'] == true)         add_submenu_page('share-juice', 'Debug', 'Debug', 'administrator','sj-debug', array($this,'show_debug_info'));


    }

    public
    function show_debug_info()
    {
        global $share_juice_options;
        global $share_juice_upgrade_inst;

        $share_juice_activation_error = get_option('share_juice_activation_error');

        if (!empty($share_juice_activation_error)) {
            echo'<h2>Activation Errors</h2>';
		 echo wp_kses($share_juice_activation_error,wp_kses_allowed_html('data'));	
        }


        echo '<h2>Some useful information</h2>';
        /*    echo '<h3>Upgrade check URL</h3>';

        echo 'Upgrade check URL = '. $share_juice_upgrade_inst->get_upgrade_url();
        echo '<h3>Contents of option array</h3>';*/
        echo '<pre>';
        print_r($share_juice_options);
        echo '</pre>';
    }

    public
    function show_options()
    {
        global $share_juice_options;
        echo '<h2>This is your saved settings</h2>';
        echo '<p>This is just for info</p>';
        echo '<pre>';
        print_r($share_juice_options);
        echo '</pre>';
    }

}
?>