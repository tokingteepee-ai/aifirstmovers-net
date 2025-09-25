<?php
/**
 * Plugin Name: AIFirstMovers Core
 * Plugin URI: https://aifirstmovers.net
 * Description: Core functionality for AIFirstMovers.net including custom post types and taxonomies
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: AIFirstMovers Team
 * Text Domain: aifm-core
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AIFM_CORE_VERSION', '1.0.0');
define('AIFM_CORE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AIFM_CORE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIFM_CORE_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class AIFM_Core {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        $this->register_post_types();
        $this->register_taxonomies();
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('aifm-core', false, dirname(AIFM_CORE_PLUGIN_BASENAME) . '/languages');
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Register Resource post type
        $labels = array(
            'name'                  => _x('Resources', 'Post type general name', 'aifm-core'),
            'singular_name'         => _x('Resource', 'Post type singular name', 'aifm-core'),
            'menu_name'             => _x('Resources', 'Admin Menu text', 'aifm-core'),
            'name_admin_bar'        => _x('Resource', 'Add New on Toolbar', 'aifm-core'),
            'add_new'               => __('Add New', 'aifm-core'),
            'add_new_item'          => __('Add New Resource', 'aifm-core'),
            'new_item'              => __('New Resource', 'aifm-core'),
            'edit_item'             => __('Edit Resource', 'aifm-core'),
            'view_item'             => __('View Resource', 'aifm-core'),
            'all_items'             => __('All Resources', 'aifm-core'),
            'search_items'          => __('Search Resources', 'aifm-core'),
            'parent_item_colon'     => __('Parent Resources:', 'aifm-core'),
            'not_found'             => __('No resources found.', 'aifm-core'),
            'not_found_in_trash'    => __('No resources found in Trash.', 'aifm-core'),
            'featured_image'        => _x('Resource Cover Image', 'Overrides the "Featured Image" phrase', 'aifm-core'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'aifm-core'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'aifm-core'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'aifm-core'),
            'archives'              => _x('Resource archives', 'The post type archive label', 'aifm-core'),
            'insert_into_item'      => _x('Insert into resource', 'Overrides the "Insert into post" phrase', 'aifm-core'),
            'uploaded_to_this_item' => _x('Uploaded to this resource', 'Overrides the "Uploaded to this post" phrase', 'aifm-core'),
            'filter_items_list'     => _x('Filter resources list', 'Screen reader text for the filter links', 'aifm-core'),
            'items_list_navigation' => _x('Resources list navigation', 'Screen reader text for the pagination', 'aifm-core'),
            'items_list'            => _x('Resources list', 'Screen reader text for the items list', 'aifm-core'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'resources'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-book-alt',
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields'),
            'taxonomies'         => array('resource-type'),
        );
        
        register_post_type('resource', $args);
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Register Resource Type taxonomy
        $labels = array(
            'name'                       => _x('Types', 'Taxonomy general name', 'aifm-core'),
            'singular_name'              => _x('Type', 'Taxonomy singular name', 'aifm-core'),
            'search_items'               => __('Search Types', 'aifm-core'),
            'popular_items'              => __('Popular Types', 'aifm-core'),
            'all_items'                  => __('All Types', 'aifm-core'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Type', 'aifm-core'),
            'update_item'                => __('Update Type', 'aifm-core'),
            'add_new_item'               => __('Add New Type', 'aifm-core'),
            'new_item_name'              => __('New Type Name', 'aifm-core'),
            'separate_items_with_commas' => __('Separate types with commas', 'aifm-core'),
            'add_or_remove_items'        => __('Add or remove types', 'aifm-core'),
            'choose_from_most_used'      => __('Choose from the most used types', 'aifm-core'),
            'not_found'                  => __('No types found.', 'aifm-core'),
            'menu_name'                  => __('Types', 'aifm-core'),
        );
        
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'show_in_rest'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'resource-type'),
        );
        
        register_taxonomy('resource-type', array('resource'), $args);
        
        // Add default terms
        $this->add_default_terms();
    }
    
    /**
     * Add default taxonomy terms
     */
    private function add_default_terms() {
        $default_types = array(
            'Article' => 'In-depth articles about AI topics',
            'Tutorial' => 'Step-by-step tutorials and guides',
            'Case Study' => 'Real-world AI implementation case studies',
            'White Paper' => 'Technical white papers and research',
            'Video' => 'Educational videos and presentations',
            'Tool' => 'AI tools and software recommendations',
            'Research' => 'Latest AI research and findings',
        );
        
        foreach ($default_types as $name => $description) {
            if (!term_exists($name, 'resource-type')) {
                wp_insert_term($name, 'resource-type', array(
                    'description' => $description,
                ));
            }
        }
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        // Enqueue CSS if exists
        if (file_exists(AIFM_CORE_PLUGIN_DIR . 'assets/css/style.css')) {
            wp_enqueue_style(
                'aifm-core-style',
                AIFM_CORE_PLUGIN_URL . 'assets/css/style.css',
                array(),
                AIFM_CORE_VERSION
            );
        }
        
        // Enqueue JS if exists
        if (file_exists(AIFM_CORE_PLUGIN_DIR . 'assets/js/script.js')) {
            wp_enqueue_script(
                'aifm-core-script',
                AIFM_CORE_PLUGIN_URL . 'assets/js/script.js',
                array('jquery'),
                AIFM_CORE_VERSION,
                true
            );
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on our CPT edit screens
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post_type;
        if ('resource' !== $post_type) {
            return;
        }
        
        // Enqueue admin CSS if exists
        if (file_exists(AIFM_CORE_PLUGIN_DIR . 'assets/css/admin.css')) {
            wp_enqueue_style(
                'aifm-core-admin-style',
                AIFM_CORE_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                AIFM_CORE_VERSION
            );
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Register post types and taxonomies
        $this->register_post_types();
        $this->register_taxonomies();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set default options
        add_option('aifm_core_version', AIFM_CORE_VERSION);
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize the plugin
new AIFM_Core();