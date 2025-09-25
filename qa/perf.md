# Performance Optimization Guide

This guide provides comprehensive strategies and implementation details for optimizing the AI First Movers website performance, ensuring fast loading times and excellent user experience.

## Performance Goals and Metrics

### Core Web Vitals Targets
- **Largest Contentful Paint (LCP)**: < 2.5 seconds
- **First Input Delay (FID)**: < 100 milliseconds  
- **Cumulative Layout Shift (CLS)**: < 0.1
- **Time to First Byte (TTFB)**: < 800 milliseconds
- **First Contentful Paint (FCP)**: < 1.8 seconds

### Additional Performance Metrics
- **Page Load Time**: < 3 seconds on 3G connection
- **Time to Interactive (TTI)**: < 5 seconds
- **Speed Index**: < 3.0 seconds
- **Total Blocking Time (TBT)**: < 200 milliseconds

### Performance Budget
- **Total Page Weight**: < 2MB
- **JavaScript Bundle**: < 500KB
- **CSS Bundle**: < 200KB
- **Images**: < 1MB per page
- **Number of HTTP Requests**: < 50

## Server and Hosting Optimization

### Hosting Requirements

#### Recommended Server Specifications
- **CPU**: Minimum 4 cores, 2.4GHz or higher
- **RAM**: Minimum 8GB, 16GB recommended
- **Storage**: SSD with at least 100GB available space
- **Bandwidth**: Unlimited or high allocation
- **PHP Version**: 8.1 or higher
- **MySQL Version**: 8.0 or higher

#### Server Configuration
```apache
# .htaccess optimizations for Apache
<IfModule mod_deflate.c>
    # Enable compression
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/json
    AddOutputFilterByType DEFLATE font/truetype
    AddOutputFilterByType DEFLATE font/opentype
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive on
    
    # CSS and JavaScript
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType application/x-javascript "access plus 1 year"
    
    # Images
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/webp "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
    
    # Fonts
    ExpiresByType font/truetype "access plus 1 year"
    ExpiresByType font/opentype "access plus 1 year"
    ExpiresByType application/vnd.ms-fontobject "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    
    # Other assets
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/x-javascript "access plus 1 year"
    ExpiresByType application/x-shockwave-flash "access plus 1 year"
</IfModule>

<IfModule mod_headers.c>
    # Add security and performance headers
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
    
    # Enable HTTP/2 Server Push (if supported)
    <FilesMatch "\.(css|js)$">
        Header add Link "<%{REQUEST_URI}s>; rel=preload; as=style" env=CSS
        Header add Link "<%{REQUEST_URI}s>; rel=preload; as=script" env=JS
    </FilesMatch>
</IfModule>

# Enable HTTP/2 (requires server support)
<IfModule mod_http2.c>
    Protocols h2 http/1.1
</IfModule>
```

### Content Delivery Network (CDN)

#### CDN Setup with Cloudflare
1. **Account Setup**: Create Cloudflare account and add domain
2. **DNS Configuration**: Update nameservers to Cloudflare
3. **SSL Configuration**: Enable Full SSL encryption
4. **Caching Rules**: Configure page rules for optimal caching
5. **Image Optimization**: Enable Polish and Mirage features

#### CDN Configuration
```javascript
// Cloudflare Page Rules
[
  {
    "url": "aifirstmovers.net/wp-content/uploads/*",
    "settings": {
      "cache_level": "cache_everything",
      "edge_cache_ttl": 2592000 // 30 days
    }
  },
  {
    "url": "aifirstmovers.net/wp-content/themes/*/assets/*",
    "settings": {
      "cache_level": "cache_everything",
      "edge_cache_ttl": 31536000 // 1 year
    }
  },
  {
    "url": "aifirstmovers.net/*",
    "settings": {
      "cache_level": "standard",
      "browser_cache_ttl": 14400 // 4 hours
    }
  }
]
```

## WordPress Optimization

### wp-config.php Optimizations
```php
// Memory limit
define('WP_MEMORY_LIMIT', '512M');

// Enable caching
define('WP_CACHE', true);

// Optimize database
define('WP_AUTO_UPDATE_CORE', 'minor');
define('AUTOMATIC_UPDATER_DISABLED', false);

// Limit post revisions
define('WP_POST_REVISIONS', 3);

// Set autosave interval (in seconds)
define('AUTOSAVE_INTERVAL', 300); // 5 minutes

// Disable file editing
define('DISALLOW_FILE_EDIT', true);

// Database optimization
define('WP_ALLOW_REPAIR', true); // Remove after use

// Compression
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', false); // Can cause issues, test first
```

### Database Optimization
```php
// Add to functions.php for database optimization
function aifm_optimize_database() {
    global $wpdb;
    
    // Clean up old revisions
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    
    // Clean up spam comments
    $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    
    // Clean up transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name LIKE '_transient_timeout_%'");
    
    // Optimize tables
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    foreach($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE {$table[0]}");
    }
}

// Schedule weekly database cleanup
if (!wp_next_scheduled('aifm_database_cleanup')) {
    wp_schedule_event(time(), 'weekly', 'aifm_database_cleanup');
}
add_action('aifm_database_cleanup', 'aifm_optimize_database');
```

### Plugin Performance Optimization
```php
// Disable unnecessary plugins on specific pages
function aifm_conditional_plugin_loading() {
    // Disable contact form plugin on non-contact pages
    if (!is_page('contact') && !is_page('quote-request')) {
        add_action('wp_print_styles', function() {
            wp_dequeue_style('contact-form-7');
        }, 100);
        
        add_action('wp_print_scripts', function() {
            wp_dequeue_script('contact-form-7');
        }, 100);
    }
    
    // Disable social sharing on non-post pages
    if (!is_singular('post') && !is_singular('resource')) {
        remove_action('wp_enqueue_scripts', 'social_sharing_scripts');
    }
}
add_action('init', 'aifm_conditional_plugin_loading');
```

## Asset Optimization

### Image Optimization

#### Automated Image Optimization
```php
// Add to functions.php for automatic image optimization
function aifm_optimize_images() {
    // Enable WebP support
    add_filter('wp_get_attachment_image_attributes', 'aifm_add_webp_support', 10, 3);
    
    // Add responsive images
    add_filter('wp_get_attachment_image_attributes', 'aifm_add_responsive_images', 10, 3);
    
    // Lazy load images
    add_filter('wp_get_attachment_image_attributes', 'aifm_add_lazy_loading', 10, 3);
}

function aifm_add_webp_support($attr, $attachment, $size) {
    $image_url = wp_get_attachment_image_url($attachment->ID, $size);
    $webp_url = str_replace(array('.jpg', '.jpeg', '.png'), '.webp', $image_url);
    
    // Check if WebP version exists
    if (file_exists(str_replace(site_url(), ABSPATH, $webp_url))) {
        $attr['data-src-webp'] = $webp_url;
    }
    
    return $attr;
}

function aifm_add_responsive_images($attr, $attachment, $size) {
    if (!isset($attr['sizes'])) {
        $attr['sizes'] = '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw';
    }
    return $attr;
}

function aifm_add_lazy_loading($attr, $attachment, $size) {
    if (!is_admin() && !wp_is_mobile()) {
        $attr['loading'] = 'lazy';
        $attr['data-src'] = $attr['src'];
        $attr['src'] = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3C/svg%3E';
        $attr['class'] = (isset($attr['class']) ? $attr['class'] . ' ' : '') . 'lazy-load';
    }
    return $attr;
}

add_action('after_setup_theme', 'aifm_optimize_images');
```

#### Image Format Guidelines
```
Recommended formats by use case:
- Photos/Complex images: WebP (fallback: JPEG)
- Simple graphics/logos: SVG (fallback: PNG)
- Thumbnails: WebP (fallback: JPEG at 75-80% quality)
- Hero images: WebP (multiple sizes for responsive)
- Icons: SVG sprite or icon fonts
```

### CSS Optimization

#### Critical CSS Implementation
```php
// Critical CSS inlining
function aifm_inline_critical_css() {
    if (is_front_page()) {
        $critical_css = file_get_contents(get_stylesheet_directory() . '/assets/css/critical-home.css');
    } elseif (is_singular('resource')) {
        $critical_css = file_get_contents(get_stylesheet_directory() . '/assets/css/critical-resource.css');
    } else {
        $critical_css = file_get_contents(get_stylesheet_directory() . '/assets/css/critical-general.css');
    }
    
    if ($critical_css) {
        echo '<style id="critical-css">' . $critical_css . '</style>';
    }
}
add_action('wp_head', 'aifm_inline_critical_css', 1);

// Load non-critical CSS asynchronously
function aifm_async_css() {
    ?>
    <script>
    (function() {
        var css = document.createElement('link');
        css.rel = 'stylesheet';
        css.href = '<?php echo get_stylesheet_uri(); ?>';
        css.media = 'print';
        css.onload = function() { this.media = 'all'; };
        document.head.appendChild(css);
    })();
    </script>
    <?php
}
add_action('wp_head', 'aifm_async_css', 20);
```

#### CSS Minification and Compression
```php
// CSS optimization function
function aifm_optimize_css() {
    if (!is_admin() && !is_customize_preview()) {
        // Remove unused CSS for specific pages
        add_action('wp_enqueue_scripts', 'aifm_remove_unused_css', 999);
        
        // Minify inline CSS
        add_action('wp_head', 'aifm_minify_inline_css', 999);
    }
}

function aifm_remove_unused_css() {
    // Remove WooCommerce CSS on non-shop pages
    if (!is_woocommerce() && !is_cart() && !is_checkout()) {
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');
    }
    
    // Remove block editor CSS on non-block pages
    if (!is_singular() || !has_blocks()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }
}

function aifm_minify_inline_css() {
    ob_start(function($html) {
        // Minify CSS within <style> tags
        return preg_replace_callback(
            '/<style[^>]*>(.*?)<\/style>/is',
            function($matches) {
                $css = $matches[1];
                // Remove comments
                $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
                // Remove whitespace
                $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
                return '<style' . substr($matches[0], 6, strpos($matches[0], '>') - 6) . '>' . $css . '</style>';
            },
            $html
        );
    });
}

add_action('init', 'aifm_optimize_css');
```

### JavaScript Optimization

#### Script Loading Optimization
```php
// Optimize JavaScript loading
function aifm_optimize_scripts() {
    if (!is_admin()) {
        // Defer non-critical scripts
        add_filter('script_loader_tag', 'aifm_defer_scripts', 10, 3);
        
        // Move jQuery to footer
        add_action('wp_enqueue_scripts', 'aifm_move_jquery_to_footer');
        
        // Remove unnecessary scripts
        add_action('wp_enqueue_scripts', 'aifm_remove_unnecessary_scripts', 999);
    }
}

function aifm_defer_scripts($tag, $handle, $src) {
    // Scripts to defer
    $defer_scripts = array(
        'aifm-core-script',
        'contact-form-7',
        'google-analytics',
        'hubspot-tracking'
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    // Scripts to load async
    $async_scripts = array(
        'google-tag-manager',
        'facebook-pixel'
    );
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    return $tag;
}

function aifm_move_jquery_to_footer() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, NULL, true);
        wp_enqueue_script('jquery');
    }
}

function aifm_remove_unnecessary_scripts() {
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Remove embed script on non-post pages
    if (!is_singular()) {
        wp_dequeue_script('wp-embed');
    }
    
    // Remove comment reply script on pages without comments
    if (!is_singular() || !comments_open()) {
        wp_dequeue_script('comment-reply');
    }
}

add_action('init', 'aifm_optimize_scripts');
```

#### Code Splitting and Bundling
```javascript
// webpack.config.js for asset bundling
const path = require('path');

module.exports = {
  entry: {
    main: './src/js/main.js',
    admin: './src/js/admin.js',
    critical: './src/js/critical.js'
  },
  output: {
    filename: '[name].[contenthash].js',
    path: path.resolve(__dirname, 'assets/js'),
    clean: true
  },
  optimization: {
    splitChunks: {
      chunks: 'all',
      cacheGroups: {
        vendor: {
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors',
          chunks: 'all',
        },
        common: {
          name: 'common',
          minChunks: 2,
          chunks: 'all',
          enforce: true
        }
      }
    }
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
    ]
  }
};
```

## Caching Strategies

### WordPress Caching
```php
// Advanced caching configuration
function aifm_advanced_caching() {
    // Page caching
    add_action('template_redirect', 'aifm_start_page_cache');
    add_action('wp_footer', 'aifm_end_page_cache');
    
    // Object caching
    add_action('init', 'aifm_setup_object_cache');
    
    // Database query caching
    add_filter('query', 'aifm_cache_database_queries');
}

function aifm_start_page_cache() {
    if (!is_admin() && !is_user_logged_in() && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $cache_key = 'page_cache_' . md5($_SERVER['REQUEST_URI']);
        $cached_page = wp_cache_get($cache_key, 'page_cache');
        
        if ($cached_page !== false) {
            echo $cached_page;
            exit;
        }
        
        ob_start();
    }
}

function aifm_end_page_cache() {
    if (!is_admin() && !is_user_logged_in() && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $content = ob_get_contents();
        $cache_key = 'page_cache_' . md5($_SERVER['REQUEST_URI']);
        wp_cache_set($cache_key, $content, 'page_cache', 3600); // Cache for 1 hour
    }
}

function aifm_setup_object_cache() {
    // Enable object caching for expensive operations
    if (!defined('WP_CACHE_KEY_SALT')) {
        define('WP_CACHE_KEY_SALT', 'aifm_' . NONCE_SALT);
    }
}

add_action('after_setup_theme', 'aifm_advanced_caching');
```

### Browser Caching Headers
```php
// Set custom cache headers
function aifm_cache_headers() {
    if (!is_admin()) {
        $expires = 3600; // 1 hour default
        
        if (is_front_page()) {
            $expires = 900; // 15 minutes for homepage
        } elseif (is_singular('resource')) {
            $expires = 86400; // 24 hours for resources
        } elseif (is_page()) {
            $expires = 3600; // 1 hour for pages
        }
        
        header('Cache-Control: public, max-age=' . $expires);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
        header('Vary: Accept-Encoding');
    }
}
add_action('send_headers', 'aifm_cache_headers');
```

## Monitoring and Analytics

### Performance Monitoring Setup
```php
// Performance monitoring
function aifm_performance_monitoring() {
    if (!is_admin()) {
        add_action('wp_footer', 'aifm_performance_tracking');
    }
}

function aifm_performance_tracking() {
    ?>
    <script>
    // Core Web Vitals tracking
    function sendToAnalytics({name, value, id}) {
        gtag('event', name, {
            event_category: 'Web Vitals',
            value: Math.round(name === 'CLS' ? value * 1000 : value),
            event_label: id,
            non_interaction: true,
        });
    }

    // Load web-vitals library
    import('https://unpkg.com/web-vitals?module').then(({getCLS, getFID, getFCP, getLCP, getTTFB}) => {
        getCLS(sendToAnalytics);
        getFID(sendToAnalytics);
        getFCP(sendToAnalytics);
        getLCP(sendToAnalytics);
        getTTFB(sendToAnalytics);
    });

    // Custom performance metrics
    window.addEventListener('load', function() {
        const perfData = performance.getEntriesByType('navigation')[0];
        
        // Track specific metrics
        gtag('event', 'page_load_time', {
            event_category: 'Performance',
            value: Math.round(perfData.loadEventEnd - perfData.fetchStart),
            event_label: location.pathname
        });
        
        gtag('event', 'dom_content_loaded', {
            event_category: 'Performance',
            value: Math.round(perfData.domContentLoadedEventEnd - perfData.fetchStart),
            event_label: location.pathname
        });
    });
    </script>
    <?php
}

add_action('after_setup_theme', 'aifm_performance_monitoring');
```

### Performance Budget Monitoring
```javascript
// Performance budget alerts
const performanceBudget = {
    lcp: 2500,
    fid: 100,
    cls: 0.1,
    fcp: 1800,
    ttfb: 800
};

function checkPerformanceBudget(metric, value) {
    if (value > performanceBudget[metric]) {
        // Send alert to monitoring service
        fetch('/api/performance-alert', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                metric: metric,
                value: value,
                budget: performanceBudget[metric],
                page: location.pathname,
                timestamp: new Date().toISOString()
            })
        });
    }
}
```

## Performance Testing and Optimization Tools

### Automated Testing Setup
```bash
#!/bin/bash
# performance-test.sh - Automated performance testing script

# Lighthouse CI
lighthouse-ci autorun

# WebPageTest API
curl -X POST "https://www.webpagetest.org/runtest.php" \
  -d "url=https://aifirstmovers.net" \
  -d "runs=3" \
  -d "location=Dulles:Chrome" \
  -d "k=YOUR_API_KEY"

# GTmetrix API
curl -u "YOUR_EMAIL:YOUR_API_KEY" \
  -d "url=https://aifirstmovers.net" \
  https://gtmetrix.com/api/0.1/test

# Pingdom
curl -X POST "https://api.pingdom.com/api/3.1/analysis" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d "url=https://aifirstmovers.net"
```

### Performance Optimization Checklist

#### Pre-Launch Optimization
- [ ] Enable server compression (Gzip/Brotli)
- [ ] Configure browser caching headers
- [ ] Optimize and compress all images
- [ ] Minimize CSS and JavaScript files
- [ ] Implement critical CSS inlining
- [ ] Set up CDN for static assets
- [ ] Remove unused plugins and themes
- [ ] Optimize database tables
- [ ] Configure object caching
- [ ] Test page load times across devices

#### Ongoing Optimization
- [ ] Weekly performance monitoring review
- [ ] Monthly image optimization audit
- [ ] Quarterly database cleanup
- [ ] Annual performance strategy review
- [ ] Continuous Core Web Vitals monitoring
- [ ] Regular plugin performance audits
- [ ] CDN performance optimization
- [ ] Mobile performance testing

### Emergency Performance Troubleshooting

#### Common Performance Issues and Solutions

**Slow Database Queries**
```sql
-- Identify slow queries
SHOW PROCESSLIST;
SHOW FULL PROCESSLIST;

-- Analyze query performance
EXPLAIN SELECT * FROM wp_posts WHERE post_status = 'publish';

-- Add database indexes
ALTER TABLE wp_posts ADD INDEX idx_post_status_type (post_status, post_type);
```

**Memory Issues**
```php
// Memory usage debugging
function aifm_debug_memory_usage() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('wp_footer', function() {
            echo '<!-- Memory Usage: ' . memory_get_peak_usage(true) / 1024 / 1024 . ' MB -->';
            echo '<!-- Memory Limit: ' . ini_get('memory_limit') . ' -->';
        });
    }
}
add_action('init', 'aifm_debug_memory_usage');
```

**Plugin Conflicts**
```bash
# Plugin performance testing script
#!/bin/bash
echo "Testing plugin performance impact..."

# Test with all plugins active
lighthouse --only-categories=performance --chrome-flags="--headless" https://aifirstmovers.net

# Test with plugins deactivated (implement via WP-CLI)
wp plugin deactivate --all
lighthouse --only-categories=performance --chrome-flags="--headless" https://aifirstmovers.net

# Reactivate plugins one by one and test
for plugin in $(wp plugin list --field=name --status=inactive); do
    wp plugin activate $plugin
    echo "Testing with $plugin active..."
    lighthouse --only-categories=performance --chrome-flags="--headless" https://aifirstmovers.net
done
```

## Conclusion

Performance optimization is an ongoing process that requires regular monitoring, testing, and refinement. The strategies outlined in this guide should be implemented progressively, with continuous measurement to ensure each optimization provides the expected benefits.

Regular performance audits and staying updated with the latest web performance best practices will help maintain optimal site speed and user experience.