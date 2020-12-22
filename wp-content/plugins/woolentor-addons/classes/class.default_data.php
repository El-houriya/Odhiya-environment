<?php
/**
* WooLentor_Default_Data
*/
class WooLentor_Default_Data{

    /**
     * [$instance]
     * @var null
     */
    private static $instance   = null;

    /**
     * [$product_id]
     * @var null
     */
    private static $product_id = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){
        add_action( 'init', [ $this, 'init'] );
    }

    /**
     * [init] Initialize Function
     * @return [void]
     */
    public function init(){
        add_filter( 'body_class', [ $this, 'body_class' ] );
        add_filter( 'post_class', [ $this, 'post_class' ] );
    }

    /**
     * [body_class] Body Classes
     * @param  [type] $classes String
     * @return [void] 
     */
    public function body_class( $classes ){
        $post_type = get_post_type();
        if( $post_type == 'elementor_library' ){
            $classes[] = 'woocommerce';
            $classes[] = 'woocommerce-page';
            $classes[] = 'woolentor-woocommerce-builder';
            $classes[] = 'single-product';
        }
        return $classes;
    }

    /**
     * [post_class] Post Classes
     * @param  [type] $classes String
     * @return [void]
     */
    public function post_class( $classes ){
        $post_type = get_post_type();
        if( $post_type == 'elementor_library' ){
            $classes[] = 'product';
        }
        return $classes;
    }

    /**
     * [default] Show Default data in Elementor Editor Mode
     * @param  string $addons   Addon Name
     * @param  array  $settings Addon Settings
     * @return [html] 
     */
    public function default( $addons = '', $settings = array() ){

        global $post, $product;
        if( get_post_type() == 'product' ){
            self::$product_id = $product->get_id();
        }else{
            if( function_exists('woolentor_get_last_product_id') ){
                self::$product_id = woolentor_get_last_product_id();
                $product = wc_get_product( woolentor_get_last_product_id() );
            }
        }

        if( $product ){
            switch ( $addons ){

                case 'wl-product-add-to-cart':
                    ob_start();
                    echo '<div class="product">';
                    do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
                    echo '</div>';
                    return ob_get_clean();
                    break;

                case 'wl-single-product-price':
                    ob_start();
                    ?>
                    <p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>
                    <?php
                    return ob_get_clean();
                    break;

                case 'wl-single-product-short-description':
                    ob_start();
                    $short_description = get_the_excerpt( self::$product_id );
                    $short_description = apply_filters( 'woocommerce_short_description', $short_description );
                    if ( empty( $short_description ) ) { return; }
                    ?>
                        <div class="woocommerce-product-details__short-description"><?php echo wp_kses_post( $short_description ); ?></div>
                    <?php
                    return ob_get_clean();
                    break;

                case 'wl-single-product-description':
                    ob_start();
                    $description = get_post_field( 'post_content', self::$product_id );
                    if ( empty( $description ) ) { return; }
                    return $description .= ob_get_clean();
                    break;

                case 'wl-single-product-rating':
                    if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
                        return;
                    }
                    ob_start();
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    $average      = $product->get_average_rating();

                    if ( $rating_count > 0 ) : ?>
                        <div class="product">
                            <div class="woocommerce-product-rating">
                                <?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
                                <?php if ( comments_open() ) : ?>
                                    <?php //phpcs:disable ?>
                                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woolentor' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
                                    <?php // phpcs:enable ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php else:?>
                        <?php echo '<div class="wl-nodata">'.__('No Ratting Available','woolentor').'</div>';?>
                    <?php endif; 
                    break;

                case 'wl-single-product-image':
                    ob_start();
                    $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                    $thumbnail_id = $product->get_image_id();
                    $wrapper_classes = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
                        'woocommerce-product-gallery',
                        'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
                        'woocommerce-product-gallery--columns-' . absint( $columns ),
                        'images',
                    ) );

                    if ( function_exists( 'wc_get_gallery_image_html' ) ) {
                        ?>
                        <div class="product">
                            <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
                                <figure class="woocommerce-product-gallery__wrapper">
                                    <?php
                                    if ( $product->get_image_id() ) {
                                        $html = wc_get_gallery_image_html( $thumbnail_id, true );
                                    } else {
                                        $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                                        $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woolentor' ) );
                                        $html .= '</div>';
                                    }

                                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

                                    $attachment_ids = $product->get_gallery_image_ids();
                                    if ( $attachment_ids && $product->get_image_id() ) {
                                        foreach ( $attachment_ids as $attachment_id ) {
                                            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                                        }
                                    }

                                    ?>
                                </figure>
                            </div>
                        </div>
                        <?php
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-meta':
                    ob_start();
                    ?>
                        <div class="product">
                            <div class="product_meta">

                                <?php do_action( 'woocommerce_product_meta_start' ); ?>

                                <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

                                    <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woolentor' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woolentor' ); ?></span></span>

                                <?php endif; ?>

                                <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woolentor' ) . ' ', '</span>' ); ?>

                                <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woolentor' ) . ' ', '</span>' ); ?>

                                <?php do_action( 'woocommerce_product_meta_end' ); ?>

                            </div>
                        </div>
                    <?php
                    return ob_get_clean();
                    break;

                case 'wl-product-additional-information':
                    ob_start();
                    wc_get_template( 'single-product/tabs/additional-information.php' );
                    return ob_get_clean();
                    break;

                case 'wl-product-data-tabs':
                    setup_postdata( $product->get_id() );
                    ob_start();
                    if( get_post_type() == 'elementor_library' ){
                        add_filter( 'the_content', [ $this, 'product_content' ] );
                    }
                    wc_get_template( 'single-product/tabs/tabs.php' );
                    return ob_get_clean();
                    break;

                case 'wl-single-product-reviews':
                    ob_start();
                    if( comments_open() ){
                        comments_template();
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-stock':
                    ob_start();
                    $availability = $product->get_availability();
                    ?>
                        <div class="product"><p class="stock <?php echo esc_attr( $availability['class'] ); ?>"><?php echo wp_kses_post( $availability['availability'] ); ?></p></div>
                    <?php
                    return ob_get_clean();
                    break;

                case 'wl-single-product-upsell':
                    ob_start();

                    $product_per_page   = '-1';
                    $columns            = 4;
                    $orderby            = 'rand';
                    $order              = 'desc';
                    if ( ! empty( $settings['columns'] ) ) {
                        $columns = $settings['columns'];
                    }
                    if ( ! empty( $settings['orderby'] ) ) {
                        $orderby = $settings['orderby'];
                    }
                    if ( ! empty( $settings['order'] ) ) {
                        $order = $settings['order'];
                    }

                    woocommerce_upsell_display( $product_per_page, $columns, $orderby, $order );

                    return ob_get_clean();
                    break;

                case 'wl-product-related':
                    ob_start();
                    if ( ! $product ) { return; }
                    $args = [
                        'posts_per_page' => 4,
                        'columns' => 4,
                        'orderby' => $settings['orderby'],
                        'order' => $settings['order'],
                    ];
                    if ( ! empty( $settings['posts_per_page'] ) ) {
                        $args['posts_per_page'] = $settings['posts_per_page'];
                    }
                    if ( ! empty( $settings['columns'] ) ) {
                        $args['columns'] = $settings['columns'];
                    }

                    $args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), 
                        $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );

                    $args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

                    wc_get_template( 'single-product/related.php', $args );

                    return ob_get_clean();
                    break;

                default: 
                    return '';
                    break;

            }
        }


    }

    /**
     * [product_content]
     * @param  [string] $content
     * @return [string] 
     */
    public function product_content( $content ){
        $product_content = get_post( self::$product_id );
        $content = $product_content->post_content;
        return $content;
    }

}
WooLentor_Default_Data::instance();