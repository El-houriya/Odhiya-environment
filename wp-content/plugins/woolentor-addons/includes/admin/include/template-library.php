<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Woolentor_Template_Library{

    const TRANSIENT_KEY = 'woolentor_template_info';

    public static $endpoint = 'https://woolentor.com/library/wp-json/woolentor/v1/templates';
    public static $templateapi = 'https://woolentor.com/library/wp-json/woolentor/v1/templates/%s';

    public static $api_args = [];

    // Get Instance
    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        if ( is_admin() ) {
            add_action( 'admin_menu', [ $this, 'admin_menu' ], 225 );
            add_action( 'wp_ajax_woolentor_ajax_request', [ $this, 'templates_ajax_request' ] );

            add_action( 'wp_ajax_woolentor_ajax_get_required_plugin', [ $this, 'ajax_plugin_data' ] );
            add_action( 'wp_ajax_woolentor_ajax_plugin_activation', [ $this, 'ajax_plugin_activation' ] );
            add_action( 'wp_ajax_woolentor_ajax_theme_activation', [ $this, 'ajax_theme_activation' ] );
        }
        
        add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );

        self::$api_args = [
            'plugin_version' => WOOLENTOR_VERSION,
            'url'            => home_url(),
        ];

    }

    // Setter Endpoint
    function set_api_endpoint( $endpoint ){
        self::$endpoint = $endpoint;
    }
    
    // Setter Template API
    function set_api_templateapi( $templateapi ){
        self::$templateapi = $templateapi;
    }

    // Get Endpoint
    public static function get_api_endpoint(){
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && function_exists('woolentor_pro_template_endpoint') ){
            self::$endpoint = woolentor_pro_template_endpoint();
        }
        return self::$endpoint;
    }
    
    // Get Template API
    public static function get_api_templateapi(){
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && function_exists('woolentor_pro_template_url') ){
            self::$templateapi = woolentor_pro_template_url();
        }
        return self::$templateapi;
    }

    // Plugins Library Register
    public function admin_menu() {
        add_submenu_page(
            'woolentor_page', 
            esc_html__( 'Template Library', 'woolentor' ),
            esc_html__( 'Template Library', 'woolentor' ), 
            'manage_options', 
            'woolentor_templates', 
            [ $this, 'library_render_html' ] 
        );
    }

    public function library_render_html(){
        require_once WOOLENTOR_ADDONS_PL_PATH . 'includes/admin/include/templates_list.php';
    }

    public static function request_remote_templates_info( $force_update ) {
        global $wp_version;
        $body_args = apply_filters( 'httemplates/api/get_templates/body_args', self::$api_args );
        $request = wp_remote_get(
            self::get_api_endpoint(),
            [
                'timeout'    => $force_update ? 25 : 10,
                'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
                'body'       => $body_args,
                'sslverify'  => false,
            ]
        );
        $response = json_decode( wp_remote_retrieve_body( $request ), true );
        return $response;
    }

    /**
     * Retrieve template library and save as a transient.
     */
    public static function set_templates_info( $force_update = false ) {
        $transient = get_transient( self::TRANSIENT_KEY );

        if ( ! $transient || $force_update ) {
            $info = self::request_remote_templates_info( $force_update );
            set_transient( self::TRANSIENT_KEY, $info, DAY_IN_SECONDS );
        }

    }

    /**
     * Get template info.
     */
    public function get_templates_info( $force_update = false ) {
        if ( !get_transient( self::TRANSIENT_KEY ) || $force_update ) {
            self::set_templates_info( true );
        }
        return get_transient( self::TRANSIENT_KEY );
    }

    /**
     * Admin Scripts.
     */
    public function scripts( $hook ) {

        if( 'woolentor_page_woolentor_templates' == $hook ){

            // CSS
            wp_enqueue_style( 'woolentor-selectric' );
            wp_enqueue_style( 'woolentor-temlibray-style' );

            // JS
            wp_enqueue_script( 'woolentor-modernizr' );
            wp_enqueue_script( 'jquery-selectric' );
            wp_enqueue_script( 'jquery-ScrollMagic' );
            wp_enqueue_script( 'babel-min' );
            wp_enqueue_script( 'woolentor-templates' );
            wp_enqueue_script( 'woolentor-install-manager' );

        }

    }

    /**
     * Ajax request.
     */
    public function templates_ajax_request(){

        if ( isset( $_REQUEST ) ) {

            $template_id        = $_REQUEST['httemplateid'];
            $template_parentid  = $_REQUEST['htparentid'];
            $template_title     = $_REQUEST['httitle'];
            $page_title         = $_REQUEST['pagetitle'];

            $templateurl    = sprintf( self::get_api_templateapi(), $template_id );
            $response_data  = $this->templates_get_content_remote_request( $templateurl );
            $defaulttitle   = ucfirst( $template_parentid ) .' -> '.$template_title;


            $args = [
                'post_type'    => !empty( $page_title ) ? 'page' : 'elementor_library',
                'post_status'  => !empty( $page_title ) ? 'draft' : 'publish',
                'post_title'   => !empty( $page_title ) ? $page_title : $defaulttitle,
                'post_content' => '',
            ];

            $new_post_id = wp_insert_post( $args );

            update_post_meta( $new_post_id, '_elementor_data', $response_data['content']['content'] );
            update_post_meta( $new_post_id, '_elementor_template_type', $response_data['type'] );
            update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );
            
            update_post_meta( $new_post_id, '_elementor_page_settings', $response_data['page_settings'] );

            if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
                update_post_meta( $new_post_id, '_wp_page_template', !empty( $response_data['page_template'] ) ? $response_data['page_template'] : 'elementor_canvas' );
            }

            echo json_encode(
                array( 
                    'id'      => $new_post_id,
                    'edittxt' => !empty( $page_title ) ? esc_html__( 'Edit Page', 'move-pro' ) : esc_html__( 'Edit Template', 'move-pro' )
                )
            );
        }

        wp_die();
    }

    public function templates_get_content_remote_request( $templateurl ){
        $url = $templateurl;
        $response = wp_remote_get( $url, array(
            'timeout'   => 60,
            'sslverify' => false
        ) );
        $result = json_decode( wp_remote_retrieve_body( $response ), true );
        return $result;
    }

    /*
    * Ajax response required data
    */
    public function ajax_plugin_data(){
        if ( isset( $_POST ) ) {
            $freeplugins = explode( ',', $_POST['freeplugins'] );
            $proplugins = explode( ',', $_POST['proplugins'] );
            $themeinfo = explode( ',', $_POST['requiredtheme'] );
            if(!empty($_POST['freeplugins'])){$this->required_plugins( $freeplugins, 'free' );}
            if(!empty($_POST['proplugins'])){ $this->required_plugins( $proplugins, 'pro' );}
            if(!empty($_POST['requiredtheme'])){ $this->required_theme( $themeinfo, 'free' );}
        }
        wp_die();
    }

    /*
    * Required Plugins
    */
    public function required_plugins( $plugins, $type ) {
        foreach ( $plugins as $key => $plugin ) {

            $plugindata = explode( '//', $plugin );
            $data = array(
                'slug'      => isset( $plugindata[0] ) ? $plugindata[0] : '',
                'location'  => isset( $plugindata[1] ) ? $plugindata[0].'/'.$plugindata[1] : '',
                'name'      => isset( $plugindata[2] ) ? $plugindata[2] : '',
                'pllink'    => isset( $plugindata[3] ) ? 'https://'.$plugindata[3] : '#',
            );

            if ( ! is_wp_error( $data ) ) {

                // Installed but Inactive.
                if ( file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && is_plugin_inactive( $data['location'] ) ) {

                    $button_classes = 'button activate-now button-primary';
                    $button_text    = esc_html__( 'Activate', 'woolentor' );

                // Not Installed.
                } elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {

                    $button_classes = 'button install-now';
                    $button_text    = esc_html__( 'Install Now', 'woolentor' );

                // Active.
                } else {
                    $button_classes = 'button disabled';
                    $button_text    = esc_html__( 'Activated', 'woolentor' );
                }

                ?>
                    <li class="htwptemplata-plugin-<?php echo $data['slug']; ?>">
                        <h3><?php echo $data['name']; ?></h3>
                        <?php
                            if ( $type == 'pro' && ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {
                                echo '<a class="button" href="'.esc_url( $data['pllink'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'woolentor' ).'</a>';
                            }else{
                        ?>
                            <button class="<?php echo $button_classes; ?>" data-pluginopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                        <?php } ?>
                    </li>
                <?php

            }

        }
    }

    /*
    * Required Theme
    */
    public function required_theme( $themes, $type ){
        foreach ( $themes as $key => $theme ) {
            $themedata = explode( '//', $theme );
            $data = array(
                'slug'      => isset( $themedata[0] ) ? $themedata[0] : '',
                'name'      => isset( $themedata[1] ) ? $themedata[1] : '',
                'prolink'   => isset( $themedata[2] ) ? $themedata[2] : '',
            );

            if ( ! is_wp_error( $data ) ) {

                $theme = wp_get_theme();

                // Installed but Inactive.
                if ( file_exists( get_theme_root(). '/' . $data['slug'] . '/functions.php' ) && ( $theme->stylesheet != $data['slug'] ) ) {

                    $button_classes = 'button themeactivate-now button-primary';
                    $button_text    = esc_html__( 'Activate', 'woolentor' );

                // Not Installed.
                } elseif ( ! file_exists( get_theme_root(). '/' . $data['slug'] . '/functions.php' ) ) {

                    $button_classes = 'button themeinstall-now';
                    $button_text    = esc_html__( 'Install Now', 'woolentor' );

                // Active.
                } else {
                    $button_classes = 'button disabled';
                    $button_text    = esc_html__( 'Activated', 'woolentor' );
                }

                ?>
                    <li class="htwptemplata-theme-<?php echo $data['slug']; ?>">
                        <h3><?php echo $data['name']; ?></h3>
                        <?php
                            if ( !empty( $data['prolink'] ) ) {
                                echo '<a class="button" href="'.esc_url( $data['prolink'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'woolentor' ).'</a>';
                            }else{
                        ?>
                            <button class="<?php echo $button_classes; ?>" data-themeopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                        <?php } ?>
                    </li>
                <?php
            }


        }

    }

    /**
     * Ajax plugins activation request
     */
    public function ajax_plugin_activation() {

        if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['location'] ) || ! $_POST['location'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Plugin Not Found', 'woolentor' ),
                )
            );
        }

        $plugin_location = ( isset( $_POST['location'] ) ) ? esc_attr( $_POST['location'] ) : '';
        $activate    = activate_plugin( $plugin_location, '', false, true );

        if ( is_wp_error( $activate ) ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => $activate->get_error_message(),
                )
            );
        }

        wp_send_json_success(
            array(
                'success' => true,
                'message' => esc_html__( 'Plugin Successfully Activated', 'woolentor' ),
            )
        );

    }

    /*
    * Required Theme Activation Request
    */
    public function ajax_theme_activation() {

        if ( ! current_user_can( 'install_themes' ) || ! isset( $_POST['themeslug'] ) || ! $_POST['themeslug'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Sorry, you are not allowed to install themes on this site.', 'woolentor' ),
                )
            );
        }

        $theme_slug = ( isset( $_POST['themeslug'] ) ) ? esc_attr( $_POST['themeslug'] ) : '';
        switch_theme( $theme_slug );

        wp_send_json_success(
            array(
                'success' => true,
                'message' => __( 'Theme Activated', 'woolentor' ),
            )
        );
    }


}

Woolentor_Template_Library::instance();