<?php
class FPC_Coupon_Manager {

    public function __construct() {
        add_action( 'woocommerce_before_cart', [ $this, 'apply_free_product_coupon' ] );
        add_action( 'fpc_cleanup_cron', [ $this, 'cleanup_expired_coupons' ] );
    }

    // Create a coupon for free product if conditions are met
    public function apply_free_product_coupon() {
        if ( ! is_admin() && WC()->cart ) {
            $cart_items = WC()->cart->get_cart();
            
            if ( FPC_Cart_Checker::is_eligible_for_free_product( $cart_items ) ) {
                $coupon_code = 'FREE_PRODUCT_' . get_current_user_id();

                if ( ! $this->coupon_exists( $coupon_code ) ) {
                    $this->create_coupon( $coupon_code );
                }

                WC()->cart->apply_coupon( $coupon_code );
            }
        }
    }

    // Create a WooCommerce coupon for a free product
    private function create_coupon( $coupon_code ) {
        $coupon = new WC_Coupon();
        $coupon->set_code( $coupon_code );
        $coupon->set_discount_type( 'fixed_cart' );
        $coupon->set_amount( 100 ); // 100% discount
        $coupon->set_individual_use( true );
        $coupon->set_email_restrictions( [ wp_get_current_user()->user_email ] );
        $coupon->save();
    }

    // Check if coupon already exists
    private function coupon_exists( $coupon_code ) {
        return wc_get_coupon_id_by_code( $coupon_code ) ? true : false;
    }

    // Cleanup expired coupons (runs via cron)
    public function cleanup_expired_coupons() {
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'shop_coupon' AND post_date < NOW() - INTERVAL 7 DAY" );
    }
}
