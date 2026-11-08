# 🏮 HaoHao Mandarin - WordPress Theme & WooCommerce QRIS Payment Gateway Plugin

Solusi lengkap platform kursus bahasa Mandarin online berbasis WordPress & WooCommerce yang dilengkapi dengan **Custom Theme Modern** dan **Plugin Pembayaran QRIS Dinamis Otomatis**.

Proyek ini dibangun secara khusus untuk memenuhi seluruh kriteria teknis **Freelance WordPress Developer at HaoHao Mandarin**:
- ✅ Pembuatan responsive website & custom WordPress theme
- ✅ Pembuatan plugin custom WordPress: **QRIS QR Payment Plugin**
- ✅ Dokumentasi mendalam arsitektur alur pembayaran (**QR Payment Flow**)
- ✅ Pengujian menyeluruh (*QA Cross-Device & Functional Testing*)

---

## 📂 Struktur Proyek

```
haohao-mandarin-qris/
├── plugins/
│   └── haohao-qris-payment/        # Plugin WordPress WooCommerce QRIS Gateway
│       ├── haohao-qris-payment.php  # Entry point plugin
│       ├── includes/               # Logika gateway & webhook handler HMAC-SHA256
│       └── assets/                 # CSS & JS polling auto-check
├── themes/
│   └── haohao-mandarin-theme/      # Tema WordPress Kursus Mandarin
│       ├── style.css               # Header tema WordPress resmi
│       ├── functions.php           # Enqueue assets, WooCommerce support, CPT Kursus
│       ├── header.php & footer.php # Navigasi & footer oriental modern
│       └── front-page.php          # Landing page kelas HSK & pendaftaran
├── docs/
│   ├── QR_PAYMENT_FLOW.md          # Dokumentasi teknis & sequence diagram alur QRIS
│   └── QA_TESTING_REPORT.md        # Laporan pengujian fungsional & lintas browser
├── dist/                           # File .zip siap upload ke WordPress asli
│   ├── haohao-qris-payment.zip     # Zip Plugin
│   └── haohao-mandarin-theme.zip   # Zip Theme
├── demo/                           # Simulator interaktif mandiri (FastAPI + Modern Web)
├── build_packages.py               # Script otomatis mem-bundle theme & plugin ke .zip
└── run_demo.py                     # Script menjalankan live web simulator
```

---

## ⚡ Fitur Utama

### 1. 💳 WooCommerce QRIS Payment Gateway Plugin
* **Kompatibel Seluruh Bank & E-Wallet:** BCA, Mandiri, BRI, BNI, GoPay, OVO, ShopeePay, DANA, LinkAja.
* **Dynamic QRIS (Standar ASPI / Bank Indonesia):** Nominal terkunci sesuai tagihan order siswa.
* **REST API Webhook Callback:** Endpoint `/wp-json/haohao-qris/v1/webhook` dengan verifikasi tanda tangan digital **HMAC-SHA256** untuk aktivasi kelas otomatis tanpa kirim bukti transfer manual.
* **Auto-Check Client Polling:** Polling status real-time di layar siswa; otomatis beralih ke halaman sukses begitu pembayaran terkonfirmasi.
* **Proteksi Idempotensi:** Mencegah aktivasi dobel saat webhook terpanggil lebih dari satu kali.

### 2. 🎨 HaoHao Mandarin Custom Theme
* **Desain Oriental-Modern:** Kombinasi warna merah kekaisaran (*Imperial Red*), aksen emas (*Gold*), dan slate elegan.
* **Tipografi Mandarin:** Didukung font Google *Noto Sans SC* untuk ketajaman karakter Hanzi (`好好汉语`).
* **Katalog Program:** Modul kelas HSK 1-2 (Pemula), HSK 3-4 (Menengah), dan Mandarin Bisnis Eksekutif.
* **Fully Responsive:** Teruji optimal di mobile smartphone, tablet, maupun layar desktop.

---

## 🚀 Cara Menjalankan Live Demo Simulator

Untuk menguji coba alur pendaftaran dan simulasi pembayaran QRIS secara langsung di browser tanpa perlu menginstall server Apache/MySQL lokal:

1. Buka terminal di folder project ini:
   ```bash
   cd "E:\App Dev\haohao-mandarin-qris"
   ```
2. Jalankan simulator:
   ```bash
   python run_demo.py
   ```
3. Buka browser di:
   👉 **http://localhost:8080**

---

## 📦 Instalasi di WordPress / WooCommerce Asli

File instalasi siap pakai tersedia di folder `/dist`:
1. **Upload Theme:** Masuk ke WP-Admin ➔ **Appearance ➔ Themes ➔ Add New ➔ Upload Theme** ➔ pilih `dist/haohao-mandarin-theme.zip`.
2. **Upload Plugin:** Masuk ke WP-Admin ➔ **Plugins ➔ Add New ➔ Upload Plugin** ➔ pilih `dist/haohao-qris-payment.zip`.
3. Masuk ke **WooCommerce ➔ Settings ➔ Payments ➔ QRIS** untuk mengatur Merchant ID dan Secret Key.

---

## 👨‍💻 Pengembang
Dikembangkan oleh **Ahmad Yusuf Arifin**  
*Portfolio Project for HaoHao Mandarin Freelance WordPress Developer Role*
