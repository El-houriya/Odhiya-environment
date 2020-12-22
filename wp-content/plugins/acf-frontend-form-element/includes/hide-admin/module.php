<?php
namespace ACFFrontend\Module;

use Elementor\Core\Base\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class HA_Module extends Module {
	
	public function get_name() {
		return 'hide_admin';
	}

	public function get_widgets() {
		return [
			'Hide Admin'
		];
	}
	
		/**
	* Redirect non-admin users to home page
	*
	* This function is attached to the ‘admin_init’ action hook.
	*/
	public function redirect_non_admin_users() {
		global $current_user; 
		if ( is_admin() && ! current_user_can( 'manage_options' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			if( get_user_meta( $current_user->ID, 'hide_admin_area', true )  ){
				wp_redirect( home_url() );
				exit;
			}
		}
	}
	
	public function hide_admin_bar() {
		global $current_user; 
		if( get_user_meta( $current_user->ID, 'hide_admin_area', true )  ){
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}
	
	function hide_admin_area_option( $user ) {
		global $current_user; 
			$checked = ( isset ( $user->hide_admin_area ) && $user->hide_admin_area ) ? ' checked="checked"' : '';

			echo '<h3>' . __( 'Hide WordPress Admin Area', 'acf-frontend-form-element' ) . '</h3>
				<table class="form-table">
					<tr>
						<th><label for="hide_admin_area">' . __( 'Hide Admin Area', 'acf-frontend-form-element' ) . '</label></th>
						<td><input name="hide_admin_area" type="checkbox" id="hide_admin_area" value="1"' . $checked . '></td>
					</tr>
				</table>';		
	}
	

	function hide_admin_area_update_action($user_id) {
		$hide_admin = isset( $_POST[ 'hide_admin_area' ] );
	  	update_user_meta( $user_id, 'hide_admin_area', $hide_admin );
	}
	
	public function __construct() {
		add_action( 'admin_init', [ $this, 'redirect_non_admin_users' ] );
		add_action( 'init', [ $this, 'hide_admin_bar' ] );
		
		add_action('show_user_profile', [ $this, 'hide_admin_area_option' ] );
		add_action('edit_user_profile', [ $this, 'hide_admin_area_option' ] );
		
		add_action('personal_options_update', [ $this, 'hide_admin_area_update_action' ] );
		add_action('edit_user_profile_update', [ $this, 'hide_admin_area_update_action' ] );
	}
	
}



