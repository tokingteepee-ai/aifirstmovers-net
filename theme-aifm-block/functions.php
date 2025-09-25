<?php
/**
 * AI First Movers Block Theme Functions
 *
 * @package AIFM_Block
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function aifm_block_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Enqueue editor styles
    add_editor_style('assets/css/editor-style.css');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for custom line height controls
    add_theme_support('custom-line-height');

    // Add support for custom units
    add_theme_support('custom-units');

    // Add support for custom spacing
    add_theme_support('custom-spacing');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'               => 60,
        'width'                => 200,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array('site-title', 'site-description'),
        'unlink-homepage-logo' => false,
    ));

    // HTML5 markup support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'aifm-block'),
        'footer'  => __('Footer Menu', 'aifm-block'),
        'social'  => __('Social Menu', 'aifm-block'),
    ));
}
add_action('after_setup_theme', 'aifm_block_setup');

/**
 * Enqueue scripts and styles
 */
function aifm_block_scripts() {
    // Theme stylesheet
    wp_enqueue_style(
        'aifm-block-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // Custom JavaScript
    wp_enqueue_script(
        'aifm-block-script',
        get_template_directory_uri() . '/assets/js/theme.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Localize script for AJAX
    wp_localize_script('aifm-block-script', 'aifmAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('aifm_nonce'),
    ));

    // Load comment reply script on singular posts/pages with comments open
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aifm_block_scripts');

/**
 * Enqueue block editor assets
 */
function aifm_block_editor_assets() {
    wp_enqueue_script(
        'aifm-block-editor',
        get_template_directory_uri() . '/assets/js/editor.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        wp_get_theme()->get('Version'),
        true
    );

    wp_enqueue_style(
        'aifm-block-editor-style',
        get_template_directory_uri() . '/assets/css/editor-style.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('enqueue_block_editor_assets', 'aifm_block_editor_assets');

/**
 * Register block patterns
 */
function aifm_block_patterns() {
    // Hero section pattern
    register_block_pattern(
        'aifm-block/hero-section',
        array(
            'title'       => __('Hero Section', 'aifm-block'),
            'description' => __('A hero section with heading, description, and call-to-action button.', 'aifm-block'),
            'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem","left":"2rem","right":"2rem"}}},"backgroundColor":"primary","textColor":"bg-white","className":"aifm-hero-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull aifm-hero-section has-bg-white-color has-primary-background-color has-text-color has-background" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"heading-1"} -->
<h1 class="wp-block-heading has-text-align-center has-heading-1-font-size">Welcome to AI First Movers</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Leading the way in artificial intelligence innovation and strategy.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:2rem"><!-- wp:button {"className":"aifm-button-primary"} -->
<div class="wp-block-button aifm-button-primary"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
            'categories'  => array('featured'),
        )
    );

    // Three column feature pattern
    register_block_pattern(
        'aifm-block/three-column-features',
        array(
            'title'       => __('Three Column Features', 'aifm-block'),
            'description' => __('Three columns with icon, heading, and description.', 'aifm-block'),
            'content'     => '<!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}}} -->
<div class="wp-block-columns alignwide" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:column {"className":"aifm-card"} -->
<div class="wp-block-column aifm-card"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">AI Strategy</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Develop comprehensive AI strategies that align with your business objectives and drive sustainable growth.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"aifm-card"} -->
<div class="wp-block-column aifm-card"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Implementation</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Expert implementation services to bring your AI vision to life with proven methodologies and best practices.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"aifm-card"} -->
<div class="wp-block-column aifm-card"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Support</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Ongoing support and optimization to ensure your AI solutions continue to deliver maximum value.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
            'categories'  => array('columns'),
        )
    );
}
add_action('init', 'aifm_block_patterns');

/**
 * Add custom body classes
 */
function aifm_block_body_classes($classes) {
    $classes[] = 'aifm-block-theme';
    
    if (is_front_page()) {
        $classes[] = 'aifm-home';
    }
    
    if (is_singular('resource')) {
        $classes[] = 'aifm-resource';
    }
    
    return $classes;
}
add_filter('body_class', 'aifm_block_body_classes');

/**
 * Modify the excerpt length
 */
function aifm_block_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'aifm_block_excerpt_length');

/**
 * Modify the excerpt more text
 */
function aifm_block_excerpt_more($more) {
    return sprintf(
        '&hellip; <a href="%s" class="read-more">%s</a>',
        get_permalink(),
        __('Continue reading', 'aifm-block')
    );
}
add_filter('excerpt_more', 'aifm_block_excerpt_more');

/**
 * Add skip link for accessibility
 */
function aifm_block_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#main">' . __('Skip to content', 'aifm-block') . '</a>';
}
add_action('wp_body_open', 'aifm_block_skip_link');