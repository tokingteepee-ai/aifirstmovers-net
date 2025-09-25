# AI First Movers Website Setup Guide

This guide will walk you through setting up the AI First Movers website with WordPress.

## Prerequisites

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.7 or higher / MariaDB 10.3 or higher
- SSL certificate (recommended)

## Installation Steps

### 1. WordPress Installation

1. Install WordPress on your web server
2. Complete the standard WordPress setup process
3. Create an admin user account

### 2. Theme Installation

#### Option A: Child Theme (Recommended for customization)

1. Install the Twenty Twenty-Five theme (parent theme):
   - Go to **Appearance > Themes**
   - Click **Add New**
   - Search for "Twenty Twenty-Five"
   - Install and activate

2. Install the AI First Movers Child Theme:
   - Upload the `/theme-aifm-child` folder to `/wp-content/themes/`
   - Go to **Appearance > Themes**
   - Activate "AI First Movers Child Theme"

#### Option B: Block Theme (For full control)

1. Upload the `/theme-aifm-block` folder to `/wp-content/themes/`
2. Go to **Appearance > Themes**
3. Activate "AI First Movers Block Theme"

### 3. Plugin Installation

1. Upload the `/plugin-aifm-core` folder to `/wp-content/plugins/`
2. Go to **Plugins > Installed Plugins**
3. Activate "AI First Movers Core"

### 4. Initial Configuration

#### Theme Customization

1. Go to **Appearance > Customize**
2. Configure:
   - **Site Identity**: Upload logo, set site title and tagline
   - **Colors**: Adjust color scheme if needed
   - **Typography**: Select fonts and sizes
   - **Header & Footer**: Configure navigation menus

#### Core Plugin Setup

1. **Resource Types**: Go to **Resources > Types**
   - Create resource types (e.g., "White Paper", "Case Study", "Guide")
   - Add colors and icons for each type

2. **Resource Topics**: Go to **Resources > Topics**
   - Create topic hierarchy (e.g., "AI Strategy > Implementation")

3. **Sample Resources**: Create a few sample resources to test functionality

#### Menu Setup

1. Go to **Appearance > Menus**
2. Create a primary navigation menu with key pages:
   - Home
   - About
   - Resources
   - Services
   - Contact

3. Create a footer menu with additional links:
   - Privacy Policy
   - Terms of Service
   - Support

### 5. Essential Pages

Create these essential pages:

#### Home Page
- Use block patterns or customize with the child theme
- Include hero section, featured resources, and call-to-actions

#### Resources Archive
- Will be automatically created by the plugin
- Accessible at `/resources/`

#### About Page
- Company information and team details
- Mission and vision statements

#### Contact Page
- Contact form (consider Contact Form 7 plugin)
- Company contact information

### 6. Recommended Plugins

Install these additional plugins for enhanced functionality:

#### SEO
- **Yoast SEO** or **RankMath**
- Configure meta titles, descriptions, and sitemaps

#### Security
- **Wordfence Security**
- **Updraft Plus** (for backups)

#### Performance
- **W3 Total Cache** or **WP Rocket**
- **Smush** (image optimization)

#### Forms
- **Contact Form 7** or **Gravity Forms**

### 7. Content Migration

If migrating from an existing site:

1. **Export/Import**: Use WordPress import/export tools
2. **Media Library**: Upload all images and documents
3. **Resources**: Convert existing content to Resource post type
4. **Categories**: Map to Resource Types and Topics

### 8. Testing

Before going live:

1. **Functionality Testing**:
   - Create and edit resources
   - Test filtering and search
   - Verify responsive design

2. **Performance Testing**:
   - Use GTmetrix or PageSpeed Insights
   - Optimize images and scripts

3. **SEO Testing**:
   - Verify meta tags
   - Test structured data
   - Check sitemap generation

### 9. Going Live

1. **DNS Configuration**: Point domain to web server
2. **SSL Certificate**: Install and configure SSL
3. **Search Engine Submission**: Submit sitemap to Google Search Console
4. **Analytics**: Install Google Analytics and Google Tag Manager

## Configuration Files

### wp-config.php Additions

Add these constants to your wp-config.php file:

```php
// Enable WordPress debugging (remove in production)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Increase memory limit
define('WP_MEMORY_LIMIT', '256M');

// File permissions
define('FS_METHOD', 'direct');

// Automatic updates
define('WP_AUTO_UPDATE_CORE', 'minor');

// Security keys (generate at https://api.wordpress.org/secret-key/1.1/salt/)
// [Insert your unique security keys here]
```

### .htaccess Recommendations

For Apache servers, add to your .htaccess file:

```apache
# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compression
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

# Caching
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
</IfModule>
```

## Troubleshooting

### Common Issues

#### Theme Not Appearing
- Check file permissions (755 for directories, 644 for files)
- Verify theme structure and required files
- Check WordPress error logs

#### Plugin Activation Errors
- Increase PHP memory limit
- Check for plugin conflicts
- Review PHP error logs

#### Resource Pages Not Working
- Go to **Settings > Permalinks** and click "Save Changes"
- Check .htaccess file permissions

#### Styling Issues
- Clear any caching plugins
- Check for theme conflicts
- Verify CSS file enqueueing

### Support Resources

- **WordPress Codex**: https://codex.wordpress.org/
- **WordPress Support Forums**: https://wordpress.org/support/
- **AI First Movers Documentation**: [Internal documentation link]

## Next Steps

After setup is complete:

1. Review the [HubSpot Integration Guide](HUBSPOT.md)
2. Complete the [QA Checklist](QA_CHECKLIST.md)
3. Review accessibility and performance guides in the `/qa` folder

## Maintenance

### Regular Tasks

- **Weekly**: Update plugins and themes
- **Monthly**: Review site performance and security
- **Quarterly**: Full site backup and disaster recovery test

### Monitoring

Set up monitoring for:
- Site uptime
- Page load times
- Security threats
- SEO rankings
- User analytics