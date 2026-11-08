# HaoHao QRIS Payment Gateway for WooCommerce

Gerbang pembayaran QRIS dinamis untuk platform kursus WordPress & WooCommerce. Dikembangkan secara khusus untuk arsitektur edukasi online HaoHao Mandarin.

## ✨ Fitur Utama
1. **Dukungan Seluruh Bank & E-Wallet:** Mendukung pembayaran via BCA, Mandiri, BRI, BNI, GoPay, OVO, ShopeePay, DANA, LinkAja.
2. **Dynamic QRIS Generation:** Menghasilkan payload string QRIS berstandar EMVCo / ASPI Bank Indonesia yang unik untuk setiap pesanan.
3. **Webhook Callback REST API:** Endpoint `/wp-json/haohao-qris/v1/webhook` dengan verifikasi tanda tangan digital HMAC-SHA256 untuk memproses update otomatis status pesanan ke *Completed*.
4. **Real-time Client Polling:** Polling AJAX di browser siswa yang mendeteksi perubahan status lunas tanpa perlu refresh manual.
5. **Countdown Timer:** Penghitung mundur 15 menit otomatis membatalkan pesanan jika kedaluwarsa.
6. **Idempotency & Anti-Duplikasi:** Mencegah pemrosesan ganda (*duplicate execution*) saat webhook dipanggil berulang kali.

## 🛠️ Instalasi
1. Kompres folder ini menjadi `haohao-qris-payment.zip`.
2. Buka dashboard WordPress: **Plugins ➔ Add New ➔ Upload Plugin**.
3. Aktifkan plugin.
4. Buka **WooCommerce ➔ Settings ➔ Payments ➔ QRIS (Scan & Bayar Instan)** untuk memasukkan Merchant ID dan Secret Key.

## 👨‍💻 Pengembang
Dikembangkan oleh **Ahmad Yusuf Arifin**
