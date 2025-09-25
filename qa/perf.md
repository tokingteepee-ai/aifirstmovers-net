# Performance Optimization Guidelines

This document outlines performance optimization strategies and best practices for AIFirstMovers.net to ensure fast loading times, excellent user experience, and improved search rankings.

## Performance Goals

### Core Web Vitals Targets
- **Largest Contentful Paint (LCP)**: < 2.5 seconds
- **First Input Delay (FID)**: < 100 milliseconds  
- **Cumulative Layout Shift (CLS)**: < 0.1
- **First Contentful Paint (FCP)**: < 1.8 seconds
- **Time to Interactive (TTI)**: < 3.5 seconds

### Additional Metrics
- **Page Load Time**: < 3 seconds
- **Time to First Byte (TTFB)**: < 600ms
- **Speed Index**: < 3.0
- **Total Blocking Time**: < 300ms

## Server-Side Optimization

### Hosting Recommendations
- SSD storage for faster disk I/O
- PHP 8.0+ for improved performance
- HTTP/2 support for multiplexing
- CDN integration for global delivery
- Adequate CPU and RAM resources

### PHP Configuration
```php
// php.ini optimizations
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 64M
post_max_size = 64M
max_input_vars = 3000

// Enable OPcache
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

### Database Optimization
```sql
-- Regular database maintenance
OPTIMIZE TABLE wp_posts;
OPTIMIZE TABLE wp_options;
OPTIMIZE TABLE wp_postmeta;

-- Remove unnecessary data
DELETE FROM wp_posts WHERE post_status = 'trash';
DELETE FROM wp_comments WHERE comment_approved = 'spam';
DELETE FROM wp_options WHERE option_name LIKE '_transient_%';
```

## WordPress Optimization

### Core Optimizations
```php
// functions.php performance enhancements
function aifm_performance_optimizations() {
    // Remove unnecessary WordPress features
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Disable emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Remove query strings from static resources
    add_filter('script_loader_src', 'aifm_remove_script_version', 15, 1);
    add_filter('style_loader_src', 'aifm_remove_script_version', 15, 1);
    
    // Optimize heartbeat API
    add_filter('heartbeat_settings', 'aifm_optimize_heartbeat');
}
add_action('init', 'aifm_performance_optimizations');

function aifm_remove_script_version($src) {
    $parts = explode('?ver', $src);
    return $parts[0];
}

function aifm_optimize_heartbeat($settings) {
    $settings['interval'] = 60; // Increase interval to 60 seconds
    return $settings;
}

// Disable heartbeat on frontend
add_action('init', function() {
    if (!is_admin()) {
        wp_deregister_script('heartbeat');
    }
});
```

### Plugin Optimization
```php
// Conditionally load plugins
function aifm_conditional_plugin_loading() {
    // Only load contact form plugin on contact page
    if (!is_page('contact')) {
        wp_dequeue_script('contact-form-7');
        wp_dequeue_style('contact-form-7');
    }
    
    // Only load slider plugin on homepage
    if (!is_front_page()) {
        wp_dequeue_script('slider-plugin');
        wp_dequeue_style('slider-plugin');
    }
}
add_action('wp_enqueue_scripts', 'aifm_conditional_plugin_loading');

// Optimize plugin loading
function aifm_optimize_plugin_loading() {
    // Remove unused Gutenberg styles on frontend
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('classic-theme-styles');
    }
}
add_action('wp_enqueue_scripts', 'aifm_optimize_plugin_loading', 100);
```

## Frontend Optimization

### CSS Optimization
```css
/* Use efficient CSS selectors */
.header-nav ul li a { /* Specific but not overly complex */ }

/* Avoid inefficient selectors */
/* * { } - Universal selector */
/* div > * { } - Universal child selector */
/* [attribute="value"] { } - Attribute selectors */

/* Minimize CSS properties */
.button {
    /* Use shorthand properties */
    margin: 10px 0;
    padding: 15px 30px;
    border: 1px solid #ccc;
    
    /* Avoid expensive properties */
    /* box-shadow: 0 0 10px rgba(0,0,0,0.5); */
    /* border-radius: 50%; */
}

/* Use CSS Grid and Flexbox efficiently */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    /* More efficient than floats */
}
```

### JavaScript Optimization
```javascript
// Efficient DOM manipulation
function aifm_optimize_javascript() {
    // Cache DOM elements
    const header = document.getElementById('header');
    const buttons = document.querySelectorAll('.button');
    
    // Use event delegation
    document.addEventListener('click', function(e) {
        if (e.target.matches('.button')) {
            // Handle button clicks
        }
    });
    
    // Debounce scroll events
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        scrollTimeout = setTimeout(function() {
            // Handle scroll
        }, 100);
    });
    
    // Use requestAnimationFrame for animations
    function animate() {
        // Animation code
        requestAnimationFrame(animate);
    }
}

// Lazy load non-critical JavaScript
function aifm_lazy_load_scripts() {
    // Load analytics after page load
    window.addEventListener('load', function() {
        const script = document.createElement('script');
        script.src = 'https://www.google-analytics.com/analytics.js';
        document.head.appendChild(script);
    });
}
```

### Image Optimization
```php
// Responsive images
function aifm_responsive_images() {
    // Add image sizes
    add_image_size('hero-desktop', 1920, 800, true);
    add_image_size('hero-tablet', 1024, 600, true);
    add_image_size('hero-mobile', 768, 400, true);
    
    // Generate responsive image markup
    add_filter('wp_get_attachment_image_attributes', 'aifm_responsive_image_attributes', 10, 3);
}

function aifm_responsive_image_attributes($attr, $attachment, $size) {
    $attr['sizes'] = '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw';
    return $attr;
}

// WebP image support
function aifm_webp_support() {
    add_filter('wp_generate_attachment_metadata', 'aifm_generate_webp_images');
}

function aifm_generate_webp_images($metadata) {
    // Generate WebP versions of uploaded images
    // Use imagewebp() function or external service
    return $metadata;
}

// Lazy loading implementation
function aifm_lazy_loading() {
    add_filter('wp_get_attachment_image_attributes', 'aifm_add_lazy_loading');
}

function aifm_add_lazy_loading($attr) {
    $attr['loading'] = 'lazy';
    $attr['decoding'] = 'async';
    return $attr;
}
```

## Caching Strategies

### Browser Caching
```apache
# .htaccess file
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### WordPress Object Caching
```php
// Implement object caching
function aifm_object_caching() {
    // Cache expensive database queries
    $cache_key = 'aifm_featured_resources';
    $featured_resources = wp_cache_get($cache_key);
    
    if (false === $featured_resources) {
        // Expensive query
        $featured_resources = get_posts(array(
            'post_type' => 'resource',
            'meta_query' => array(
                array(
                    'key' => 'featured',
                    'value' => '1'
                )
            ),
            'posts_per_page' => 3
        ));
        
        // Cache for 1 hour
        wp_cache_set($cache_key, $featured_resources, '', 3600);
    }
    
    return $featured_resources;
}

// Transient caching for external API calls
function aifm_transient_caching() {
    $cache_key = 'aifm_external_data';
    $data = get_transient($cache_key);
    
    if (false === $data) {
        // External API call
        $response = wp_remote_get('https://api.example.com/data');
        $data = wp_remote_retrieve_body($response);
        
        // Cache for 15 minutes
        set_transient($cache_key, $data, 15 * MINUTE_IN_SECONDS);
    }
    
    return $data;
}
```

### Page Caching
```php
// Simple page caching implementation
function aifm_page_caching() {
    if (is_admin() || is_user_logged_in()) {
        return;
    }
    
    $cache_key = 'page_' . md5($_SERVER['REQUEST_URI']);
    $cached_page = wp_cache_get($cache_key);
    
    if (false !== $cached_page) {
        echo $cached_page;
        exit;
    }
    
    // Start output buffering
    ob_start('aifm_cache_page_output');
}

function aifm_cache_page_output($content) {
    $cache_key = 'page_' . md5($_SERVER['REQUEST_URI']);
    wp_cache_set($cache_key, $content, '', 3600); // Cache for 1 hour
    return $content;
}
```

## CDN Implementation

### CDN Configuration
```php
// CDN URL replacement
function aifm_cdn_urls($url) {
    $cdn_url = 'https://cdn.aifirstmovers.net';
    $site_url = home_url();
    
    // Replace site URL with CDN URL for static assets
    if (strpos($url, '/wp-content/') !== false) {
        $url = str_replace($site_url, $cdn_url, $url);
    }
    
    return $url;
}
add_filter('wp_get_attachment_url', 'aifm_cdn_urls');
add_filter('stylesheet_uri', 'aifm_cdn_urls');
add_filter('script_loader_src', 'aifm_cdn_urls');
```

### Asset Optimization
```php
// Combine and minify CSS/JS
function aifm_optimize_assets() {
    if (!is_admin()) {
        // Dequeue individual CSS files
        wp_dequeue_style('theme-style-1');
        wp_dequeue_style('theme-style-2');
        
        // Enqueue combined and minified version
        wp_enqueue_style('aifm-combined', get_template_directory_uri() . '/assets/dist/combined.min.css');
        
        // Same for JavaScript
        wp_dequeue_script('theme-script-1');
        wp_dequeue_script('theme-script-2');
        wp_enqueue_script('aifm-combined-js', get_template_directory_uri() . '/assets/dist/combined.min.js', array(), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'aifm_optimize_assets', 100);
```

## Database Optimization

### Query Optimization
```php
// Efficient WordPress queries
function aifm_efficient_queries() {
    // Use WP_Query efficiently
    $args = array(
        'post_type' => 'resource',
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => 'featured',
                'value' => '1',
                'compare' => '='
            )
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'resource-type',
                'field' => 'slug',
                'terms' => 'article'
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true, // Skip pagination count
        'update_post_meta_cache' => false, // Skip meta cache if not needed
        'update_post_term_cache' => false // Skip term cache if not needed
    );
    
    $query = new WP_Query($args);
    return $query;
}

// Optimize meta queries
function aifm_optimize_meta_queries() {
    // Use EXISTS queries when possible
    $meta_query = array(
        'relation' => 'AND',
        array(
            'key' => 'featured',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => 'priority',
            'value' => 'high',
            'compare' => '='
        )
    );
    
    return $meta_query;
}
```

### Database Maintenance
```php
// Automated database cleanup
function aifm_database_cleanup() {
    global $wpdb;
    
    // Clean expired transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%' AND option_name NOT IN (SELECT REPLACE(option_name, '_transient_timeout_', '_transient_') FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%')");
    
    // Clean spam comments
    $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    
    // Clean post revisions (keep last 3)
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    
    // Optimize tables
    $wpdb->query("OPTIMIZE TABLE {$wpdb->posts}");
    $wpdb->query("OPTIMIZE TABLE {$wpdb->options}");
    $wpdb->query("OPTIMIZE TABLE {$wpdb->comments}");
}

// Schedule weekly cleanup
if (!wp_next_scheduled('aifm_weekly_cleanup')) {
    wp_schedule_event(time(), 'weekly', 'aifm_weekly_cleanup');
}
add_action('aifm_weekly_cleanup', 'aifm_database_cleanup');
```

## Performance Monitoring

### Performance Metrics Tracking
```javascript
// Web Vitals monitoring
function aifm_track_web_vitals() {
    // Core Web Vitals
    import('web-vitals').then(({getCLS, getFID, getFCP, getLCP, getTTFB}) => {
        getCLS(sendToAnalytics);
        getFID(sendToAnalytics);
        getFCP(sendToAnalytics);
        getLCP(sendToAnalytics);
        getTTFB(sendToAnalytics);
    });
    
    function sendToAnalytics(metric) {
        gtag('event', metric.name, {
            event_category: 'Web Vitals',
            value: Math.round(metric.name === 'CLS' ? metric.value * 1000 : metric.value),
            event_label: metric.id,
            non_interaction: true,
        });
    }
}

// Performance observer
function aifm_performance_observer() {
    if ('PerformanceObserver' in window) {
        const observer = new PerformanceObserver((list) => {
            for (const entry of list.getEntries()) {
                if (entry.entryType === 'navigation') {
                    console.log('Page Load Time:', entry.loadEventEnd - entry.fetchStart);
                }
                if (entry.entryType === 'resource') {
                    if (entry.duration > 1000) {
                        console.log('Slow resource:', entry.name, entry.duration);
                    }
                }
            }
        });
        
        observer.observe({entryTypes: ['navigation', 'resource']});
    }
}
```

### Server Monitoring
```php
// Server performance monitoring
function aifm_server_monitoring() {
    // Track PHP memory usage
    $memory_usage = memory_get_usage(true);
    $memory_peak = memory_get_peak_usage(true);
    
    // Log if memory usage is high
    if ($memory_usage > 128 * 1024 * 1024) { // 128MB
        error_log("High memory usage: " . size_format($memory_usage));
    }
    
    // Track database query count
    if (defined('SAVEQUERIES') && SAVEQUERIES) {
        global $wpdb;
        $query_count = count($wpdb->queries);
        
        if ($query_count > 100) {
            error_log("High query count: " . $query_count . " queries");
        }
    }
    
    // Track page generation time
    $start_time = $_SERVER['REQUEST_TIME_FLOAT'];
    $end_time = microtime(true);
    $page_time = $end_time - $start_time;
    
    if ($page_time > 3.0) {
        error_log("Slow page generation: " . $page_time . " seconds for " . $_SERVER['REQUEST_URI']);
    }
}
add_action('wp_footer', 'aifm_server_monitoring');
```

## Performance Testing Tools

### Automated Testing
```bash
# Lighthouse CI
npm install -g @lhci/cli
lhci autorun --upload.target=temporary-public-storage

# WebPageTest API
curl "https://www.webpagetest.org/runtest.php?url=https://aifirstmovers.net&k=API_KEY&f=json"

# Load testing with Artillery
npm install -g artillery
artillery quick --count 100 --num 10 https://aifirstmovers.net
```

### Performance Budget
```json
{
  "budget": [
    {
      "resourceType": "script",
      "maximumCount": 10,
      "maximumSize": 500000
    },
    {
      "resourceType": "stylesheet",
      "maximumCount": 5,
      "maximumSize": 100000
    },
    {
      "resourceType": "image",
      "maximumSize": 2000000
    },
    {
      "resourceType": "document",
      "maximumSize": 50000
    }
  ]
}
```

## Maintenance Schedule

### Daily Tasks
- [ ] Monitor Core Web Vitals
- [ ] Check error logs
- [ ] Review server response times
- [ ] Monitor CDN performance

### Weekly Tasks  
- [ ] Performance audit with Lighthouse
- [ ] Database optimization
- [ ] Cache hit rate analysis
- [ ] Image optimization review

### Monthly Tasks
- [ ] Comprehensive performance review
- [ ] Update performance budget
- [ ] Review and optimize slow queries
- [ ] CDN usage analysis

### Quarterly Tasks
- [ ] Complete performance audit
- [ ] Server configuration review
- [ ] Performance strategy adjustment
- [ ] Tool and service evaluation

## Emergency Response

### Performance Issues
1. **Immediate Actions**
   - Enable maintenance mode if severe
   - Check server status and resources
   - Disable non-essential plugins
   - Clear all caches

2. **Investigation**
   - Review error logs
   - Check database performance
   - Analyze recent changes
   - Monitor third-party services

3. **Resolution**
   - Apply fixes based on findings
   - Test thoroughly
   - Monitor recovery
   - Document incident and lessons learned