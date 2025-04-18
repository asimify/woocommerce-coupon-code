<?php
class FPC_Cart_Checker {

    // Define eligible products (can be set dynamically via admin settings)
    private static $eligible_product_ids = [ 123, 456 ]; // Replace with real product IDs
    private static $free_product_id = 789; // Free product ID

    // Check if cart meets criteria for a free product
    public static function is_eligible_for_free_product( $cart_items ) {
        $has_eligible_product = false;

        foreach ( $cart_items as $item ) {
            if ( in_array( $item['product_id'], self::$eligible_product_ids ) ) {
                $has_eligible_product = true;
            }
        }

        return $has_eligible_product;
    }
}
