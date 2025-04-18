<?php
/**
 * Plugin Name: Free Product Coupon for WooCommerce
 * Description: Creates a coupon that gives a free product if certain conditions are met in the cart.
 * Version: 1.0.0
 * Author: Asim Asimify
 * Text Domain: free-product-coupon
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'FPC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Include necessary classes
require_once FPC_PLUGIN_PATH . 'includes/class-fpc-coupon-manager.php';
require_once FPC_PLUGIN_PATH . 'includes/class-fpc-cart-checker.php';
require_once FPC_PLUGIN_PATH . 'includes/class-fpc-admin-settings.php';

// Plugin activation hook: schedule cleanup cron
register_activation_hook( __FILE__, function() {
    if ( ! wp_next_scheduled( 'fpc_cleanup_cron' ) ) {
        wp_schedule_event( time(), 'twicedaily', 'fpc_cleanup_cron' );
    }
});

// Plugin deactivation hook: clear scheduled cleanup cron
register_deactivation_hook( __FILE__, function() {
    wp_clear_scheduled_hook( 'fpc_cleanup_cron' );
});

// Initialize the plugin
function fpc_init() {
    new FPC_Coupon_Manager();
    new FPC_Cart_Checker();
    new FPC_Admin_Settings();
}
add_action( 'plugins_loaded', 'fpc_init' );
