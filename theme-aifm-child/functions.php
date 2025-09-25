<?php
/**
 * AI First Movers Child Theme Functions
 *
 * @package AIFM_Child
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function aifm_child_enqueue_styles() {
    // Enqueue parent theme stylesheet
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->get('Version')
    );

    // Enqueue child theme stylesheet
    wp_enqueue_style(
        'aifm-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('twentytwentyfive-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue child theme custom JavaScript
    wp_enqueue_script(
        'aifm-child-script',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'aifm_child_enqueue_styles');

/**
 * Add theme support features
 */
function aifm_child_theme_setup() {
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for custom header
    add_theme_support('custom-header', array(
        'default-color' => 'ffffff',
        'width'         => 1200,
        'height'        => 400,
        'flex-height'   => true,
    ));

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'f8fafc',
    ));
}
add_action('after_setup_theme', 'aifm_child_theme_setup');

/**
 * Customize excerpt length
 */
function aifm_child_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aifm_child_excerpt_length');

/**
 * Add custom excerpt more text
 */
function aifm_child_excerpt_more($more) {
    return '... <a href="' . get_permalink() . '" class="read-more">Read More</a>';
}
add_filter('excerpt_more', 'aifm_child_excerpt_more');

/**
 * Register custom menu locations
 */
function aifm_child_register_menus() {
    register_nav_menus(array(
        'footer-menu' => __('Footer Menu', 'aifm-child'),
        'social-menu' => __('Social Media Menu', 'aifm-child'),
    ));
}
add_action('init', 'aifm_child_register_menus');

/**
 * Add custom body classes
 */
function aifm_child_body_classes($classes) {
    $classes[] = 'aifm-theme';
    
    if (is_front_page()) {
        $classes[] = 'aifm-home';
    }
    
    return $classes;
}
add_filter('body_class', 'aifm_child_body_classes');

/**
 * Enqueue Google Fonts
 */
function aifm_child_google_fonts() {
    wp_enqueue_style(
        'aifm-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'aifm_child_google_fonts');