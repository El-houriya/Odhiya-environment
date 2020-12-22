<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
* Third party
*/
class WooLentorProductQuery{

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){

        if( class_exists('WooCommerce') ){
            add_action( 'woocommerce_product_query', [ $this, 'parse_query' ] );
        }

    }


    /**
     * [parse_query]
     * @param  [object] $wp_query WooCommerce Default Widget
     * @return [void]
     */
    public function parse_query( $wp_query ){

        if ( isset( $_GET['wlfilter'] ) ) {

            $queries =[];
            $new_queries = [];
            parse_str( $_SERVER['QUERY_STRING' ], $queries );
            foreach ( $queries as $key => $querie ) {
                $new_queries[] = $key;
            }

            if( isset( $_GET['wlorder_by'] ) ){
                if( in_array( $_GET['wlorder_by'], [ '_price', 'total_sales', '_wc_average_rating' ] ) ) {

                    $wp_query->set( 'meta_key', $_GET['wlorder_by'] );
                    $wp_query->set( 'orderby', 'meta_value_num' );

                }else if( $_GET['wlorder_by'] === 'featured' ){
                    $tax_query[] = [
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => explode( ',', $_GET['wlorder_by'] ),
                        'operator' => ( $_GET['wlorder_by'] === 'exclude-from-catalog' ? 'NOT IN' : 'IN' ),
                    ];
                    $wp_query->set( 'tax_query', $tax_query );
                }else{
                    $wp_query->set( 'orderby', $_GET['wlorder_by'] );
                }
            }

            if( isset( $_GET['wlsort'] ) ){
                $wp_query->set( 'order', $_GET['wlsort'] );
            }

            if( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ){
                $meta_query[] = array(
                    [
                        'key' => '_price',
                        'value' => array( $_GET['min_price'], $_GET['max_price'] ),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    ],
                );
                $wp_query->set( 'meta_query', $meta_query );
            }

            if( isset( $new_queries[1] ) && !in_array( $new_queries[1], [ 'wlsort', 'wlorder_by' ] ) ){
                $attr_pre_str = substr( $new_queries[1], 0, 6 );
                if( 'filter' === $attr_pre_str ){
                    $taxonomy = str_replace('filter', 'pa', $new_queries[1] );
                    if( isset( $_GET[$new_queries[1] ] ) ){
                        $tax_query[] = array(
                            'taxonomy' => $taxonomy,
                            'field' => 'name',
                            'terms' => explode( ',', $_GET[$new_queries[1]] ),
                        );
                    }
                    $wp_query->set( 'tax_query', $tax_query );
                }
            }

            
        }

    }

    
}

WooLentorProductQuery::instance();