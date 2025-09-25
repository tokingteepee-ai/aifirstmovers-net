<?php
/**
 * Custom Post Types
 *
 * @package AIFM_Core
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AIFM Post Types Class
 */
class AIFM_Post_Types {

    /**
     * Instance
     *
     * @var AIFM_Post_Types
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return AIFM_Post_Types
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
        add_action('init', array($this, 'register_post_types'));
        add_filter('enter_title_here', array($this, 'change_title_placeholder'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        $this->register_resource_post_type();
    }

    /**
     * Register Resource post type
     */
    private function register_resource_post_type() {
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
            'featured_image'        => _x('Resource Featured Image', 'Overrides the "Featured Image" phrase', 'aifm-core'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'aifm-core'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'aifm-core'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'aifm-core'),
            'archives'              => _x('Resource archives', 'The post type archive label', 'aifm-core'),
            'insert_into_item'      => _x('Insert into resource', 'Overrides the "Insert into post"/"Insert into page" phrase', 'aifm-core'),
            'uploaded_to_this_item' => _x('Uploaded to this resource', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'aifm-core'),
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
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-media-document',
            'supports'           => array(
                'title',
                'editor',
                'excerpt',
                'author',
                'thumbnail',
                'comments',
                'revisions',
                'custom-fields',
                'page-attributes',
            ),
            'show_in_nav_menus'  => true,
            'can_export'         => true,
            'delete_with_user'   => false,
        );

        register_post_type('resource', $args);
    }

    /**
     * Change title placeholder for custom post types
     *
     * @param string $title Default title placeholder
     * @return string Modified title placeholder
     */
    public function change_title_placeholder($title) {
        $screen = get_current_screen();

        if ($screen && 'resource' === $screen->post_type) {
            $title = __('Enter resource title here', 'aifm-core');
        }

        return $title;
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'resource_details',
            __('Resource Details', 'aifm-core'),
            array($this, 'resource_details_callback'),
            'resource',
            'normal',
            'high'
        );

        add_meta_box(
            'resource_files',
            __('Resource Files', 'aifm-core'),
            array($this, 'resource_files_callback'),
            'resource',
            'side',
            'default'
        );
    }

    /**
     * Resource details meta box callback
     *
     * @param WP_Post $post Current post object
     */
    public function resource_details_callback($post) {
        wp_nonce_field('aifm_resource_meta_box', 'aifm_resource_meta_box_nonce');

        $resource_url = get_post_meta($post->ID, '_resource_url', true);
        $resource_description = get_post_meta($post->ID, '_resource_description', true);
        $resource_difficulty = get_post_meta($post->ID, '_resource_difficulty', true);
        $resource_duration = get_post_meta($post->ID, '_resource_duration', true);

        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="resource_url"><?php _e('Resource URL', 'aifm-core'); ?></label>
                </th>
                <td>
                    <input type="url" id="resource_url" name="resource_url" value="<?php echo esc_attr($resource_url); ?>" class="regular-text" />
                    <p class="description"><?php _e('External URL for this resource (optional)', 'aifm-core'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="resource_description"><?php _e('Short Description', 'aifm-core'); ?></label>
                </th>
                <td>
                    <textarea id="resource_description" name="resource_description" rows="3" class="large-text"><?php echo esc_textarea($resource_description); ?></textarea>
                    <p class="description"><?php _e('Brief description of the resource (used in listings)', 'aifm-core'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="resource_difficulty"><?php _e('Difficulty Level', 'aifm-core'); ?></label>
                </th>
                <td>
                    <select id="resource_difficulty" name="resource_difficulty">
                        <option value=""><?php _e('Select difficulty...', 'aifm-core'); ?></option>
                        <option value="beginner" <?php selected($resource_difficulty, 'beginner'); ?>><?php _e('Beginner', 'aifm-core'); ?></option>
                        <option value="intermediate" <?php selected($resource_difficulty, 'intermediate'); ?>><?php _e('Intermediate', 'aifm-core'); ?></option>
                        <option value="advanced" <?php selected($resource_difficulty, 'advanced'); ?>><?php _e('Advanced', 'aifm-core'); ?></option>
                        <option value="expert" <?php selected($resource_difficulty, 'expert'); ?>><?php _e('Expert', 'aifm-core'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="resource_duration"><?php _e('Reading/Completion Time', 'aifm-core'); ?></label>
                </th>
                <td>
                    <input type="text" id="resource_duration" name="resource_duration" value="<?php echo esc_attr($resource_duration); ?>" class="regular-text" />
                    <p class="description"><?php _e('e.g., "5 minutes", "2 hours", "1 week"', 'aifm-core'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Resource files meta box callback
     *
     * @param WP_Post $post Current post object
     */
    public function resource_files_callback($post) {
        $resource_file = get_post_meta($post->ID, '_resource_file', true);
        $file_size = get_post_meta($post->ID, '_resource_file_size', true);

        ?>
        <p>
            <label for="resource_file"><?php _e('Download File', 'aifm-core'); ?></label><br>
            <input type="url" id="resource_file" name="resource_file" value="<?php echo esc_attr($resource_file); ?>" class="widefat" />
            <small><?php _e('URL to downloadable file (PDF, DOC, etc.)', 'aifm-core'); ?></small>
        </p>
        
        <p>
            <label for="resource_file_size"><?php _e('File Size', 'aifm-core'); ?></label><br>
            <input type="text" id="resource_file_size" name="resource_file_size" value="<?php echo esc_attr($file_size); ?>" class="widefat" />
            <small><?php _e('e.g., "2.5 MB", "1.2 GB"', 'aifm-core'); ?></small>
        </p>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta_boxes($post_id) {
        // Check if nonce is valid
        if (!isset($_POST['aifm_resource_meta_box_nonce']) || !wp_verify_nonce($_POST['aifm_resource_meta_box_nonce'], 'aifm_resource_meta_box')) {
            return;
        }

        // Check if user can edit the post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if this is the correct post type
        if (get_post_type($post_id) !== 'resource') {
            return;
        }

        // Save meta fields
        $meta_fields = array(
            'resource_url',
            'resource_description',
            'resource_difficulty',
            'resource_duration',
            'resource_file',
            'resource_file_size',
        );

        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}