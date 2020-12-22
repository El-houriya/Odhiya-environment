<?php

namespace ACFFrontend\Module;

use  ACFFrontend\Plugin ;
use  Elementor\Core\Base\Module ;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

class ACFEF_Module extends Module
{
    public  $main_actions = array() ;
    public  $submit_actions = array() ;
    public  $elementor_categories = array() ;
    public function get_name()
    {
        return 'acf_frontend_form';
    }
    
    public static function find_element_recursive( $elements, $widget_id )
    {
        foreach ( $elements as $element ) {
            if ( $widget_id == $element['id'] ) {
                return $element;
            }
            
            if ( !empty($element['elements']) ) {
                $element = self::find_element_recursive( $element['elements'], $widget_id );
                if ( $element ) {
                    return $element;
                }
            }
        
        }
        return false;
    }
    
    public function add_main_action( $id, $instance )
    {
        $this->main_actions[$id] = $instance;
    }
    
    public function get_main_actions( $id = null )
    {
        
        if ( $id ) {
            if ( !isset( $this->main_actions[$id] ) ) {
                return null;
            }
            return $this->main_actions[$id];
        }
        
        return $this->main_actions;
    }
    
    public function add_submit_action( $id, $instance )
    {
        $this->submit_actions[$id] = $instance;
    }
    
    public function get_submit_actions( $id = null )
    {
        
        if ( $id ) {
            if ( !isset( $this->submit_actions[$id] ) ) {
                return null;
            }
            return $this->submit_actions[$id];
        }
        
        return $this->submit_actions;
    }
    
    public function acfef_widgets()
    {
        // Include Widget files
        require_once __DIR__ . '/widgets/acf-frontend-form.php';
        require_once __DIR__ . '/widgets/acf-fields.php';
        require_once __DIR__ . '/widgets/submit_button.php';
        //require_once( __DIR__ . '/widgets/payment-form.php' );
        require_once __DIR__ . '/widgets/edit_post.php';
        require_once __DIR__ . '/widgets/edit_term.php';
        require_once __DIR__ . '/widgets/edit_button.php';
        require_once __DIR__ . '/widgets/edit_user.php';
        require_once __DIR__ . '/widgets/new_post.php';
        //require_once( __DIR__ . '/widgets/new_term.php' );
        require_once __DIR__ . '/widgets/new_user.php';
        require_once __DIR__ . '/widgets/trash_button.php';
        // Register widget
        $elementor = Plugin::instance()->elementor();
        $elementor->widgets_manager->register_widget_type( new Widgets\Edit_Button_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\Edit_Post_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\New_Post_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\Edit_Term_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\Delete_Post_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\Edit_User_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\New_User_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\ACF_Frontend_Form_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\ACF_Fields_Widget() );
        $elementor->widgets_manager->register_widget_type( new Widgets\Submit_Post_Widget() );
    }
    
    public function acfef_widget_categories( $elements_manager )
    {
        $categories = [
            'acfef-forms'   => [
            'title' => __( 'FRONTEND FORMS', 'acf-frontend-form-element' ),
            'icon'  => 'fa fa-plug',
        ],
            'acfef-buttons' => [
            'title' => __( 'FRONTEND BUTTONS', 'acf-frontend-form-element' ),
            'icon'  => 'fa fa-plug',
        ],
        ];
        foreach ( $categories as $name => $args ) {
            $this->elementor_categories[$name] = $args;
            $elements_manager->add_category( $name, $args );
        }
    }
    
    public function acfef_dynamic_tags( $dynamic_tags )
    {
        
        if ( class_exists( 'ElementorPro\\Modules\\DynamicTags\\Tags\\Base\\Data_Tag' ) ) {
            \Elementor\Plugin::$instance->dynamic_tags->register_group( 'acfef-user-data', [
                'title' => 'User',
            ] );
            require_once __DIR__ . '/dynamic-tags/user-local-avatar.php';
            require_once __DIR__ . '/dynamic-tags/author-local-avatar.php';
            $dynamic_tags->register_tag( new DynamicTags\User_Local_Avatar_Tag() );
            $dynamic_tags->register_tag( new DynamicTags\Author_Local_Avatar_Tag() );
        }
    
    }
    
    public function acfef_document_types()
    {
        require_once __DIR__ . '/documents/post-form.php';
        \Elementor\Plugin::$instance->documents->register_document_type( 'post_form', Documents\PostFormTemplate::get_class_full_name() );
    }
    
    public function acfef_icon_file()
    {
        wp_enqueue_style(
            'acfef-icon',
            ACFEF_URL . 'includes/assets/css/icon.css',
            array(),
            ACFEF_ASSETS_VERSION
        );
        wp_enqueue_style(
            'acfef-editor',
            ACFEF_URL . 'includes/assets/css/editor.min.css',
            array(),
            ACFEF_ASSETS_VERSION
        );
    }
    
    public function migrate_field_controls()
    {
        if ( !get_option( 'acfef_migrated_2_5_5' ) ) {
            require_once __DIR__ . '/classes/migrate_settings.php';
        }
    }
    
    public function __construct()
    {
        require_once __DIR__ . '/classes/form_submit.php';
        require_once __DIR__ . '/classes/save_fields.php';
        require_once __DIR__ . '/classes/action_base.php';
        //actions
        require_once __DIR__ . '/actions/term.php';
        require_once __DIR__ . '/actions/user.php';
        require_once __DIR__ . '/actions/post.php';
        require_once __DIR__ . '/classes/content_tab.php';
        $this->add_main_action( 'user', new Actions\ActionUser() );
        $this->add_main_action( 'post', new Actions\ActionPost() );
        $this->add_main_action( 'term', new Actions\ActionTerm() );
        add_action( 'elementor/elements/categories_registered', array( $this, 'acfef_widget_categories' ) );
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'acfef_widgets' ] );
        add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'acfef_dynamic_tags' ] );
        add_action( 'elementor/documents/register', [ $this, 'acfef_document_types' ] );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'acfef_icon_file' ] );
        add_action( 'init', [ $this, 'migrate_field_controls' ] );
    }

}