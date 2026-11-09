# 🏮 HaoHao Mandarin - WordPress Theme & WooCommerce QRIS Payment Gateway Plugin

Solusi lengkap platform kursus bahasa Mandarin online berbasis WordPress & WooCommerce yang dilengkapi dengan **Custom Theme Modern** dan **Plugin Pembayaran QRIS Dinamis Otomatis**.

> 🌐 **Live Demo Website & QRIS Simulator:** [https://haohao-mandarin-qris.vercel.app](https://haohao-mandarin-qris.vercel.app)  
> ⚡ **Status:** Production Ready • WooCommerce & Dynamic QRIS Integrated

---

## ⚡ Fitur Utama

### 1. 💳 WooCommerce QRIS Payment Gateway Plugin
* **Kompatibel Seluruh Bank & E-Wallet:** BCA, Mandiri, BRI, BNI, GoPay, OVO, ShopeePay, DANA, LinkAja.
* **Dynamic QRIS (Standar ASPI / Bank Indonesia):** Nominal terkunci sesuai tagihan order siswa.
* **REST API Webhook Callback:** Endpoint `/wp-json/haohao-qris/v1/webhook` dengan verifikasi tanda tangan digital **HMAC-SHA256** untuk aktivasi kelas otomatis tanpa konfirmasi manual.
* **Auto-Check Client Polling:** Polling status real-time di layar pembayaran; otomatis berpindah ke halaman sukses begitu pembayaran diterima.
* **Proteksi Idempotensi:** Mencegah aktivasi ganda saat webhook terpanggil berulang.

### 2. 🎨 HaoHao Mandarin Custom Theme
* **Desain Oriental-Modern:** Nuansa warna merah kekaisaran (*Imperial Red*), aksen emas (*Gold*), dan slate elegan.
* **Tipografi Mandarin:** Didukung font Google *Noto Sans SC* untuk ketajaman karakter Hanzi (`好好汉语`).
* **Katalog Program:** Modul kelas HSK 1-2 (Pemula), HSK 3-4 (Menengah), dan Mandarin Bisnis Eksekutif.
* **Fully Responsive:** Optimal di smartphone, tablet, maupun desktop.

---

## 🚀 Cara Menjalankan (Demo Simulator)

Jalankan simulator lokal menggunakan Python (versi 3.8+):

```bash
pip install -r requirements.txt
python run_demo.py
```

Setelah server aktif, langsung buka browser di:  
👉 **http://localhost:8080**

---

## 📦 Instalasi di WordPress / WooCommerce

File `.zip` siap pakai tersedia di folder `dist/`:
1. **Upload Theme:** Masuk ke WP-Admin ➔ **Appearance ➔ Themes ➔ Add New ➔ Upload Theme** ➔ pilih `dist/haohao-mandarin-theme.zip`.
2. **Upload Plugin:** Masuk ke WP-Admin ➔ **Plugins ➔ Add New ➔ Upload Plugin** ➔ pilih `dist/haohao-qris-payment.zip`.
3. Masuk ke **WooCommerce ➔ Settings ➔ Payments ➔ QRIS** untuk konfigurasi Merchant ID dan Secret Key.
