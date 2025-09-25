# AIFirstMovers.net Setup Guide

This guide will help you set up the AIFirstMovers.net WordPress project for development and production environments.

## Prerequisites

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.7 or higher (or MariaDB 10.3+)
- Node.js 16+ (for theme/plugin development)
- Composer (for PHP dependencies)

## Local Development Setup

### 1. WordPress Installation

1. Download and install WordPress locally using your preferred method:
   - XAMPP, WAMP, or MAMP
   - Local by Flywheel
   - Docker with wp-cli

2. Create a new database for the project

3. Configure `wp-config.php` with your database credentials

### 2. Theme Installation

#### Child Theme (theme-aifm-child)

1. Copy the `theme-aifm-child` folder to `wp-content/themes/`
2. Ensure the Twenty Twenty-Five theme is also installed
3. Activate the child theme from WordPress Admin → Appearance → Themes

#### Block Theme (theme-aifm-block)

1. Copy the `theme-aifm-block` folder to `wp-content/themes/`
2. This theme can be activated as an alternative to the child theme
3. It provides full site editing capabilities

### 3. Plugin Installation

1. Copy the `plugin-aifm-core` folder to `wp-content/plugins/`
2. Activate the plugin from WordPress Admin → Plugins
3. The plugin will automatically:
   - Create the "Resource" custom post type
   - Create the "Type" taxonomy
   - Add default taxonomy terms

### 4. Content Setup

After activation, you'll have access to:

- **Resources** (Custom Post Type): Create AI-related content
- **Resource Types** (Taxonomy): Categorize resources by type
  - Article
  - Tutorial
  - Case Study
  - White Paper
  - Video
  - Tool
  - Research

### 5. Development Environment

For theme/plugin development with asset compilation:

```bash
# Navigate to theme directory
cd wp-content/themes/theme-aifm-child
# or
cd wp-content/themes/theme-aifm-block

# Install dependencies (if package.json exists)
npm install

# Start development mode
npm run dev

# Build for production
npm run build
```

## Production Deployment

### 1. Server Requirements

- WordPress hosting with PHP 8.0+
- SSL certificate
- Backup solution
- Security measures (firewall, malware scanning)

### 2. File Transfer

1. Upload theme folders to `wp-content/themes/`
2. Upload plugin folder to `wp-content/plugins/`
3. Set appropriate file permissions (644 for files, 755 for directories)

### 3. WordPress Configuration

1. Install and activate themes/plugins through WordPress admin
2. Configure permalink structure (recommended: Post name)
3. Set up caching (recommended: W3 Total Cache or WP Rocket)
4. Configure CDN if needed

### 4. Security Hardening

1. Update WordPress core, themes, and plugins regularly
2. Use strong passwords and two-factor authentication
3. Install security plugins (Wordfence, Sucuri)
4. Hide wp-admin from unauthorized access
5. Regular backups

## Configuration Options

### Theme Customization

Both themes support WordPress Customizer:

1. Go to Appearance → Customize
2. Configure:
   - Site Identity (logo, title, tagline)
   - Colors and Typography
   - Menus
   - Widgets
   - Additional CSS

### Plugin Settings

The AIFM Core plugin provides:

- Custom post type management
- Taxonomy organization
- Default content structures

## Troubleshooting

### Common Issues

1. **Theme not displaying correctly**
   - Ensure parent theme (Twenty Twenty-Five) is installed
   - Check file permissions
   - Clear cache

2. **Plugin features not working**
   - Verify plugin is activated
   - Check PHP version compatibility
   - Review error logs

3. **Performance issues**
   - Enable caching
   - Optimize images
   - Minimize plugins
   - Use CDN

### Debug Mode

Enable WordPress debug mode in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check `/wp-content/debug.log` for error messages.

## Maintenance

### Regular Tasks

1. **Weekly**
   - Update WordPress core
   - Update themes and plugins
   - Review security scans
   - Check backup integrity

2. **Monthly**
   - Optimize database
   - Review performance metrics
   - Update content
   - Test forms and functionality

3. **Quarterly**
   - Security audit
   - Performance optimization
   - Backup strategy review
   - User access review

## Support

For technical support:

1. Check WordPress documentation
2. Review theme/plugin documentation
3. Contact development team
4. WordPress support forums

## Version History

- **1.0.0** - Initial setup with child theme, block theme, and core plugin