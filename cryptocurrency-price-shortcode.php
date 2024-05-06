<?php
/**
 * Plugin Name: Cryptocurrency Price Shortcode
 * Description: Generates shortcodes to display live cryptocurrency prices.
 * Version: 1.0.0
 * Author: Hamed Fuladi
 * Author URI: https://github.com/hamedfuladi/
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: cryptocurrency-price-shortcode
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('CRYPCODE_DIR', plugin_dir_path(__FILE__));
define('CRYPCODE_INC_DIR', trailingslashit(CRYPCODE_DIR . 'inc'));
define('CRYPCODE_ASS_DIR', trailingslashit(CRYPCODE_DIR . 'assets'));
define('CRYPCODE_URL', plugin_dir_url(__FILE__));
define('CRYPCODE_ASS_URL', trailingslashit(CRYPCODE_URL . 'assets'));

class crypcode_Shortcode_Main
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    /**
     * Initialize the plugin.
     */
    public function init()
    {
        // Load admin functionality if in admin area
        if (is_admin()) {
            $this->load_admin();
        }

        // Load shortcode functionality
        $this->load_shortcodes();
    }

    /**
     * Load admin functionality.
     */
    private function load_admin()
    {
        $admin_file = CRYPCODE_INC_DIR . 'class-admin.php';
        if (file_exists($admin_file)) {
            require_once $admin_file;
        }
    }

    /**
     * Load shortcode functionality.
     */
    private function load_shortcodes()
    {
        $shortcode_file = CRYPCODE_INC_DIR . 'class-shortcode.php';
        if (file_exists($shortcode_file)) {
            require_once $shortcode_file;
        }
    }

}

$crypcode_shortcode_main = new crypcode_Shortcode_Main();
