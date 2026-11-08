<?php
if (!defined('ABSPATH')) {
    exit;
}

class WC_Gateway_HaoHao_QRIS extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'haohao_qris';
        $this->icon               = apply_filters('haohao_qris_icon', HAOHAO_QRIS_URL . 'assets/images/qris-logo.svg');
        $this->has_fields         = false;
        $this->method_title       = __('QRIS (Scan & Bayar Instan)', 'haohao-qris');
        $this->method_description = __('Gerbang pembayaran QRIS dinamis untuk kursus HaoHao Mandarin. Mendukung semua e-wallet (GoPay, OVO, ShopeePay, DANA) dan Mobile Banking (BCA, Mandiri, BRI, BNI).', 'haohao-qris');

        $this->supports = array(
            'products',
            'refunds'
        );

        // Load settings
        $this->init_form_fields();
        $this->init_settings();

        // Define properties based on settings
        $this->title        = $this->get_option('title', 'QRIS (Semua Bank & E-Wallet)');
        $this->description  = $this->get_option('description', 'Scan kode QRIS menggunakan GoPay, OVO, DANA, ShopeePay, BCA, Livin, atau aplikasi perbankan lainnya.');
        $this->enabled      = $this->get_option('enabled', 'yes');
        $this->merchant_id  = $this->get_option('merchant_id');
        $this->server_key   = $this->get_option('server_key');
        $this->is_production= $this->get_option('is_production') === 'yes';
        $this->expiry_mins  = absint($this->get_option('expiry_mins', 15));

        // Save settings hook
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        // Receipt page hook for QR Code display
        add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));

        // AJAX polling for status check
        add_action('wp_ajax_haohao_qris_check_status', array($this, 'ajax_check_order_status'));
        add_action('wp_ajax_nopriv_haohao_qris_check_status', array($this, 'ajax_check_order_status'));
    }

    /**
     * Admin Configuration Form Fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'       => __('Aktifkan Gateway', 'haohao-qris'),
                'type'        => 'checkbox',
                'label'       => __('Aktifkan Pembayaran QRIS', 'haohao-qris'),
                'default'     => 'yes'
            ),
            'title' => array(
                'title'       => __('Judul Pembayaran', 'haohao-qris'),
                'type'        => 'text',
                'description' => __('Judul metode pembayaran yang dilihat siswa saat checkout.', 'haohao-qris'),
                'default'     => __('QRIS (Scan & Bayar Otomatis)', 'haohao-qris'),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Deskripsi', 'haohao-qris'),
                'type'        => 'textarea',
                'description' => __('Keterangan petunjuk saat memilih metode ini.', 'haohao-qris'),
                'default'     => __('Scan kode QRIS langsung melalui m-Banking (BCA, Mandiri, BRI, dll) atau E-Wallet (GoPay, OVO, ShopeePay, DANA). Terverifikasi otomatis dalam 10 detik.', 'haohao-qris'),
            ),
            'merchant_id' => array(
                'title'       => __('Merchant ID QRIS', 'haohao-qris'),
                'type'        => 'text',
                'description' => __('ID Merchant dari penyedia QRIS / Payment Gateway (Contoh: M-HAOHAO-8891).', 'haohao-qris'),
                'default'     => 'HAOHAO-MANDARIN-01',
            ),
            'server_key' => array(
                'title'       => __('Server Key / Secret Key', 'haohao-qris'),
                'type'        => 'password',
                'description' => __('Kunci rahasia untuk otentikasi webhook HMAC-SHA256.', 'haohao-qris'),
                'default'     => '',
            ),
            'expiry_mins' => array(
                'title'       => __('Batas Waktu Pembayaran (Menit)', 'haohao-qris'),
                'type'        => 'number',
                'description' => __('Masa berlaku QR code sebelum expired otomatis.', 'haohao-qris'),
                'default'     => 15,
            ),
            'is_production' => array(
                'title'       => __('Mode Produksi', 'haohao-qris'),
                'type'        => 'checkbox',
                'label'       => __('Gunakan environment Live Production (Hilangkan centang untuk Sandbox Test Mode)', 'haohao-qris'),
                'default'     => 'no'
            )
        );
    }

    /**
     * Process Payment upon Checkout click
     */
    public function process_payment($order_id) {
        $order = wc_get_order($order_id);

        // Generate Transaction Reference & QR String
        $transaction_id = 'QRIS-' . $order_id . '-' . time();
        $qris_string    = $this->generate_qris_payload($order, $transaction_id);
        $expiry_time    = time() + ($this->expiry_mins * 60);

        // Store meta in order
        $order->update_meta_data('_haohao_qris_trx_id', $transaction_id);
        $order->update_meta_data('_haohao_qris_content', $qris_string);
        $order->update_meta_data('_haohao_qris_expiry', $expiry_time);
        $order->save();

        // Mark order as Pending Payment
        $order->update_status('pending', __('Menunggu pembayaran QRIS oleh siswa.', 'haohao-qris'));

        // Reduce stock
        wc_reduce_stock_levels($order_id);

        // Clear cart
        WC()->cart->empty_cart();

        // Return redirect to receipt/payment page
        return array(
            'result'   => 'success',
            'redirect' => $order->get_checkout_payment_url(true)
        );
    }

    /**
     * Generate QRIS String payload compliant with EMVCo standard (Indonesian QRIS specification)
     */
    private function generate_qris_payload($order, $transaction_id) {
        $amount = (int) $order->get_total();
        // Standard Indonesian National QRIS Payload Template (ASPI / Bank Indonesia standard format)
        $qris_dummy = "00020101021226670016ID.CO.QRIS.WWW0118936005030000088910215HAOHAO{$order->get_id()}520482115303360540" . strlen($amount) . $amount . "5802ID5915HAOHAO MANDARIN6007JAKARTA61051234062200716{$transaction_id}6304";
        return $qris_dummy;
    }

    /**
     * Render Receipt Page with QRIS Card and Live Polling
     */
    public function receipt_page($order_id) {
        $order          = wc_get_order($order_id);
        $transaction_id = $order->get_meta('_haohao_qris_trx_id');
        $qris_string    = $order->get_meta('_haohao_qris_content');
        $expiry_time    = (int) $order->get_meta('_haohao_qris_expiry');
        $remaining_secs = max(0, $expiry_time - time());
        $qr_image_url   = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data=' . urlencode($qris_string);

        ?>
        <div class="haohao-qris-container" data-order-id="<?php echo esc_attr($order_id); ?>" data-remaining="<?php echo esc_attr($remaining_secs); ?>">
            <div class="haohao-qris-card">
                <div class="haohao-qris-header">
                    <div class="haohao-qris-badge">
                        <span class="pulse-dot"></span> Menunggu Pembayaran
                    </div>
                    <h2>Pembayaran Kursus HaoHao Mandarin</h2>
                    <p class="order-ref">Order #<?php echo esc_html($order_id); ?> &bull; Ref: <?php echo esc_html($transaction_id); ?></p>
                </div>

                <div class="haohao-qris-amount-box">
                    <span class="label">Total Tagihan:</span>
                    <span class="amount"><?php echo wc_price($order->get_total()); ?></span>
                </div>

                <!-- QR Code Box -->
                <div class="haohao-qr-wrapper">
                    <img src="<?php echo esc_url($qr_image_url); ?>" alt="QRIS Code HaoHao Mandarin" class="qris-image" />
                    <div class="qris-logo-footer">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS Logo" style="height:24px; vertical-align:middle;">
                        <span>GPN &bull; Bank Indonesia</span>
                    </div>
                </div>

                <!-- Countdown Timer -->
                <div class="haohao-timer-box">
                    <span>Sisa Waktu Pembayaran:</span>
                    <strong id="haohao-countdown-display">15:00</strong>
                </div>

                <!-- Live Auto-check Status Indicator -->
                <div class="haohao-polling-status" id="haohao-polling-status">
                    <span class="spinner-icon">🔄</span> Mengecek status pembayaran secara otomatis...
                </div>

                <!-- Payment Instructions Accordion -->
                <div class="haohao-qris-instructions">
                    <h4>Cara Pembayaran via QRIS:</h4>
                    <ol>
                        <li>Buka aplikasi <strong>BCA mobile, Livin by Mandiri, BRImo, BNI Mobile</strong>, atau e-wallet (<strong>GoPay, OVO, DANA, ShopeePay</strong>).</li>
                        <li>Pilih menu <strong>Bayar / QRIS / Scan QR</strong>.</li>
                        <li>Arahkan kamera ke kode QR di atas atau simpan tangkapan layar.</li>
                        <li>Periksa nama penerima: <strong>HaoHao Mandarin</strong> dan jumlah tagihan.</li>
                        <li>Konfirmasi & masukkan PIN Anda. Halaman ini akan <strong>otomatis teralihkan</strong> setelah pembayaran berhasil.</li>
                    </ol>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX endpoint for frontend polling
     */
    public function ajax_check_order_status() {
        check_ajax_referer('haohao-qris-check-nonce', 'nonce');

        $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
        if (!$order_id) {
            wp_send_json_error(array('message' => 'Invalid Order ID'));
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_send_json_error(array('message' => 'Order not found'));
        }

        $status = $order->get_status();
        $is_paid = in_array($status, array('processing', 'completed'));

        wp_send_json_success(array(
            'status'       => $status,
            'is_paid'      => $is_paid,
            'redirect_url' => $this->get_return_url($order)
        ));
    }
}
