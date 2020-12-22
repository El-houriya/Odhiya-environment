<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Woolentor_Admin_Setting{

    public function __construct(){
        add_action('admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        $this->woolentor_admin_settings_page();
    }

    /*
    *  Setting Page
    */
    public function woolentor_admin_settings_page() {
        require_once('include/class.settings-api.php');
        require_once('include/template-library.php');
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){
            require_once WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/admin/admin-setting.php';
        }else{
            require_once('include/admin-setting.php');
        }
    }

    /*
    *  Enqueue admin scripts
    */
    public function enqueue_scripts( $hook ){

        if( $hook === 'woolentor_page_woolentor' or $hook === 'woolentor_page_woolentor_templates' ){

            wp_enqueue_style( 'woolentor-admin' );
            // wp core styles
            wp_enqueue_style( 'wp-jquery-ui-dialog' );
            // wp core scripts
            wp_enqueue_script( 'jquery-ui-dialog' );

            wp_enqueue_script( 'woolentor-admin-main' );

        }

    }

}

new Woolentor_Admin_Setting();