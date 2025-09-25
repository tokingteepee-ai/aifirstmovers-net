<?php
/**
 * Meta Boxes
 *
 * @package AIFM_Core
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AIFM Meta Boxes Class
 */
class AIFM_Meta_Boxes {

    /**
     * Instance
     *
     * @var AIFM_Meta_Boxes
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return AIFM_Meta_Boxes
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
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // SEO meta box for resources
        add_meta_box(
            'resource_seo',
            __('SEO Settings', 'aifm-core'),
            array($this, 'seo_meta_box_callback'),
            'resource',
            'normal',
            'low'
        );

        // Analytics meta box for resources
        add_meta_box(
            'resource_analytics',
            __('Analytics', 'aifm-core'),
            array($this, 'analytics_meta_box_callback'),
            'resource',
            'side',
            'low'
        );
    }

    /**
     * Enqueue admin scripts for meta boxes
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type === 'resource' && ($hook === 'post.php' || $hook === 'post-new.php')) {
            wp_enqueue_media();
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker-style', 'https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css');
        }
    }

    /**
     * SEO meta box callback
     *
     * @param WP_Post $post Current post object
     */
    public function seo_meta_box_callback($post) {
        wp_nonce_field('aifm_seo_meta_box', 'aifm_seo_meta_box_nonce');

        $meta_title = get_post_meta($post->ID, '_seo_title', true);
        $meta_description = get_post_meta($post->ID, '_seo_description', true);
        $meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);
        $canonical_url = get_post_meta($post->ID, '_canonical_url', true);
        $noindex = get_post_meta($post->ID, '_seo_noindex', true);
        $nofollow = get_post_meta($post->ID, '_seo_nofollow', true);

        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="seo_title"><?php _e('Meta Title', 'aifm-core'); ?></label>
                </th>
                <td>
                    <input type="text" id="seo_title" name="seo_title" value="<?php echo esc_attr($meta_title); ?>" class="large-text" maxlength="60" />
                    <p class="description">
                        <?php _e('Custom title for search engines (60 characters max). Leave empty to use post title.', 'aifm-core'); ?>
                        <span id="seo-title-counter">0/60</span>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="seo_description"><?php _e('Meta Description', 'aifm-core'); ?></label>
                </th>
                <td>
                    <textarea id="seo_description" name="seo_description" rows="3" class="large-text" maxlength="160"><?php echo esc_textarea($meta_description); ?></textarea>
                    <p class="description">
                        <?php _e('Description for search engines (160 characters max). Leave empty to use excerpt.', 'aifm-core'); ?>
                        <span id="seo-description-counter">0/160</span>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="seo_keywords"><?php _e('Focus Keywords', 'aifm-core'); ?></label>
                </th>
                <td>
                    <input type="text" id="seo_keywords" name="seo_keywords" value="<?php echo esc_attr($meta_keywords); ?>" class="large-text" />
                    <p class="description"><?php _e('Comma-separated keywords (for internal use, not meta keywords tag)', 'aifm-core'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="canonical_url"><?php _e('Canonical URL', 'aifm-core'); ?></label>
                </th>
                <td>
                    <input type="url" id="canonical_url" name="canonical_url" value="<?php echo esc_attr($canonical_url); ?>" class="large-text" />
                    <p class="description"><?php _e('Custom canonical URL (leave empty to use default)', 'aifm-core'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Search Engine Visibility', 'aifm-core'); ?></th>
                <td>
                    <fieldset>
                        <label for="seo_noindex">
                            <input type="checkbox" name="seo_noindex" id="seo_noindex" value="1" <?php checked($noindex, '1'); ?> />
                            <?php _e('Discourage search engines from indexing this resource', 'aifm-core'); ?>
                        </label><br>
                        <label for="seo_nofollow">
                            <input type="checkbox" name="seo_nofollow" id="seo_nofollow" value="1" <?php checked($nofollow, '1'); ?> />
                            <?php _e('Discourage search engines from following links on this resource', 'aifm-core'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>

        <script>
        jQuery(document).ready(function($) {
            function updateCounter(input, counter, max) {
                var length = $(input).val().length;
                $(counter).text(length + '/' + max);
                if (length > max * 0.9) {
                    $(counter).css('color', length > max ? 'red' : 'orange');
                } else {
                    $(counter).css('color', 'green');
                }
            }

            $('#seo_title').on('input', function() {
                updateCounter(this, '#seo-title-counter', 60);
            }).trigger('input');

            $('#seo_description').on('input', function() {
                updateCounter(this, '#seo-description-counter', 160);
            }).trigger('input');
        });
        </script>
        <?php
    }

    /**
     * Analytics meta box callback
     *
     * @param WP_Post $post Current post object
     */
    public function analytics_meta_box_callback($post) {
        global $wpdb;

        // Get analytics data
        $table_name = $wpdb->prefix . 'aifm_resource_analytics';
        $analytics = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE resource_id = %d",
            $post->ID
        ));

        $view_count = $analytics ? $analytics->view_count : 0;
        $download_count = $analytics ? $analytics->download_count : 0;
        $last_viewed = $analytics ? $analytics->last_viewed : __('Never', 'aifm-core');

        if ($last_viewed !== __('Never', 'aifm-core')) {
            $last_viewed = wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($last_viewed));
        }

        ?>
        <div class="aifm-analytics-stats">
            <div class="stat-item">
                <strong><?php echo number_format($view_count); ?></strong>
                <span><?php _e('Views', 'aifm-core'); ?></span>
            </div>
            <div class="stat-item">
                <strong><?php echo number_format($download_count); ?></strong>
                <span><?php _e('Downloads', 'aifm-core'); ?></span>
            </div>
            <div class="stat-item">
                <strong><?php echo esc_html($last_viewed); ?></strong>
                <span><?php _e('Last Viewed', 'aifm-core'); ?></span>
            </div>
        </div>

        <style>
        .aifm-analytics-stats {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #f9f9f9;
            border-radius: 4px;
            flex: 1;
        }
        .stat-item strong {
            display: block;
            font-size: 1.5em;
            color: #2563eb;
        }
        .stat-item span {
            font-size: 0.9em;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        </style>
        <?php

        // Add reset button for admins
        if (current_user_can('manage_options')) {
            ?>
            <p style="margin-top: 1rem;">
                <button type="button" class="button" id="reset-analytics" data-post-id="<?php echo $post->ID; ?>">
                    <?php _e('Reset Analytics', 'aifm-core'); ?>
                </button>
            </p>

            <script>
            jQuery(document).ready(function($) {
                $('#reset-analytics').on('click', function() {
                    if (confirm('<?php _e('Are you sure you want to reset analytics for this resource?', 'aifm-core'); ?>')) {
                        var postId = $(this).data('post-id');
                        
                        $.post(ajaxurl, {
                            action: 'aifm_reset_analytics',
                            post_id: postId,
                            nonce: '<?php echo wp_create_nonce('aifm_reset_analytics'); ?>'
                        }, function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                alert('<?php _e('Error resetting analytics', 'aifm-core'); ?>');
                            }
                        });
                    }
                });
            });
            </script>
            <?php
        }
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta_boxes($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save SEO meta box data
        if (isset($_POST['aifm_seo_meta_box_nonce']) && wp_verify_nonce($_POST['aifm_seo_meta_box_nonce'], 'aifm_seo_meta_box')) {
            $seo_fields = array(
                'seo_title',
                'seo_description', 
                'seo_keywords',
                'canonical_url',
                'seo_noindex',
                'seo_nofollow',
            );

            foreach ($seo_fields as $field) {
                if (isset($_POST[$field])) {
                    $value = ($field === 'seo_description') ? sanitize_textarea_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
                    update_post_meta($post_id, '_' . $field, $value);
                } else {
                    delete_post_meta($post_id, '_' . $field);
                }
            }
        }
    }
}