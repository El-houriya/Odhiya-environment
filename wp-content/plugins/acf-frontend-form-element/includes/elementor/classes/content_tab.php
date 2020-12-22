<?php
namespace ACFFrontend\Module\Classes;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class ContentTab{

    public function register_form_structure_section( $widget ) {

		$widget->start_controls_section(
			'fields_section',
			[
				'label' => __( 'Fields', 'acf-frontend-form-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				
			]
		);

        $this->fields_controls( $widget, false );
        
		$widget->end_controls_section();

    }

    public function fields_controls( $widget, $steps = true ){
		$field_group_choices = acfef_get_acf_field_group_choices();
        $field_choices = acfef_get_acf_field_choices();
        
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'field_type',
			[
				'label' => __( 'Field Type', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => 'ACF_fields',
				'groups' => $widget->get_field_type_options(),
			]
		);

		if( $steps ) $widget->register_step_controls( $repeater, false );

		$repeater->add_control(
			'field_groups_select',
			[
				'label' => __( 'ACF Field Groups', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $field_group_choices,
				'condition' => [
					'field_type' => 'ACF_field_groups',
				],
			]
		);
		$repeater->add_control(
			'fields_select',
			[
				'label' => __( 'ACF Fields', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $field_choices,
				'condition' => [
					'field_type' => 'ACF_fields',
				],
			]
		);		

		$repeater->add_control(
			'fields_select_exclude',
			[
				'label' => __( 'Exclude Specific Fields', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $field_choices,
				'condition' => [
					'field_type' => [ 'ACF_field_groups' ],
				],
			]
		);		

		$repeater->start_controls_tabs(
			'field_settings_tabs'
		);
		
		$custom_layouts = [ 'ACF_field_groups', 'ACF_fields', 'message', 'recaptcha', 'step' ];
		$text_fields = [ 'term_name', 'username', 'email', 'first_name', 'last_name', 'nickname', 'display_name', 'bio', 'title', 'excerpt', 'sku', 'product_title', 'price', 'sale_price',	'description', 'short_description', 'comment', 'author', 'author_email', 'site_title', 'site_tagline' ];

		$repeater->start_controls_tab(
			'field_content_tab',
			[
				'label' => __( 'Content', 'acf-frontend-form-element' ),
				'condition' => [
					'field_type!' => $custom_layouts,
				]
			]
		);

		$repeater->add_control(
			'field_label_on',
			[
				'label' => __( 'Show Label', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',
				'default' => 'true',
				'condition' => [
					'field_type!' => $custom_layouts,
				],
				
			]
		);
		$repeater->add_control(
			'field_label',
			[
				'label' => __( 'Label', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Field Label', 'acf-frontend-form-element' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type!' => $custom_layouts,
					'field_label_on' => 'true',
				],
			]
		);				
		
		$repeater->add_control(
			'field_placeholder',
			[
				'label' => __( 'Placeholder', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Field Placeholder', 'acf-frontend-form-element' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => $text_fields,
				],
			]
		);			
		$repeater->add_control(
			'field_default_value',
			[
				'label' => __( 'Default Value', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'This will populate a field if no value has been given yet. You can use shortcodes from other text fields. Foe example: [$acf:field_name]', 'acf-frontend-form-element' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => $text_fields,
				],
			]
		);	
		
		$repeater->add_control(
			'default_featured_image',
			[
				'label' => __( 'Default Featured Image', 'acf-frontend-form-element' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'field_type' => 'featured_image',
				],
			]
		);

		$repeater->add_control(
			'editor_type',
			[
				'label' => __( 'Type', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'wysiwyg' => __( 'Text Editor', 'acf-frontend-form-element' ),
					'textarea' => __( 'Text Area', 'acf-frontend-form-element' ),
				],
				'default' => 'wysiwyg',
				'condition' => [
					'field_type' => 'content',
				],
			]
		);
		
		
		$repeater->add_control(
			'field_instruction',
			[
				'label' => __( 'Instruction', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Field Instruction', 'acf-frontend-form-element' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type!' => $custom_layouts,
				],
			]
		);

		$repeater->add_control(
			'field_required',
			[
				'label' => __( 'Required', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',
				'condition' => [
					'field_type!' => $custom_layouts,
				],
			]
		);				
		$repeater->add_control(
			'field_hidden',
			[
				'label' => __( 'Hidden', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'This will hide the field. Useful if used with a default value. Warning: do not hide required field if they do not have a default value!', 'acf-frontend-form-element' ),
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',
				'condition' => [
					'field_type!' => $custom_layouts,
				],
			]
		);			
		$repeater->add_control(
			'field_disabled',
			[
				'label' => __( 'Disabled', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'This will prevent users from editing the field and the data will not be sent.', 'acf-frontend-form-element' ),
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',
				'condition' => [
					'field_type!' => $custom_layouts,
				],
			]
		);		
		$repeater->add_control(
			'field_readonly',
			[
				'label' => __( 'Readonly', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'This will prevent users from editing the field.', 'acf-frontend-form-element' ),
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',
				'condition' => [
					'field_type' => $text_fields,
				],
			]
		);			
	
		$repeater->add_control(
			'field_message',
			[
				'label' => __( 'Message', 'acf-frontend-form-element' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'You can add here text, images template shortcodes, and more', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Type your message here', 'acf-frontend-form-element' ),
				'condition' => [
					'field_type' => 'message',
				]
			]
		);
        $repeater->add_control(
			'post_type_field_options',
			[
				'label' => __( 'Post Types to Choose From', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'default' => [
					'subscriber',
				],
				'options' => acf_get_pretty_post_types(), 
				'condition' => [
					'field_type' => 'post_type',
				],
			]
		);
		$repeater->add_control( 
			'post_type_appearance', 
			[
				'label'  => __( 'Appearance', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'default'  => 'select',
				'options' => [
					'radio' => __( 'Radio Buttons', 'acf-frontend-form-element' ),
					'select' => __( 'Select', 'acf-frontend-form-element' ),
				],
				'condition' => [
					'field_type' => [ 'post_type' ],
				],
			]
		);
		$repeater->add_control( 
			'post_type_radio_layout', 
			[
				'label'  => __( 'Layout', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'default'  => 'select',
				'options' => [
					'horizontal' => __( 'Horizontal', 'acf-frontend-form-element' ),
					'vertical' => __( 'Vertical', 'acf-frontend-form-element' ),
				],
				'condition' => [
					'field_type' => [ 'post_type' ],
					'post_type_appearance' => 'radio',
				],
			]
		);
		$repeater->add_control(
			'default_post_type',
			[
				'label' => __( 'Default Post Type Option', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [
					'subscriber',
				],
				'options' => acf_get_pretty_post_types(), 
				'condition' => [
					'field_type' => 'post_type',
				],
			]
		);
		$repeater->add_control(
			'role_field_options',
			[
				'label' => __( 'Roles to Choose From', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'default' => [
					'subscriber',
				],
				'options' => acfef_get_user_roles(), 
				'condition' => [
					'field_type' => 'role',
				],
			]
		);
		$repeater->add_control( 
			'role_appearance', 
			[
				'label'  => __( 'Appearance', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'default'  => 'select',
				'options' => [
					'radio' => __( 'Radio Buttons', 'acf-frontend-form-element' ),
					'select' => __( 'Select', 'acf-frontend-form-element' ),
				],
				'condition' => [
					'field_type' => [ 'role' ],
				],
			]
		);
		$repeater->add_control( 
			'role_radio_layout', 
			[
				'label'  => __( 'Layout', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'default'  => 'select',
				'options' => [
					'horizontal' => __( 'Horizontal', 'acf-frontend-form-element' ),
					'vertical' => __( 'Vertical', 'acf-frontend-form-element' ),
				],
				'condition' => [
					'field_type' => [ 'role' ],
					'role_appearance' => 'radio',
				],
			]
		);
		$repeater->add_control(
			'default_role',
			[
				'label' => __( 'Default Role Option', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [
					'subscriber',
				],
				'options' => acfef_get_user_roles(), 
				'condition' => [
					'field_type' => 'role',
				],
			]
		);
		$repeater->add_control(
			'password_strength',
			[
				'label' => __( 'Password Strength', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => '3',
				'options' => [
					'1'  => __( 'Very Weak', 'acf-frontend-form-element' ),
					'2' => __( 'Weak', 'acf-frontend-form-element' ),
					'3' => __( 'Medium', 'acf-frontend-form-element' ),
					'4' => __( 'Strong', 'acf-frontend-form-element' ),
				],
				'condition' => [
					'field_type' => 'password',
				],
			]
		);
        $repeater->add_control( 
			'field_taxonomy', 
			[
				'label'  => __( 'Taxonomy', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default'  => 'category',
				'options' => acf_get_taxonomy_labels(),
				'condition' => [
					'field_type' => 'taxonomy',
				],
			]
		 );
		 $repeater->add_control( 
			'field_taxonomy_appearance', 
			[
				'label'  => __( 'Appearance', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'default'  => 'checkbox',
				'groups' => [
					'multi' => [
						'label' => __( 'Multiple Value', 'acf-frontend-form-element' ),
						'options' => [
							'checkbox' => __( 'Checkboxes', 'acf-frontend-form-element' ),
							'multi_select' => __( 'Multi Select', 'acf-frontend-form-element' ),
						],
					],
					'single' => [
						'label' => __( 'Single Value', 'acf-frontend-form-element' ),
						'options' => [
							'radio' => __( 'Radio Buttons', 'acf-frontend-form-element' ),
							'select' => __( 'Select', 'acf-frontend-form-element' ),
						],
					],
				],
				'condition' => [
					'field_type' => [ 'taxonomy', 'categories', 'tags', 'product_categories', 'product_tags' ],
				],
			]
		 );
		 $repeater->add_control(
			'field_add_term',
			[
				'label' => __( 'Add Term', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',	
				'condition' => [
					'field_type' => [ 'taxonomy', 'categories', 'tags', 'product_categories', 'product_tags' ],
				],				
			]
		);
		$repeater->add_control(
			'set_as_username',
			[
				'label' => __( 'Set as username', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',	
				'condition' => [
					'field_type' => 'email',
				],				
			]
		);
		$repeater->add_control(
			'allow_edit',
			[
				'label' => __( 'Allow Edit', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'description' => __( 'WARNING: allowing your users to change their username might affect your existing urls and their SEO rating','acf-frontend-form-element' ),
				'return_value' => 'true',	
				'condition' => [
					'field_type' => 'username',
				],				
			]
		);
		$repeater->add_control(
			'edit_password',
			[
				'label' => __( 'Edit Password Button', 'acf-frontend-form-element' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Edit Password', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Edit Password', 'acf-frontend-form-element' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => [ 'password' ],
				],
			]
		);	
		$repeater->add_control(
			'cancel_edit_password',
			[
				'label' => __( 'Cancel Edit Button', 'acf-frontend-form-element' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Cancel', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Cancel', 'acf-frontend-form-element' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => [ 'password' ],
				],
			]
		);	
		 $repeater->add_control(
			'ui_on',
			[
				'label' => __( 'On Text', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Yes', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => [ 'manage_stock', 'sold_individually' ],
				],
			]
		);	
		$repeater->add_control(
			'ui_off',
			[
				'label' => __( 'Off Text', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'No', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => [ 'manage_stock', 'sold_individually' ],
				],
			]
		);	
		 $repeater->add_control(
			'stock_choices',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'seperator' => 'before',
				'raw' => "<h3>Choices</h3>",
				'condition' => [
					'field_type' => 'stock_status',
				],
			]
		);
		$repeater->add_control(
			'instock',
			[
				'label' => __( 'In stock', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'In stock', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => 'stock_status',
				],
			]
		);	
		$repeater->add_control(
			'outofstock',
			[
				'label' => __( 'Out of stock', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Out of stock', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => 'stock_status',
				],
			]
		);	
		$repeater->add_control(
			'backorder',
			[
				'label' => __( 'On backorder', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'On backorder', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => 'stock_status',
				],
			]
		);		
		$repeater->add_control(
			'backorder_choices',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'seperator' => 'before',
				'raw' => "<h4>Choices</h4>",
				'condition' => [
					'field_type' => 'allow_backorders',
				],
			]
		);
		$repeater->add_control(
			'do_not_allow',
			[
				'label' => __( 'Do not allow', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Do not allow', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => 'allow_backorders',
				],
			]
		);	
		$repeater->add_control(
			'notify',
			[
				'label' => __( 'Notify', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Allow, but notify customers', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => 'allow_backorders',
				],
			]
		);	
		$repeater->add_control(
			'allow',
			[
				'label' => __( 'Allow', 'woocommerce' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Allow', 'woocommerce' ),
				'dynamic' => [
					'active' => true,
				],		
				'condition' => [
					'field_type' => 'allow_backorders',
				],
			]
		);	
	
		$repeater->add_control(
			'recaptcha_version',
			[
				'label' => __( 'Version', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'v2' => __( 'Version 2', 'acf-frontend-form-element' ),
					'v3' => __( 'Version 3', 'acf-frontend-form-element' ),					
				],
				'default' => 'v2',
				'condition' => [
					'field_type' => 'recaptcha',
				],
			]
		);	
		$repeater->add_control(
			'recaptcha_site_key',
			[
				'label' => __( 'Site Key', 'acf-frontend-form-element' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],		
				'default' => get_option( 'acfef_google_recaptcha_site' ),
				'condition' => [
					'field_type' => 'recaptcha',
				],
			]
		);		
		$repeater->add_control(
			'recaptcha_secret_key',
			[
				'label' => __( 'Secret Key', 'acf-frontend-form-element' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],		
				'default' => get_option( 'acfef_google_recaptcha_secret' ),
				'condition' => [
					'field_type' => 'recaptcha',
				],
			]
		);	
		$repeater->add_control(
			'recaptcha_note',
			[
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<br>' . __( 'If you don\'t already have a site key and a secret, you may generate them here:', 'acf-frontend-form-element' ) . ' <a href="https://www.google.com/recaptcha/admin"> reCaptcha API Admin </a>',
				'condition' => [
					'field_type' => 'recaptcha',
				],
			]
		);		
		$repeater->add_control(
			'recaptcha_v2_note',
			[
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<br><br>' .
				__( 'For reCaptcha v2, you may use the following keys for testing purposes only:', 'acf-frontend-form-element' ) .
					'<br><br>' .
					__( 'Site Key', 'acf-frontend-form-element' ) .
					' <input type="text" readonly value="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" /> 
					<br>' .
					__( 'Secret Key', 'acf-frontend-form-element' ) . 
					' <input type="text" readonly value="6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe" />',
				'condition' => [
					'field_type' => 'recaptcha',
					'recaptcha_version' => 'v2',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'field_style_tab',
			[
				'label' => __( 'Style', 'acf-frontend-form-element' ),
				'condition' => [
					'field_type!' => $custom_layouts,
				]
			]
		);
		
		$repeater->add_control(
			'recaptcha_theme',
			[
				'label' => __( 'Version', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'light' => __( 'Light', 'acf-frontend-form-element' ),
					'dark' => __( 'Dark', 'acf-frontend-form-element' ),					
				],
				'default' => 'light',
				'condition' => [
					'field_type' => 'recaptcha',
					'recaptcha_version' => 'v2',
				],
			]
		);	
		$repeater->add_control(
			'recaptcha_size',
			[
				'label' => __( 'Version', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'normal' => __( 'Normal', 'acf-frontend-form-element' ),
					'compact' => __( 'Compact', 'acf-frontend-form-element' ),					
				],
				'default' => 'normal',
				'condition' => [
					'field_type' => 'recaptcha',
					'recaptcha_version' => 'v2',
				],
			]
		);	
		$repeater->add_control(
			'recaptcha_hide_logo',
			[
				'label' => __( 'Hide Logo', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off' => __( 'No','acf-frontend-form-element' ),
				'return_value' => 'true',	
				'condition' => [
					'field_type' => 'recaptcha',
					'recaptcha_version' => 'v3',
				],				
			]
		);

		$repeater->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} input' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} textarea' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} select' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} span.select2-selection__rendered' => 'color: {{VALUE}};',
				],
			]
		);
		$repeater->add_control(
			'field_placeholder_text_color',
			[
				'label' => __( 'Placeholder Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} textarea::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} select::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} input, {{WRAPPER}} {{CURRENT_ITEM}} textarea, {{WRAPPER}} {{CURRENT_ITEM}} select, {{WRAPPER}} .select2-selection__rendered, {{WRAPPER}} .input-subgroup label',
			]
		);

		$repeater->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} input' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} textarea' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input .select2-selection' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$repeater->add_responsive_control(
			'field_text_padding',
			[
				'label' => __( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input select *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input .select2-selection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		$repeater->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  input' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input .select2-selection' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} textarea' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input .select2-selection' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',		
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .acf-input .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
					'{{WRAPPER}} {{CURRENT_ITEM}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'field_width',
			[
				'label' => __( 'Width', 'acf-frontend-form-element' ) . ' (%)',
				'type' => Controls_Manager::NUMBER,
				'min' => 30,
				'max' => 100,
				'default' => 100,
				'required' => true,
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'max' => 100,
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'default' => 100,
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} {{CURRENT_ITEM}}' => 'float: left; clear: none',
					'body.rtl {{WRAPPER}} {{CURRENT_ITEM}}' => 'float: right; clear: none',
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{VALUE}}%',
				],
				'condition' => [
					'field_type!' => [ 'ACF_field_groups', 'ACF_fields', 'step' ],
				]
			]
		);		

		$repeater->add_responsive_control(
			'field_margin',
			[
				'label' => __( 'Margin', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%', 'px', 'em' ],
				'default' => [
					'unit' => '%',
					'top' => 'o',
					'bottom' => 'o',
					'left' => 'o',
					'right' => 'o',
					'isLinked' => 'false',
				],
				'isLinked' => 'false',
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		$repeater->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ '%', 'px', 'em' ],
				'default' => [
					'top' => 'o',
					'bottom' => 'o',
					'left' => 'o',
					'right' => 'o',
					'isLinked' => 'false',
					'unit' => '%',
				],
				'min' => 0,
				'isLinked' => 'false',
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();
	
		$widget->add_control(
			'fields_selection',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '<span style="text-transform: capitalize;">{{{ field_type.replace(/_/g, " ") }}}</span>',
				'default' => $widget->form_defaults[ 'fields' ],
				'separator' => 'after',
			]
        );
    }
    
    public function register_display_section( $widget ) {

		$widget->start_controls_section(
			'display_section',
			[
				'label' => __( 'Display Options', 'acf-frontend-form-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
			);
		$widget->add_control(
			'hide_field_labels',
			[
				'label' => __( 'Hide Field Labels', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Hide', 'acf-frontend-form-element' ),
				'label_off' => __( 'Show','acf-frontend-form-element' ),
				'return_value' => 'true',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .acf-label' => 'display: none',
				]
			]
		);
		$widget->add_control(
			'field_label_position',
			[
				'label' => __( 'Label Position', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Above', 'elementor-pro' ),
					'left' => __( 'Inline', 'elementor-pro' ),
				],
				'default' => 'top',
				'condition' => [
					'hide_field_labels!' => 'true',
				],
			]
		);
		$widget->add_control(
			'hide_mark_required',
			[
				'label' => __( 'Hide Required Mark', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Hide', 'elementor-pro' ),
				'label_off' => __( 'Show', 'elementor-pro' ),
				'return_value' => 'true',
				'condition' => [
					'hide_field_labels!' => 'true',
				],
				'selectors' => [
					'{{WRAPPER}} .acf-required' => 'display: none',
				],
			]
		);
		
		$widget->add_control(
			'field_instruction_position',
			[
				'label' => __( 'Instruction Position', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'label' => __( 'Above Field', 'elementor-pro' ),
					'field' => __( 'Below Field', 'elementor-pro' ),
				],
				'default' => 'label',
				'separator' => 'before',
			]
		);

		
		$widget->add_control(
			'field_seperator',
			[
				'label' => __( 'Field Seperator', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Hide', 'elementor-pro' ),
				'label_off' => __( 'Show', 'elementor-pro' ),
				'default' => 'true',
				'return_value' => 'true',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .acf-fields>.acf-field' => 'border-top: none',
					'{{WRAPPER}} .acf-field[data-width]+.acf-field[data-width]' => 'border-left: none',
				],
			]
		);			

		$widget->end_controls_section();
	}

    
	public function __construct() {
		add_action( 'acfef/form_structure_section', [ $this, 'register_form_structure_section' ] );
		add_action( 'acfef/display_section', [ $this, 'register_display_section' ] );		
		add_action( 'acfef/fields_controls', [ $this, 'fields_controls' ] );		
	}

}

new ContentTab();
