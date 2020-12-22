<?php
namespace ACFFrontend;

use Elementor\Plugin as EL;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Plugin {
		
		/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var ACF_Elementor_Form The single instance of the class.
	 */
	private static $_instance = null;
	
	private $modules = [];


	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return ACF_Elementor_Form An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}
	
	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}
	
	public static function get_current_post_id() {
		if ( isset( EL::$instance->documents ) ) {
			$current_page = EL::$instance->documents->get_current();
			if( isset( $current_page ) ){
				return EL::$instance->documents->get_current()->get_main_id();
			}
		}
		return get_the_ID();
	}
	
	public function add_module( $id, $instance ) {
		$this->modules[ $id ] = $instance;
	}
	
	public function get_modules( $id = null ) {
		if ( $id ) {
			if ( ! isset( $this->modules[ $id ] ) ) {
				return null;
			}

			return $this->modules[ $id ];
		}

		return $this->modules;
	}

	public function __construct() {

		require_once ( __DIR__ . '/includes/elementor/helpers/data_fetch.php' );
		require_once ( __DIR__ . '/includes/elementor/helpers/forms.php' );
		require_once ( __DIR__ . '/includes/elementor/helpers/permissions.php' );	
		require_once ( __DIR__ . '/includes/elementor/helpers/modal.php' );	

		require_once ( __DIR__ . '/includes/elementor/module.php' );
		require_once ( __DIR__ . '/includes/acf-field-settings/module.php' );
		require_once ( __DIR__ . '/includes/acfef-settings/module.php' );
		
		$this->add_module( 'acfef_widget', Module\ACFEF_Module::instance() );	
		$this->add_module( 'acf_settings', Module\ACFS_Module::instance() );
		$this->add_module( 'acfef_settings', Module\ACFEFS_Module::instance() );
		
		do_action( 'acfef/widget_loaded' );
	}	
	
}

Plugin::instance();