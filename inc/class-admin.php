<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Cryptocurrency_Price_Admin {

    /**
     * Constructor. Adds actions for WordPress admin menu and script enqueuing.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_select2'));
    }

    /**
     * Adds plugin menu page to WordPress admin.
     */
    public function add_plugin_menu() {
        add_menu_page(
            __('Cryptocurrency Shortcode Settings', 'cryptocurrency-price-shortcode'),
            __('Cryptocurrency Shortcode', 'cryptocurrency-price-shortcode'),
            'manage_options',
            'cryptocurrency-settings',
            array($this, 'plugin_settings_page')
        );
    }

    /**
     * Enqueues Select2 styles and scripts.
     */
    public function enqueue_select2($hook) {
        if ($hook === 'toplevel_page_cryptocurrency-settings') {
            wp_enqueue_style('crypcode-select2-css', CRYPCODE_ASS_URL . 'css/select2.min.css');
            wp_enqueue_style('crypcode-select2-bootstrap-5-css', CRYPCODE_ASS_URL . 'css/select2-bootstrap-5-theme.min.css');
            wp_enqueue_style('crypcode-admin-css', CRYPCODE_ASS_URL . 'css/admin.css');
            wp_enqueue_script('crypcode-select2-js', CRYPCODE_ASS_URL . 'js/select2.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('crypcode-admin-js', CRYPCODE_ASS_URL . 'js/admin.js', array('jquery'), '2.0.0', true);
        }
    }

    /**
     * Displays the plugin settings page.
     */
    public function plugin_settings_page() {
        $currencies = json_decode(@file_get_contents(CRYPCODE_ASS_DIR . "json/quotes.json"), true);
        $source_coins = json_decode(@file_get_contents(CRYPCODE_ASS_DIR . "json/srcCoins.json"), true);
        ?>
        <div class="wrap">
            <h2><?php _e('Cryptocurrency Settings', 'cryptocurrency-price-shortcode'); ?></h2>
            <p>
                <?php _e('Select Source Coin:', 'cryptocurrency-price-shortcode'); ?>
                <select name="coin" id="coin" class="select2" style="width: 100%;height: 46px">
                    <?php foreach ($source_coins as $coin) : ?>
                        <option value="<?php echo esc_attr($coin['symbol']); ?>"><?php echo esc_html($coin['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <?php _e('Select Destination Currency:', 'cryptocurrency-price-shortcode'); ?>
                <select name="currency" id="currency" class="select2" style="width: 100%;height: 46px">
                    <?php foreach ($currencies as $currency) : ?>
                        <option value="<?php echo esc_attr($currency['symbol']); ?>"><?php echo esc_html($currency['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><?php _e('Shortcode:', 'cryptocurrency-price-shortcode'); ?> <input type="text" id="shortcode-example" disabled></p>
            <button id="copyShortcodeBtn"><?php _e('Copy Shortcode', 'cryptocurrency-price-shortcode'); ?></button>
        </div>
        <?php
    }
}

$cryptocurrency_price_admin_instance = new Cryptocurrency_Price_Admin();
