<?php
/**
 * Plugin Name: AI First Movers Core
 * Plugin URI: https://aifirstmovers.net
 * Description: Core functionality plugin for AI First Movers website including custom post types and taxonomies.
 * Version: 1.0.0
 * Author: AI First Movers Team
 * Author URI: https://aifirstmovers.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: aifm-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.4
 * Requires PHP: 8.0
 *
 * @package AIFM_Core
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AIFM_CORE_VERSION', '1.0.0');
define('AIFM_CORE_PLUGIN_FILE', __FILE__);
define('AIFM_CORE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AIFM_CORE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIFM_CORE_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main AIFM Core Plugin Class
 */
class AIFM_Core {

    /**
     * Plugin instance
     *
     * @var AIFM_Core
     */
    private static $instance = null;

    /**
     * Get plugin instance
     *
     * @return AIFM_Core
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('init', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        require_once AIFM_CORE_PLUGIN_DIR . 'includes/class-post-types.php';
        require_once AIFM_CORE_PLUGIN_DIR . 'includes/class-taxonomies.php';
        require_once AIFM_CORE_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once AIFM_CORE_PLUGIN_DIR . 'includes/class-shortcodes.php';
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize post types
        AIFM_Post_Types::get_instance();
        
        // Initialize taxonomies
        AIFM_Taxonomies::get_instance();
        
        // Initialize meta boxes
        AIFM_Meta_Boxes::get_instance();
        
        // Initialize shortcodes
        AIFM_Shortcodes::get_instance();
    }

    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'aifm-core',
            false,
            dirname(AIFM_CORE_PLUGIN_BASENAME) . '/languages/'
        );
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'aifm-core-style',
            AIFM_CORE_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            AIFM_CORE_VERSION
        );

        wp_enqueue_script(
            'aifm-core-script',
            AIFM_CORE_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            AIFM_CORE_VERSION,
            true
        );

        wp_localize_script('aifm-core-script', 'aifmCore', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aifm_core_nonce'),
        ));
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        global $post_type;

        if ($post_type === 'resource' || $hook === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'resource') {
            wp_enqueue_style(
                'aifm-core-admin',
                AIFM_CORE_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                AIFM_CORE_VERSION
            );

            wp_enqueue_script(
                'aifm-core-admin',
                AIFM_CORE_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery'),
                AIFM_CORE_VERSION,
                true
            );
        }
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables if needed
        $this->create_tables();
        
        // Set default options
        $this->set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set activation flag
        update_option('aifm_core_activated', true);
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Remove activation flag
        delete_option('aifm_core_activated');
    }

    /**
     * Create custom database tables
     */
    private function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Example: Create a custom table for resource analytics
        $table_name = $wpdb->prefix . 'aifm_resource_analytics';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            resource_id bigint(20) NOT NULL,
            view_count int(11) DEFAULT 0,
            download_count int(11) DEFAULT 0,
            last_viewed datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY resource_id (resource_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Set default plugin options
     */
    private function set_default_options() {
        $default_options = array(
            'enable_resource_analytics' => true,
            'resources_per_page' => 12,
            'enable_related_resources' => true,
            'enable_resource_search' => true,
        );

        foreach ($default_options as $option_name => $option_value) {
            if (get_option('aifm_core_' . $option_name) === false) {
                add_option('aifm_core_' . $option_name, $option_value);
            }
        }
    }
}

// Initialize the plugin
AIFM_Core::get_instance();