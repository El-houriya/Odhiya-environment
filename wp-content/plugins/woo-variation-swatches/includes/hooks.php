<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
	
	add_action( 'wp_ajax_nopriv_wvs_get_available_variations', 'wvs_get_available_product_variations' );
	
	add_action( 'wp_ajax_wvs_get_available_variations', 'wvs_get_available_product_variations' );
	
	add_filter( 'product_attributes_type_selector', 'wvs_product_attributes_types' );
	
	add_action( 'init', 'wvs_settings', 2 );
	
	add_action( 'admin_init', 'wvs_add_product_taxonomy_meta' );
	
	// From WC 3.6+
	if ( defined( 'WC_VERSION' ) && version_compare( '3.6', WC_VERSION, '<=' ) ) {
		add_action( 'woocommerce_product_option_terms', 'wvs_product_option_terms', 20, 3 );
	} else {
		add_action( 'woocommerce_product_option_terms', 'wvs_product_option_terms_old', 20, 2 );
	}
	
	// Dokan Support
	add_action( 'dokan_product_option_terms', 'dokan_support_wvs_product_option_terms', 20, 2 );
	
	add_filter( 'woocommerce_ajax_variation_threshold', 'wvs_ajax_variation_threshold', 8 );
	
	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'wvs_variation_attribute_options_html', 200, 2 );
	
	add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
		
		$defer_load_js = (bool) woo_variation_swatches()->get_option( 'defer_load_js' );
		
		if ( $defer_load_js ) {
			$handles = array( 'woo-variation-swatches-pro', 'wc-add-to-cart-variation', 'woo-variation-swatches' );
			
			if ( ! wp_is_mobile() && in_array( $handle, $handles ) && ( strpos( $tag, 'plugins' . DIRECTORY_SEPARATOR . 'woo-variation-swatches' ) !== false ) ) {
				return str_ireplace( ' src=', ' defer src=', $tag );
			}
		}
		
		return $tag;
		
	}, 10, 3 );
	
	if ( ! class_exists( 'Woo_Variation_Swatches_Pro' ) ) {
		add_filter( 'woocommerce_product_data_tabs', 'add_wvs_pro_preview_tab' );
		
		add_filter( 'woocommerce_product_data_panels', 'add_wvs_pro_preview_tab_panel' );
	}
	
	
	function wvs_clear_product_variation_transient( $variation_id ) {
		$product = wc_get_product( $variation_id );
		
		// Increments the transient version to invalidate cache.
		WC_Cache_Helper::get_transient_version( 'wvs_variation_options_html', true );
		WC_Cache_Helper::get_transient_version( 'wvs_attribute_taxonomy', true );
		
		/*if ( $product && $product->is_type( 'variable' ) ) {
			
			$product_id     = $product->get_parent_id();
			$attribute_keys = array_keys( $product->get_variation_attributes() );
			
			foreach ( $attribute_keys as $attribute_id ) {
				$archive_transient_name = 'wvs_attribute_html_archive_' . $product_id . "_" . $attribute_id;
				$product_transient_name = 'wvs_attribute_html_' . $product_id . "_" . $attribute_id;
				delete_transient( $archive_transient_name );
				delete_transient( $product_transient_name );
			}
		}*/
	}
	
	add_action( 'woocommerce_save_product_variation', 'wvs_clear_product_variation_transient' );
	
	add_action( 'woocommerce_update_product_variation', 'wvs_clear_product_variation_transient' );
	
	add_action( 'woocommerce_delete_product_variation', 'wvs_clear_product_variation_transient' );
	
	add_action( 'woocommerce_trash_product_variation', 'wvs_clear_product_variation_transient' );
	
	// WooCommerce -> Status -> Tools -> Clear transients
	add_action( 'woocommerce_delete_product_transients', function ( $product_id ) {
		
		$product = wc_get_product( $product_id );
		
		WC_Cache_Helper::get_transient_version( 'wvs_variation_options_html', true );
		WC_Cache_Helper::get_transient_version( 'wvs_attribute_taxonomy', true );
		
		
		/*if ( $product && $product->is_type( 'variable' ) ) {
			$attribute_keys = array_keys( $product->get_variation_attributes() );
			
			foreach ( $attribute_keys as $attribute_id ) {
				$archive_transient_name = 'wvs_attribute_html_archive_' . $product_id . "_" . wc_variation_attribute_name( $attribute_id );
				$product_transient_name = 'wvs_attribute_html_' . $product_id . "_" . wc_variation_attribute_name( $attribute_id );
				delete_transient( $archive_transient_name );
				delete_transient( $product_transient_name );
			}
		}*/
	} );
	
	// Clean transient
	add_action( 'woocommerce_attribute_updated', function ( $attribute_id, $attribute, $old_attribute_name ) {
		/*$transient     = sprintf( 'wvs_get_wc_attribute_taxonomy_%s', wc_attribute_taxonomy_name( $attribute[ 'attribute_name' ] ) );
		$old_transient = sprintf( 'wvs_get_wc_attribute_taxonomy_%s', wc_attribute_taxonomy_name( $old_attribute_name ) );
		delete_transient( $transient );
		delete_transient( $old_transient );*/
		
		WC_Cache_Helper::get_transient_version( 'wvs_attribute_taxonomy', true );
		
	}, 20, 3 );
	
	// Clean transient
	add_action( 'woocommerce_attribute_deleted', function ( $attribute_id, $attribute_name, $taxonomy ) {
		/*$transient = sprintf( 'wvs_get_wc_attribute_taxonomy_%s', $taxonomy );
		delete_transient( $transient );*/
		
		WC_Cache_Helper::get_transient_version( 'wvs_attribute_taxonomy', true );
	}, 20, 3 );
	
	// Clean transient
	add_action( 'woocommerce_attribute_added', function ( $attribute_id, $attribute ) {
		/*$transient = sprintf( 'wvs_get_wc_attribute_taxonomy_%s', wc_attribute_taxonomy_name( $attribute[ 'attribute_name' ] ) );
		delete_transient( $transient );*/
		
		WC_Cache_Helper::get_transient_version( 'wvs_attribute_taxonomy', true );
	}, 20, 2 );
	
	// Load Template
	// add_filter( 'woocommerce_locate_template', 'wvs_locate_template', 10, 3 );
	
	add_filter( 'disable_wvs_admin_enqueue_scripts', function ( $default ) {
		return is_customize_preview() ? is_customize_preview() : $default;
	} );
	
	
	// Gallery Install Notice
	add_action( 'woocommerce_product_after_variable_attributes', function ( $loop, $variation_data, $variation ) {
		if ( ! woo_variation_swatches()->is_gallery_active() && current_user_can( 'install_plugins' ) && apply_filters( 'wvs_install_woo_variation_gallery_notice', true ) ) {
			?>
            <div class="form-row form-row-full woo-variation-gallery-message"
                 data-nonce="<?php echo wp_create_nonce( 'install-woo-variation-gallery' ) ?>"
                 data-installing="<?php esc_attr_e( 'Installing Plugin...', 'woo-variation-swatches' ); ?>"
                 data-activated="<?php esc_attr_e( 'Plugin Installed. Please refresh this page.', 'woo-variation-swatches' ); ?>">
				<?php printf( '%s <a class="install-woo-variation-gallery-action" target="_blank" href="#">%s</a> plugin', esc_html__( 'Want to add more image? Install', 'woo-variation-swatches' ), esc_html__( 'Additional Variation Images Gallery for WooCommerce', 'woo-variation-swatches' ) ) ?>
            </div>
			<?php
		}
	}, 10, 3 );
	
	add_action( 'wp_ajax_install_woo_variation_gallery', function () {
		
		if ( is_ajax() && current_user_can( 'install_plugins' ) && wp_verify_nonce( $_POST[ 'nonce' ], 'install-woo-variation-gallery' ) ) {
			
			$plugin_slug = 'woo-variation-gallery/woo-variation-gallery.php';
			$plugin_zip  = 'https://downloads.wordpress.org/plugin/woo-variation-gallery.zip';
			
			if ( wvs_is_plugin_installed( $plugin_slug ) ) {
				$installed = true;
				wvs_upgrade_plugin( $plugin_slug );
			} else {
				$installed = wvs_install_plugin( $plugin_zip );
			}
			
			if ( ! is_wp_error( $installed ) && $installed ) {
				activate_plugin( $plugin_slug );
			}
		}
		
		die;
	} );
	
	function wvs_is_plugin_installed( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();
		
		if ( ! empty( $all_plugins[ $slug ] ) ) {
			return true;
		} else {
			return false;
		}
	}
	
	function wvs_install_plugin( $plugin_zip ) {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		wp_cache_flush();
		
		$upgrader  = new Plugin_Upgrader();
		$installed = $upgrader->install( $plugin_zip );
		
		return $installed;
	}
	
	function wvs_upgrade_plugin( $plugin_slug ) {
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		wp_cache_flush();
		
		$upgrader = new Plugin_Upgrader();
		$upgraded = $upgrader->upgrade( $plugin_slug );
		
		return $upgraded;
	}
	