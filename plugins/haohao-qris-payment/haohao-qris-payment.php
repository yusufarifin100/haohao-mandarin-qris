<?php
/**
 * Plugin Name: HaoHao QRIS Payment Gateway for WooCommerce
 * Plugin URI: https://haohaomandarin.com/qris-gateway
 * Description: Gerbang pembayaran QRIS dinamis & otomatis untuk WordPress & WooCommerce. Dilengkapi auto-check status real-time, webhook callback verifikasi tanda tangan HMAC-SHA256, dan dukungan kursus online HaoHao Mandarin.
 * Version: 1.2.0
 * Author: HaoHao Mandarin Team
 * Author URI: https://haohaomandarin.com
 * Text Domain: haohao-qris
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.5
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('HAOHAO_QRIS_VERSION', '1.2.0');
define('HAOHAO_QRIS_PATH', plugin_dir_path(__FILE__));
define('HAOHAO_QRIS_URL', plugin_dir_url(__FILE__));

/**
 * Check if WooCommerce is active
 */
function haohao_qris_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p><strong>HaoHao QRIS Gateway</strong> membutuhkan plugin WooCommerce untuk dapat beroperasi.</p></div>';
        });
        return false;
    }
    return true;
}

/**
 * Register payment gateway to WooCommerce
 */
add_filter('woocommerce_payment_gateways', 'haohao_qris_add_gateway_class');
function haohao_qris_add_gateway_class($gateways) {
    $gateways[] = 'WC_Gateway_HaoHao_QRIS';
    return $gateways;
}

/**
 * Initialize Gateway Class & Webhook Handler
 */
add_action('plugins_loaded', 'haohao_qris_init_gateway_class');
function haohao_qris_init_gateway_class() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    require_once HAOHAO_QRIS_PATH . 'includes/class-wc-gateway-qris.php';
    require_once HAOHAO_QRIS_PATH . 'includes/class-qris-webhook-handler.php';

    // Initialize Webhook listener
    new HaoHao_QRIS_Webhook_Handler();
}

/**
 * Enqueue frontend scripts & styles for checkout QRIS modal
 */
add_action('wp_enqueue_scripts', 'haohao_qris_enqueue_assets');
function haohao_qris_enqueue_assets() {
    if (is_checkout() || is_account_page() || isset($_GET['pay_for_order'])) {
        wp_enqueue_style('haohao-qris-style', HAOHAO_QRIS_URL . 'assets/css/qris-checkout.css', array(), HAOHAO_QRIS_VERSION);
        wp_enqueue_script('haohao-qris-script', HAOHAO_QRIS_URL . 'assets/js/qris-checkout.js', array('jquery'), HAOHAO_QRIS_VERSION, true);

        wp_localize_script('haohao-qris-script', 'haohao_qris_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('haohao-qris-check-nonce'),
        ));
    }
}

/**
 * Add Settings Link on Plugins Page
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'haohao_qris_action_links');
function haohao_qris_action_links($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=haohao_qris') . '">Pengaturan</a>';
    array_unshift($links, $settings_link);
    return $links;
}
