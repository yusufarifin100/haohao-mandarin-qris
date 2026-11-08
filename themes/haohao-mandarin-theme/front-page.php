<?php
/**
 * Front Page Template - HaoHao Mandarin
 */
get_header(); ?>

<main class="front-page-main">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="badge-pill">
                    <span class="badge-icon">🧧</span> Pendaftaran Kelas Baru Semester Ini Dibuka
                </div>
                <h1 class="hero-title">
                    Kuasai Bahasa Mandarin.<br>
                    <span class="text-highlight">Buka Peluang Karir Global.</span>
                </h1>
                <p class="hero-subtitle">
                    Belajar dari nol hingga mahir HSK bersama Laoshi bersertifikat. Sistem belajar fleksibel, interaktif, dengan pembayaran otomatis QRIS secepat kilat.
                </p>
                <div class="hero-cta-group">
                    <a href="#courses" class="btn btn-primary">Lihat Pilihan Kelas</a>
                    <a href="#qris-demo" class="btn btn-secondary">Simulasi Bayar QRIS ➔</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <strong>1.500+</strong>
                        <span>Alumni Sukses</span>
                    </div>
                    <div class="stat-item">
                        <strong>98.4%</strong>
                        <span>Tingkat Kelulusan HSK</span>
                    </div>
                    <div class="stat-item">
                        <strong>100%</strong>
                        <span>Laoshi Bersertifikat</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="visual-card">
                    <div class="visual-badge">好好汉语 &bull; Belajar Seru</div>
                    <div class="chinese-art">
                        <span class="large-hanzi">學</span>
                        <p class="pinyin-text">xué &bull; Belajar / Menuntut Ilmu</p>
                    </div>
                    <div class="course-quick-card">
                        <div class="quick-icon">🎓</div>
                        <div>
                            <h4>HSK 1-3 Comprehensive</h4>
                            <p>Tersedia Kelas Malam & Weekend</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kursus Populer Grid -->
    <section class="courses-section" id="courses">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">Program Unggulan</span>
                <h2>Pilihan Kelas HaoHao Mandarin</h2>
                <p>Kurikulum berstandar resmi Hanban & Ujian HSK Internasional terbaru.</p>
            </div>

            <div class="course-grid">
                <!-- Course 1 -->
                <div class="course-card">
                    <div class="course-badge beginner">Pemula Nol</div>
                    <div class="course-header">
                        <span class="hanzi-title">基础汉语 &bull; HSK 1 - 2</span>
                        <h3>Mandarin Dasar & Pelafalan Pinyin</h3>
                    </div>
                    <p class="course-desc">Kuasai 300 kosakata dasar, nada bicara, serta tata bahasa percakapan sehari-hari.</p>
                    <ul class="course-features">
                        <li>✓ 16 Sesi Live via Zoom + Rekaman</li>
                        <li>✓ Bimbingan Nada & Pinyin Intensif</li>
                        <li>✓ Modul Digital & Latihan Hanzi</li>
                    </ul>
                    <div class="course-pricing">
                        <div class="price-box">
                            <span class="currency">Rp</span>
                            <span class="amount">650.000</span>
                            <span class="period">/level</span>
                        </div>
                        <a href="#qris-demo" class="btn btn-enroll" data-course="HSK 1-2 Dasar" data-price="650000">Daftar via QRIS</a>
                    </div>
                </div>

                <!-- Course 2 (Featured) -->
                <div class="course-card featured">
                    <div class="featured-ribbon">Terpopuler</div>
                    <div class="course-badge intermediate">Menengah</div>
                    <div class="course-header">
                        <span class="hanzi-title">进阶汉语 &bull; HSK 3 - 4</span>
                        <h3>Persiapan Ujian HSK 3 & 4 Intensif</h3>
                    </div>
                    <p class="course-desc">Fokus pemahaman membaca artikel, percakapan natural, dan bedah soal ujian HSK resmi.</p>
                    <ul class="course-features">
                        <li>✓ 24 Sesi Pembahasan Soal & Simulasi Ujian</li>
                        <li>✓ 600+ Kosakata & Pola Kalimat Kompleks</li>
                        <li>✓ Garansi Bimbingan Ujian sampai Lolos</li>
                    </ul>
                    <div class="course-pricing">
                        <div class="price-box">
                            <span class="currency">Rp</span>
                            <span class="amount">950.000</span>
                            <span class="period">/level</span>
                        </div>
                        <a href="#qris-demo" class="btn btn-primary btn-enroll" data-course="HSK 3-4 Intensif" data-price="950000">Daftar via QRIS</a>
                    </div>
                </div>

                <!-- Course 3 -->
                <div class="course-card">
                    <div class="course-badge business">Karir & Bisnis</div>
                    <div class="course-header">
                        <span class="hanzi-title">商务汉语 &bull; Business Class</span>
                        <h3>Mandarin Bisnis, Negosiasi & Ekspor</h3>
                    </div>
                    <p class="course-desc">Kuasai istilah bisnis, etika korespondensi email, dan percakapan negosiasi dagang dengan partner Tiongkok.</p>
                    <ul class="course-features">
                        <li>✓ 12 Sesi Studi Kasus Negosiasi Nyata</li>
                        <li>✓ Template Email & Kontrak Bisnis Bilingual</li>
                        <li>✓ Bimbingan Kosakata Sesuai Industri Anda</li>
                    </ul>
                    <div class="course-pricing">
                        <div class="price-box">
                            <span class="currency">Rp</span>
                            <span class="amount">1.350.000</span>
                            <span class="period">/paket</span>
                        </div>
                        <a href="#qris-demo" class="btn btn-enroll" data-course="Mandarin Bisnis" data-price="1350000">Daftar via QRIS</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Sistem Pembayaran QRIS -->
    <section class="qris-highlight-section" id="qris-payment">
        <div class="container highlight-box">
            <div class="highlight-text">
                <span class="section-tag-light">Integrasi Otomatis</span>
                <h2>Kemudahan Pembayaran Instan dengan QRIS</h2>
                <p>Tidak perlu konfirmasi bukti transfer manual lewat WhatsApp. Sistem website kami terhubung langsung dengan gateway perbankan nasional.</p>
                <div class="feature-bullets">
                    <div class="f-item">
                        <span class="check-icon">⚡</span>
                        <div>
                            <strong>Verifikasi 10 Detik</strong>
                            <p>Sistem membaca webhook callback dan langsung mengaktifkan akun kelas Anda.</p>
                        </div>
                    </div>
                    <div class="f-item">
                        <span class="check-icon">🔒</span>
                        <div>
                            <strong>Keamanan Tingkat Tinggi (HMAC-SHA256)</strong>
                            <p>Transaksi terlindungi dengan enkripsi tanda tangan digital anti-manipulasi data.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="highlight-qr-card">
                <div class="qr-mockup">
                    <div class="qr-mockup-header">
                        <span>HaoHao QRIS Engine</span>
                        <span class="badge-live">Live</span>
                    </div>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="Logo QRIS" class="qr-logo-demo">
                    <div class="qr-code-placeholder">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=HAOHAO-MANDARIN-DEMO-PAYMENT" alt="QRIS Demo">
                    </div>
                    <p class="qr-note">Dukungan: BCA Mobile, Livin, GoPay, OVO, ShopeePay</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
