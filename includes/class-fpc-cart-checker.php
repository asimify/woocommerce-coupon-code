<?php
class FPC_Cart_Checker {

    public function __construct() {
        add_action( 'woocommerce_before_cart', [ $this, 'check_cart_conditions' ] );
        add_filter( 'woocommerce_coupon_is_valid', [ $this, 'validate_coupon_conditions' ], 10, 2 );
        add_action( 'woocommerce_before_checkout_form', [ $this, 'show_coupon_notice_if_eligible' ] );
    }

    // Fetch eligible product IDs from settings
    private static function get_eligible_product_ids() {
        $ids = get_option( 'fpc_eligible_product_ids', '' );
        return array_map( 'intval', array_filter( array_map( 'trim', explode( ',', $ids ) ) ) );
    }

    // Fetch free product ID from settings
    private static function get_free_product_id() {
        return intval( get_option( 'fpc_free_product_id', 0 ) );
    }

    // Check if cart meets criteria for a free product
    public static function is_eligible_for_free_product( $cart_items ) {
        $eligible_ids = self::get_eligible_product_ids();
        foreach ( $cart_items as $item ) {
            if ( in_array( $item['product_id'], $eligible_ids, true ) ) {
                return true;
            }
        }
        return false;
    }

    public function validate_coupon_conditions( $valid, $coupon ) {
        $allowed_coupons = [ 'FREEFILTER', 'FREEPRODUCT' ]; // Your allowed coupon codes
        if ( in_array( strtoupper( $coupon->get_code() ), $allowed_coupons, true ) ) {

            $cart = WC()->cart->get_cart();
            $has_required_product = false;
            $required_product_ids = self::get_eligible_product_ids();

            foreach ( $cart as $cart_item ) {
                $product_id = $cart_item['product_id'];
                if ( in_array( $product_id, $required_product_ids, true ) ) {
                    $has_required_product = true;
                    break;
                }
            }

            if ( ! $has_required_product ) {
                throw new \Exception( __( 'This coupon can only be used if you have the eligible product in your cart.', 'free-product-coupon' ) );
            }
        }

        return $valid;
    }

    public function show_coupon_notice_if_eligible() {
        if ( ! WC()->cart ) {
            return;
        }

        $cart = WC()->cart->get_cart();
        $has_required_product = false;
        $required_product_ids = self::get_eligible_product_ids();

        foreach ( $cart as $cart_item ) {
            $product_id = $cart_item['product_id'];
            if ( in_array( $product_id, $required_product_ids, true ) ) {
                $has_required_product = true;
                break;
            }
        }

        if ( $has_required_product && ! WC()->cart->has_discount( 'FREEFILTER' ) ) {
            wc_print_notice( __( 'You are eligible for a free product! Use coupon code <strong>FREEFILTER</strong> at checkout.', 'free-product-coupon' ), 'notice' );
        }
    }
}
?>
