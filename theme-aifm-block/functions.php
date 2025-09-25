<?php
/**
 * AIFirstMovers Block Theme Functions
 *
 * @package AIFirstMovers_Block
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

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for block styles
    add_theme_support('wp-block-styles');

    // Add support for custom line height
    add_theme_support('custom-line-height');

    // Add support for custom units
    add_theme_support('custom-units');

    // Add support for custom spacing
    add_theme_support('custom-spacing');

    // Add support for appearance tools
    add_theme_support('appearance-tools');

    // Add support for border
    add_theme_support('border');

    // Add support for link color
    add_theme_support('link-color');

    // Register nav menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'aifm-block'),
        'footer'  => __('Footer Menu', 'aifm-block'),
        'social'  => __('Social Menu', 'aifm-block'),
    ));
}
add_action('after_setup_theme', 'aifm_block_setup');

/**
 * Enqueue block editor assets
 */
function aifm_block_editor_assets() {
    // Enqueue editor styles
    wp_enqueue_style(
        'aifm-block-editor-style',
        get_theme_file_uri('assets/css/editor-style.css'),
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('enqueue_block_editor_assets', 'aifm_block_editor_assets');

/**
 * Enqueue styles and scripts
 */
function aifm_block_enqueue_assets() {
    // Enqueue theme stylesheet
    wp_enqueue_style(
        'aifm-block-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // Enqueue custom JavaScript if exists
    if (file_exists(get_theme_file_path('assets/js/main.js'))) {
        wp_enqueue_script(
            'aifm-block-script',
            get_theme_file_uri('assets/js/main.js'),
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'aifm_block_enqueue_assets');

/**
 * Register block patterns
 */
function aifm_block_register_patterns() {
    // Hero section pattern
    register_block_pattern(
        'aifm-block/hero-section',
        array(
            'title'       => __('Hero Section', 'aifm-block'),
            'description' => __('A hero section with background and centered text', 'aifm-block'),
            'content'     => '<!-- wp:group {"className":"aifm-hero-section"} -->
<div class="wp-block-group aifm-hero-section">
    <!-- wp:heading {"level":1,"textAlign":"center","textColor":"white"} -->
    <h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Welcome to AI First Movers</h1>
    <!-- /wp:heading -->
    
    <!-- wp:paragraph {"align":"center","textColor":"white"} -->
    <p class="has-text-align-center has-white-color has-text-color">Leading the future of artificial intelligence innovation</p>
    <!-- /wp:paragraph -->
    
    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"accent","textColor":"white"} -->
        <div class="wp-block-button"><a class="wp-block-button__link has-white-color has-accent-background-color has-text-color has-background wp-element-button">Get Started</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
            'categories'  => array('featured'),
        )
    );

    // Feature grid pattern
    register_block_pattern(
        'aifm-block/feature-grid',
        array(
            'title'       => __('Feature Grid', 'aifm-block'),
            'description' => __('A grid of features with icons and descriptions', 'aifm-block'),
            'content'     => '<!-- wp:group {"className":"aifm-feature-grid"} -->
<div class="wp-block-group aifm-feature-grid">
    <!-- wp:columns -->
    <div class="wp-block-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":3,"textColor":"primary"} -->
            <h3 class="wp-block-heading has-primary-color has-text-color">Innovation</h3>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Cutting-edge AI solutions for modern businesses</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":3,"textColor":"primary"} -->
            <h3 class="wp-block-heading has-primary-color has-text-color">Expertise</h3>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Years of experience in artificial intelligence</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":3,"textColor":"primary"} -->
            <h3 class="wp-block-heading has-primary-color has-text-color">Results</h3>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Proven track record of successful implementations</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->',
            'categories'  => array('columns'),
        )
    );
}
add_action('init', 'aifm_block_register_patterns');

/**
 * Add custom block styles
 */
function aifm_block_register_block_styles() {
    // Add rounded style for buttons
    register_block_style(
        'core/button',
        array(
            'name'  => 'rounded',
            'label' => __('Rounded', 'aifm-block'),
        )
    );

    // Add shadow style for groups
    register_block_style(
        'core/group',
        array(
            'name'  => 'shadow',
            'label' => __('With Shadow', 'aifm-block'),
        )
    );
}
add_action('init', 'aifm_block_register_block_styles');