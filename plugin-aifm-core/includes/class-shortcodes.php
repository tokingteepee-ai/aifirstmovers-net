<?php
/**
 * Shortcodes
 *
 * @package AIFM_Core
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AIFM Shortcodes Class
 */
class AIFM_Shortcodes {

    /**
     * Instance
     *
     * @var AIFM_Shortcodes
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return AIFM_Shortcodes
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
        add_action('init', array($this, 'register_shortcodes'));
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('aifm_resources', array($this, 'resources_shortcode'));
        add_shortcode('aifm_resource_types', array($this, 'resource_types_shortcode'));
        add_shortcode('aifm_featured_resources', array($this, 'featured_resources_shortcode'));
    }

    /**
     * Resources listing shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function resources_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'type' => '',
            'topic' => '',
            'columns' => 3,
            'show_excerpt' => 'true',
            'show_meta' => 'true',
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts, 'aifm_resources');

        $args = array(
            'post_type' => 'resource',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        // Add taxonomy filters
        if (!empty($atts['type'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'resource_type',
                'field' => 'slug',
                'terms' => explode(',', $atts['type']),
            );
        }

        if (!empty($atts['topic'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'resource_topic',
                'field' => 'slug',
                'terms' => explode(',', $atts['topic']),
            );
        }

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return '<p>' . __('No resources found.', 'aifm-core') . '</p>';
        }

        $columns = max(1, min(4, intval($atts['columns'])));
        $column_class = 'aifm-col-' . (12 / $columns);

        ob_start();
        ?>
        <div class="aifm-resources-grid aifm-columns-<?php echo $columns; ?>">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="aifm-resource-item <?php echo $column_class; ?>">
                    <div class="aifm-resource-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="aifm-resource-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="aifm-resource-content">
                            <h3 class="aifm-resource-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ($atts['show_excerpt'] === 'true') : ?>
                                <div class="aifm-resource-excerpt">
                                    <?php
                                    $short_description = get_post_meta(get_the_ID(), '_resource_description', true);
                                    echo $short_description ? esc_html($short_description) : get_the_excerpt();
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($atts['show_meta'] === 'true') : ?>
                                <div class="aifm-resource-meta">
                                    <?php
                                    $types = get_the_terms(get_the_ID(), 'resource_type');
                                    if ($types && !is_wp_error($types)) :
                                        foreach ($types as $type) :
                                            $color = get_term_meta($type->term_id, 'type_color', true) ?: '#2563eb';
                                            ?>
                                            <span class="aifm-resource-type" style="background-color: <?php echo esc_attr($color); ?>">
                                                <?php echo esc_html($type->name); ?>
                                            </span>
                                        <?php endforeach;
                                    endif;

                                    $difficulty = get_post_meta(get_the_ID(), '_resource_difficulty', true);
                                    if ($difficulty) :
                                        ?>
                                        <span class="aifm-resource-difficulty aifm-difficulty-<?php echo esc_attr($difficulty); ?>">
                                            <?php echo ucfirst($difficulty); ?>
                                        </span>
                                    <?php endif;

                                    $duration = get_post_meta(get_the_ID(), '_resource_duration', true);
                                    if ($duration) :
                                        ?>
                                        <span class="aifm-resource-duration">
                                            <?php echo esc_html($duration); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php

        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * Resource types listing shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function resource_types_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_count' => 'true',
            'show_description' => 'true',
            'columns' => 3,
        ), $atts, 'aifm_resource_types');

        $types = AIFM_Taxonomies::get_resource_types_with_meta();

        if (empty($types)) {
            return '<p>' . __('No resource types found.', 'aifm-core') . '</p>';
        }

        $columns = max(1, min(4, intval($atts['columns'])));
        $column_class = 'aifm-col-' . (12 / $columns);

        ob_start();
        ?>
        <div class="aifm-resource-types-grid aifm-columns-<?php echo $columns; ?>">
            <?php foreach ($types as $type_data) :
                $term = $type_data['term'];
                $color = $type_data['color'];
                $icon = $type_data['icon'];
                ?>
                <div class="aifm-resource-type-item <?php echo $column_class; ?>">
                    <div class="aifm-resource-type-card" style="border-color: <?php echo esc_attr($color); ?>">
                        <div class="aifm-type-icon" style="color: <?php echo esc_attr($color); ?>">
                            <i class="<?php echo esc_attr($icon); ?>"></i>
                        </div>
                        
                        <h3 class="aifm-type-title">
                            <a href="<?php echo get_term_link($term); ?>" style="color: <?php echo esc_attr($color); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </h3>

                        <?php if ($atts['show_description'] === 'true' && $term->description) : ?>
                            <div class="aifm-type-description">
                                <?php echo wp_kses_post($term->description); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($atts['show_count'] === 'true') : ?>
                            <div class="aifm-type-count">
                                <?php printf(_n('%d resource', '%d resources', $term->count, 'aifm-core'), $term->count); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Featured resources shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function featured_resources_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 3,
            'meta_key' => '_featured',
            'meta_value' => '1',
            'style' => 'carousel',
        ), $atts, 'aifm_featured_resources');

        $args = array(
            'post_type' => 'resource',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => $atts['meta_key'],
                    'value' => $atts['meta_value'],
                    'compare' => '='
                )
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return '<p>' . __('No featured resources found.', 'aifm-core') . '</p>';
        }

        ob_start();
        ?>
        <div class="aifm-featured-resources aifm-style-<?php echo esc_attr($atts['style']); ?>">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="aifm-featured-resource">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aifm-featured-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="aifm-featured-content">
                        <h3 class="aifm-featured-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <div class="aifm-featured-excerpt">
                            <?php
                            $short_description = get_post_meta(get_the_ID(), '_resource_description', true);
                            echo $short_description ? esc_html($short_description) : get_the_excerpt();
                            ?>
                        </div>

                        <div class="aifm-featured-link">
                            <a href="<?php the_permalink(); ?>" class="aifm-button-primary">
                                <?php _e('Learn More', 'aifm-core'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php

        wp_reset_postdata();
        return ob_get_clean();
    }
}