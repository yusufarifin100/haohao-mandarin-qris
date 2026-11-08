/**
 * HaoHao QRIS Gateway - Frontend Polling & Countdown Script
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        var $container = $('.haohao-qris-container');
        if (!$container.length) return;

        var orderId = $container.data('order-id');
        var remainingSeconds = parseInt($container.data('remaining'), 10) || 900; // default 15 mins
        var $timerDisplay = $('#haohao-countdown-display');
        var $pollingStatus = $('#haohao-polling-status');
        var pollInterval = null;
        var timerInterval = null;

        // Format Seconds to MM:SS
        function formatTime(secs) {
            var m = Math.floor(secs / 60);
            var s = secs % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }

        // Start Countdown Timer
        $timerDisplay.text(formatTime(remainingSeconds));
        timerInterval = setInterval(function () {
            remainingSeconds--;
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                clearInterval(pollInterval);
                $timerDisplay.text('00:00 (KADALUARSA)');
                $pollingStatus.html('<span style="color:#dc2626;">⚠️ Waktu pembayaran telah habis. Silakan buat pesanan baru.</span>');
                return;
            }
            $timerDisplay.text(formatTime(remainingSeconds));
        }, 1000);

        // Polling Order Status via AJAX
        function checkPaymentStatus() {
            if (typeof haohao_qris_params === 'undefined') return;

            $.ajax({
                url: haohao_qris_params.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'haohao_qris_check_status',
                    order_id: orderId,
                    nonce: haohao_qris_params.nonce
                },
                success: function (res) {
                    if (res.success && res.data.is_paid) {
                        clearInterval(timerInterval);
                        clearInterval(pollInterval);
                        $pollingStatus.html('<strong style="color:#16a34a;">✅ Pembayaran Berhasil Dikonfirmasi! Mengalihkan ke kelas...</strong>');
                        setTimeout(function () {
                            window.location.href = res.data.redirect_url;
                        }, 1200);
                    }
                },
                error: function () {
                    // Silently ignore network hiccup, retry next tick
                }
            });
        }

        // Poll every 3 seconds
        pollInterval = setInterval(checkPaymentStatus, 3000);
    });
})(jQuery);
