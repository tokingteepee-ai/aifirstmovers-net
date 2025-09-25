<?php
/**
 * Custom Taxonomies
 *
 * @package AIFM_Core
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AIFM Taxonomies Class
 */
class AIFM_Taxonomies {

    /**
     * Instance
     *
     * @var AIFM_Taxonomies
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return AIFM_Taxonomies
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
        add_action('init', array($this, 'register_taxonomies'));
        add_action('resource_type_add_form_fields', array($this, 'add_taxonomy_fields'));
        add_action('resource_type_edit_form_fields', array($this, 'edit_taxonomy_fields'), 10, 2);
        add_action('created_resource_type', array($this, 'save_taxonomy_fields'));
        add_action('edited_resource_type', array($this, 'save_taxonomy_fields'));
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        $this->register_resource_type_taxonomy();
        $this->register_resource_topic_taxonomy();
    }

    /**
     * Register Resource Type taxonomy
     */
    private function register_resource_type_taxonomy() {
        $labels = array(
            'name'                       => _x('Types', 'taxonomy general name', 'aifm-core'),
            'singular_name'              => _x('Type', 'taxonomy singular name', 'aifm-core'),
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
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'resource-type'),
            'capabilities'          => array(
                'manage_terms' => 'manage_categories',
                'edit_terms'   => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts',
            ),
        );

        register_taxonomy('resource_type', array('resource'), $args);
    }

    /**
     * Register Resource Topic taxonomy (hierarchical)
     */
    private function register_resource_topic_taxonomy() {
        $labels = array(
            'name'                       => _x('Topics', 'taxonomy general name', 'aifm-core'),
            'singular_name'              => _x('Topic', 'taxonomy singular name', 'aifm-core'),
            'search_items'               => __('Search Topics', 'aifm-core'),
            'all_items'                  => __('All Topics', 'aifm-core'),
            'parent_item'                => __('Parent Topic', 'aifm-core'),
            'parent_item_colon'          => __('Parent Topic:', 'aifm-core'),
            'edit_item'                  => __('Edit Topic', 'aifm-core'),
            'update_item'                => __('Update Topic', 'aifm-core'),
            'add_new_item'               => __('Add New Topic', 'aifm-core'),
            'new_item_name'              => __('New Topic Name', 'aifm-core'),
            'menu_name'                  => __('Topics', 'aifm-core'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'topic'),
            'capabilities'      => array(
                'manage_terms' => 'manage_categories',
                'edit_terms'   => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts',
            ),
        );

        register_taxonomy('resource_topic', array('resource'), $args);
    }

    /**
     * Add custom fields to taxonomy add form
     */
    public function add_taxonomy_fields() {
        ?>
        <div class="form-field">
            <label for="type_color"><?php _e('Color', 'aifm-core'); ?></label>
            <input type="color" name="type_color" id="type_color" value="#2563eb" />
            <p><?php _e('Choose a color to represent this type in the frontend.', 'aifm-core'); ?></p>
        </div>

        <div class="form-field">
            <label for="type_icon"><?php _e('Icon Class', 'aifm-core'); ?></label>
            <input type="text" name="type_icon" id="type_icon" value="" />
            <p><?php _e('CSS class for the icon (e.g., dashicons-media-document, fa-file-pdf)', 'aifm-core'); ?></p>
        </div>

        <div class="form-field">
            <label for="type_order"><?php _e('Display Order', 'aifm-core'); ?></label>
            <input type="number" name="type_order" id="type_order" value="0" min="0" />
            <p><?php _e('Order in which this type should be displayed (0 = first)', 'aifm-core'); ?></p>
        </div>
        <?php
    }

    /**
     * Edit custom fields in taxonomy edit form
     *
     * @param WP_Term $term Current taxonomy term object
     */
    public function edit_taxonomy_fields($term) {
        $type_color = get_term_meta($term->term_id, 'type_color', true);
        $type_icon = get_term_meta($term->term_id, 'type_icon', true);
        $type_order = get_term_meta($term->term_id, 'type_order', true);

        if (empty($type_color)) {
            $type_color = '#2563eb';
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="type_color"><?php _e('Color', 'aifm-core'); ?></label>
            </th>
            <td>
                <input type="color" name="type_color" id="type_color" value="<?php echo esc_attr($type_color); ?>" />
                <p class="description"><?php _e('Choose a color to represent this type in the frontend.', 'aifm-core'); ?></p>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="type_icon"><?php _e('Icon Class', 'aifm-core'); ?></label>
            </th>
            <td>
                <input type="text" name="type_icon" id="type_icon" value="<?php echo esc_attr($type_icon); ?>" />
                <p class="description"><?php _e('CSS class for the icon (e.g., dashicons-media-document, fa-file-pdf)', 'aifm-core'); ?></p>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="type_order"><?php _e('Display Order', 'aifm-core'); ?></label>
            </th>
            <td>
                <input type="number" name="type_order" id="type_order" value="<?php echo esc_attr($type_order); ?>" min="0" />
                <p class="description"><?php _e('Order in which this type should be displayed (0 = first)', 'aifm-core'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save custom taxonomy fields
     *
     * @param int $term_id Term ID
     */
    public function save_taxonomy_fields($term_id) {
        if (isset($_POST['type_color'])) {
            update_term_meta($term_id, 'type_color', sanitize_hex_color($_POST['type_color']));
        }

        if (isset($_POST['type_icon'])) {
            update_term_meta($term_id, 'type_icon', sanitize_text_field($_POST['type_icon']));
        }

        if (isset($_POST['type_order'])) {
            update_term_meta($term_id, 'type_order', absint($_POST['type_order']));
        }
    }

    /**
     * Get resource types with metadata
     *
     * @return array Array of resource types with metadata
     */
    public static function get_resource_types_with_meta() {
        $terms = get_terms(array(
            'taxonomy'   => 'resource_type',
            'hide_empty' => false,
        ));

        if (is_wp_error($terms) || empty($terms)) {
            return array();
        }

        $types_with_meta = array();

        foreach ($terms as $term) {
            $type_data = array(
                'term'  => $term,
                'color' => get_term_meta($term->term_id, 'type_color', true) ?: '#2563eb',
                'icon'  => get_term_meta($term->term_id, 'type_icon', true) ?: 'dashicons-media-document',
                'order' => get_term_meta($term->term_id, 'type_order', true) ?: 0,
            );

            $types_with_meta[] = $type_data;
        }

        // Sort by order
        usort($types_with_meta, function($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $types_with_meta;
    }

    /**
     * Get hierarchical resource topics
     *
     * @return array Hierarchical array of topics
     */
    public static function get_resource_topics_hierarchical() {
        $terms = get_terms(array(
            'taxonomy'   => 'resource_topic',
            'hide_empty' => false,
        ));

        if (is_wp_error($terms) || empty($terms)) {
            return array();
        }

        return self::build_term_hierarchy($terms);
    }

    /**
     * Build hierarchical term structure
     *
     * @param array $terms Flat array of terms
     * @param int   $parent_id Parent term ID
     * @return array Hierarchical array
     */
    private static function build_term_hierarchy($terms, $parent_id = 0) {
        $hierarchy = array();

        foreach ($terms as $term) {
            if ($term->parent == $parent_id) {
                $term->children = self::build_term_hierarchy($terms, $term->term_id);
                $hierarchy[] = $term;
            }
        }

        return $hierarchy;
    }
}