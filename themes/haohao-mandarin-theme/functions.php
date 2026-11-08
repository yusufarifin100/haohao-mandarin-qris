<?php
/**
 * HaoHao Mandarin Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

function haohao_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Menu Utama', 'haohao-mandarin'),
        'footer'  => __('Menu Footer', 'haohao-mandarin'),
    ));
}
add_action('after_setup_theme', 'haohao_theme_setup');

/**
 * Enqueue scripts and styles
 */
function haohao_theme_scripts() {
    // Google Fonts: Plus Jakarta Sans & Noto Sans SC (Mandarin Hanzi)
    wp_enqueue_style('haohao-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', array(), null);

    // Theme Main CSS
    wp_enqueue_style('haohao-main-style', get_template_directory_uri() . '/assets/css/theme.css', array(), '1.0.0');

    // Theme Main JS
    wp_enqueue_script('haohao-main-script', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'haohao_theme_scripts');

/**
 * Register Custom Post Type: Courses (Kelas Mandarin)
 */
function haohao_register_course_post_type() {
    $labels = array(
        'name'               => __('Kelas Mandarin', 'haohao-mandarin'),
        'singular_name'      => __('Kelas Mandarin', 'haohao-mandarin'),
        'add_new'            => __('Tambah Kelas Baru', 'haohao-mandarin'),
        'edit_item'          => __('Edit Kelas', 'haohao-mandarin'),
        'all_items'          => __('Semua Kelas', 'haohao-mandarin'),
        'menu_name'          => __('Program Kursus', 'haohao-mandarin'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'            => array('slug' => 'courses'),
        'show_in_rest'       => true,
    );

    register_post_type('haohao_course', $args);
}
add_action('init', 'haohao_register_course_post_type');
