<?php
class FPC_Admin_Settings {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_admin_page() {
        add_menu_page(
            'Free Product Settings',
            'Free Product Settings',
            'manage_options',
            'fpc-settings',
            [ $this, 'settings_page' ],
            'dashicons-tickets',
            90
        );
    }

    public function register_settings() {
        register_setting( 'fpc_settings_group', 'fpc_eligible_products' );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h2>Free Product Coupon Settings</h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'fpc_settings_group' ); ?>
                <label for="fpc_eligible_products">Eligible Product IDs (comma separated):</label>
                <input type="text" name="fpc_eligible_products" value="<?php echo esc_attr( get_option( 'fpc_eligible_products', '' ) ); ?>">
                <br><br>
                <input type="submit" class="button-primary" value="Save Settings">
            </form>
        </div>
        <?php
    }
}
