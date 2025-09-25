<?php
/**
 * AIFirstMovers Child Theme Functions
 *
 * @package AIFirstMovers_Child
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
    // Get parent theme version
    $parent_theme = wp_get_theme()->parent();
    $parent_version = $parent_theme ? $parent_theme->get('Version') : '1.0.0';
    
    // Enqueue parent theme style
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css',
        array(),
        $parent_version
    );
    
    // Enqueue child theme style
    wp_enqueue_style(
        'aifm-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('twentytwentyfive-style'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue custom JavaScript if exists
    if (file_exists(get_stylesheet_directory() . '/assets/js/main.js')) {
        wp_enqueue_script(
            'aifm-child-script',
            get_stylesheet_directory_uri() . '/assets/js/main.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'aifm_child_enqueue_styles');

/**
 * Add theme support for additional features
 */
function aifm_child_theme_setup() {
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'aifm_child_theme_setup');

/**
 * Customize excerpt length
 */
function aifm_child_excerpt_length($length) {
    return 25;
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
 * Register custom navigation menus
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
    // Add class for pages with sidebar
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    }
    
    // Add class for front page
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    
    return $classes;
}
add_filter('body_class', 'aifm_child_body_classes');