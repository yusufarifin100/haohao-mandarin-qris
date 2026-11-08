<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner container">
        <div class="site-brand">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-link">
                <span class="brand-chinese">好好汉语</span>
                <span class="brand-title">HaoHao Mandarin</span>
            </a>
            <span class="brand-tagline">Kursus Bahasa Mandarin Interaktif #1</span>
        </div>

        <nav class="main-navigation" id="site-nav">
            <ul class="nav-menu">
                <li><a href="#courses">Program Kelas</a></li>
                <li><a href="#curriculum">Kurikulum HSK</a></li>
                <li><a href="#teachers">Profil Laoshi</a></li>
                <li><a href="#testimonials">Testimoni</a></li>
                <li><a href="#qris-payment" class="nav-cta">Daftar Kursus (QRIS)</a></li>
            </ul>
        </nav>

        <button class="mobile-menu-toggle" id="menu-toggle" aria-label="Buka Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
