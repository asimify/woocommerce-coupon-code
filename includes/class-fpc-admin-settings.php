<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FPC_Admin_Settings {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_admin_page() {
        add_menu_page(
            __( 'Free Product Settings', 'free-product-coupon' ),
            __( 'Free Product Settings', 'free-product-coupon' ),
            'manage_options',
            'fpc-settings',
            [ $this, 'settings_page' ],
            'dashicons-tickets',
            90
        );
    }

    public function register_settings() {
        register_setting( 'fpc_settings_group', 'fpc_eligible_product_ids', [
            'sanitize_callback' => function( $input ) {
                return sanitize_text_field( $input );
            }
        ] );

        register_setting( 'fpc_settings_group', 'fpc_free_product_id', [
            'sanitize_callback' => function( $input ) {
                return intval( $input );
            }
        ] );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Free Product Coupon Settings', 'free-product-coupon' ); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'fpc_settings_group' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Eligible Product IDs', 'free-product-coupon' ); ?></th>
                        <td>
                            <input type="text" name="fpc_eligible_product_ids" value="<?php echo esc_attr( get_option( 'fpc_eligible_product_ids', '' ) ); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e( 'Enter eligible product IDs separated by commas (e.g., 123,456).', 'free-product-coupon' ); ?></p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Free Product ID', 'free-product-coupon' ); ?></th>
                        <td>
                            <input type="text" name="fpc_free_product_id" value="<?php echo esc_attr( get_option( 'fpc_free_product_id', '' ) ); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e( 'Enter the product ID for the free product.', 'free-product-coupon' ); ?></p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

// Initialize the settings
new FPC_Admin_Settings();
?>
