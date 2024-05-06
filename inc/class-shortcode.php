<?php
/**
 * Cryptocurrency_Price_Shortcode class.
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class crypcode_Shortcode {

    /**
     * Constructor.
     */
    public function __construct() {
        add_shortcode('crypcode', array($this, 'crypcode_shortcode_handler'));
    }

    /**
     * Custom number formatting function.
     *
     * @param float $number The number to format.
     * @return string Formatted number.
     */
    public function customNumberFormat($number) {
        if (abs($number) == 0) {
            return '0';
        } elseif (abs($number) >= 1) {
            return number_format($number, 2);
        } elseif (abs($number) >= 0.01) {
            return number_format($number, 2);
        } elseif (abs($number) >= 0.0001) {
            return number_format($number, 4);
        } else {
            return rtrim(sprintf('%.10f', $number), '0');
        }
    }

    /**
     * Shortcode handler for [crypcode] shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function crypcode_shortcode_handler($atts) {
        // Default attributes
        $atts = shortcode_atts(
            array(
                'convert' => 'USD',
                'coin' => 'BTC',
            ),
            $atts,
            'crypcode'
        );

        // Check cache
        $cache_key = 'crypcode_' . md5(serialize($atts));
        $cached_output = get_transient($cache_key);

        if ($cached_output !== false) {
            return $cached_output;
        }

        // Make API call
        $api_url = 'https://script.google.com/macros/s/AKfycbwumChWVgu1WUiLPEVuok9BZmHokEXrzqjvO8Nu-jvWBUDB48fcJfYwQiY5FHwIslMsbg/exec';
        $response = wp_safe_remote_get($api_url);

        // Check for API request errors
        if (is_wp_error($response)) {
            return __('Error: Unable to fetch data.', 'cryptocurrency-price-shortcode');
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Check for API response errors
        if (isset($data['status']['error_code']) && $data['status']['error_code'] === "0") {
            foreach ($data['data'] as $item) {
                if ($item['symbol'] === $atts['coin']) {
                    foreach ($item['quotes'] as $quote) {
                        if ($quote['symbol'] === $atts['convert']) {
                            $output = $this->customNumberFormat($quote['price']);

                            // Cache the output for 1 minute
                            set_transient($cache_key, $output, 60);
                            return $output;
                        }
                    }
                }
            }
            return __('Error: Currency pair not found.', 'cryptocurrency-price-shortcode');
        } else {
            return sprintf(__('Error: %s', 'cryptocurrency-price-shortcode'), $data['status']['error_message']);
        }
    }
}

new crypcode_Shortcode();
