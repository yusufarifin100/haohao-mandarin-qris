<?php
if (!defined('ABSPATH')) {
    exit;
}

class HaoHao_QRIS_Webhook_Handler {

    public function __construct() {
        // Register REST API Route for modern Webhooks
        add_action('rest_api_init', array($this, 'register_webhook_routes'));

        // Legacy WooCommerce API Hook fallback
        add_action('woocommerce_api_haohao_qris_webhook', array($this, 'handle_legacy_webhook'));
    }

    /**
     * Register REST API endpoint: /wp-json/haohao-qris/v1/webhook
     */
    public function register_webhook_routes() {
        register_rest_route('haohao-qris/v1', '/webhook', array(
            'methods'             => 'POST',
            'callback'            => array($this, 'handle_rest_webhook'),
            'permission_callback' => '__return_true', // Validated via HMAC signature in callback
        ));
    }

    /**
     * Modern REST Webhook Handler
     */
    public function handle_rest_webhook(WP_REST_Request $request) {
        $body = $request->get_body();
        $headers = $request->get_headers();

        $signature = isset($headers['x_haohao_signature'][0]) ? $headers['x_haohao_signature'][0] : '';
        $data = json_decode($body, true);

        if (!$data || !isset($data['order_id']) || !isset($data['transaction_status'])) {
            return new WP_REST_Response(array('error' => 'Invalid Payload Structure'), 400);
        }

        // Validate HMAC-SHA256 Signature
        $gateway_settings = get_option('woocommerce_haohao_qris_settings', array());
        $server_key = isset($gateway_settings['server_key']) ? $gateway_settings['server_key'] : '';

        if (!empty($server_key) && !empty($signature)) {
            $expected_signature = hash_hmac('sha256', $body, $server_key);
            if (!hash_equals($expected_signature, $signature)) {
                return new WP_REST_Response(array('error' => 'Unauthorized: Invalid Signature'), 401);
            }
        }

        // Process order update
        $result = $this->process_order_status_update($data);
        if ($result['success']) {
            return new WP_REST_Response(array('status' => 'OK', 'message' => $result['message']), 200);
        } else {
            return new WP_REST_Response(array('error' => $result['message']), 400);
        }
    }

    /**
     * Legacy WooCommerce API Callback fallback
     */
    public function handle_legacy_webhook() {
        $raw_input = file_get_contents('php://input');
        $data = json_decode($raw_input, true);
        if ($data) {
            $this->process_order_status_update($data);
            wp_send_json(array('status' => 'OK'));
        }
        exit;
    }

    /**
     * Centralized Order Processing Logic
     */
    private function process_order_status_update($data) {
        $order_id           = absint($data['order_id']);
        $status             = sanitize_text_field($data['transaction_status']); // 'SUCCESS', 'EXPIRED', 'FAILED'
        $issuer             = isset($data['issuer']) ? sanitize_text_field($data['issuer']) : 'QRIS E-Wallet/m-Banking';
        $transaction_id     = isset($data['transaction_id']) ? sanitize_text_field($data['transaction_id']) : '';
        $amount             = isset($data['gross_amount']) ? (float)$data['gross_amount'] : 0;

        $order = wc_get_order($order_id);
        if (!$order) {
            return array('success' => false, 'message' => "Order #{$order_id} not found");
        }

        // Idempotency check: Don't process twice if already completed
        if ($order->has_status(array('processing', 'completed')) && $status === 'SUCCESS') {
            return array('success' => true, 'message' => "Order #{$order_id} already marked as paid.");
        }

        switch (strtoupper($status)) {
            case 'SUCCESS':
            case 'PAID':
            case 'SETTLEMENT':
                $order->payment_complete($transaction_id);
                $order->add_order_note(sprintf(
                    __('[HaoHao QRIS] Pembayaran Rp%s berhasil diverifikasi otomatis melalui %s. ID Transaksi: %s.', 'haohao-qris'),
                    number_format($amount, 0, ',', '.'),
                    $issuer,
                    $transaction_id
                ));
                $order->update_status('completed', __('Kursus HaoHao Mandarin aktif. Siswa dapat mengakses kelas.', 'haohao-qris'));
                return array('success' => true, 'message' => 'Order marked as completed.');

            case 'EXPIRED':
                $order->update_status('cancelled', __('Pembayaran QRIS kadaluarsa (melewati batas waktu 15 menit).', 'haohao-qris'));
                return array('success' => true, 'message' => 'Order marked as cancelled.');

            case 'FAILED':
            case 'DENIED':
                $order->update_status('failed', __('Pembayaran QRIS gagal diproses oleh sistem perbankan.', 'haohao-qris'));
                return array('success' => true, 'message' => 'Order marked as failed.');

            default:
                return array('success' => false, 'message' => "Unknown status: {$status}");
        }
    }
}
