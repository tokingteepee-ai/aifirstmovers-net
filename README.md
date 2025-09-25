# AI First Movers Website

Website redesign project for AI First Movers Advantage - A comprehensive WordPress solution with custom themes, plugins, and optimized performance.

## 🚀 Project Overview

This repository contains a complete WordPress website structure for AI First Movers, featuring:

- **Two theme options**: Child theme and custom block theme
- **Custom WordPress plugin** with Resource CPT and taxonomy management
- **Comprehensive documentation** for setup, HubSpot integration, and QA
- **Performance and accessibility optimizations**
- **SEO-ready structure** with schema markup and optimization

## 📁 Project Structure

```
aifirstmovers-net/
├── theme-aifm-child/          # Twenty Twenty-Five child theme
│   ├── style.css              # Main stylesheet with theme header
│   ├── functions.php          # Theme functionality and hooks
│   └── assets/                # CSS, JS, and image assets
│       ├── css/
│       ├── js/
│       └── images/
├── theme-aifm-block/          # Custom block theme
│   ├── style.css              # Theme stylesheet
│   ├── theme.json             # Block theme configuration
│   ├── functions.php          # Theme setup and features
│   ├── templates/             # Block templates
│   │   └── index.html         # Main template
│   └── parts/                 # Template parts
│       ├── header.html        # Site header
│       └── footer.html        # Site footer
├── plugin-aifm-core/          # Core functionality plugin
│   ├── plugin-aifm-core.php   # Main plugin file
│   ├── includes/              # Plugin classes
│   │   ├── class-post-types.php    # Resource CPT
│   │   ├── class-taxonomies.php    # Type taxonomy
│   │   ├── class-meta-boxes.php    # Custom meta boxes
│   │   └── class-shortcodes.php    # Display shortcodes
│   └── assets/                # Plugin assets
│       ├── css/
│       └── js/
├── docs/                      # Documentation
│   ├── SETUP.md              # Installation guide
│   ├── HUBSPOT.md            # HubSpot integration
│   └── QA_CHECKLIST.md       # Quality assurance checklist
├── qa/                        # Quality assurance guides
│   ├── accessibility.md      # WCAG 2.1 AA compliance guide
│   ├── seo.md                # SEO optimization guide
│   └── perf.md               # Performance optimization guide
├── .gitignore                # WordPress-optimized gitignore
└── README.md                 # This file
```

## ⚡ Quick Start

### Prerequisites

- WordPress 6.0+
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/tokingteepee-ai/aifirstmovers-net.git
   cd aifirstmovers-net
   ```

2. **Choose your theme approach**:

   **Option A: Child Theme (Recommended for customization)**
   ```bash
   # Copy child theme to WordPress
   cp -r theme-aifm-child /path/to/wordpress/wp-content/themes/
   
   # Install parent theme (Twenty Twenty-Five) via WordPress admin
   # Then activate "AI First Movers Child Theme"
   ```

   **Option B: Block Theme (For full control)**
   ```bash
   # Copy block theme to WordPress
   cp -r theme-aifm-block /path/to/wordpress/wp-content/themes/
   
   # Activate "AI First Movers Block Theme" in WordPress admin
   ```

3. **Install the core plugin**:
   ```bash
   # Copy plugin to WordPress
   cp -r plugin-aifm-core /path/to/wordpress/wp-content/plugins/
   
   # Activate "AI First Movers Core" in WordPress admin
   ```

4. **Configure the site**:
   - Follow the detailed [Setup Guide](docs/SETUP.md)
   - Configure resource types and topics
   - Set up navigation menus
   - Create essential pages

## 🎯 Key Features

### Theme Features
- **Responsive Design**: Mobile-first approach with optimized layouts
- **Accessibility Ready**: WCAG 2.1 AA compliant with proper ARIA labels
- **SEO Optimized**: Schema markup, semantic HTML structure
- **Performance Focused**: Optimized assets, lazy loading, caching ready
- **Customizable**: Extensive customization options and hooks

### Core Plugin Features
- **Resource Custom Post Type**: Manage white papers, guides, case studies
- **Taxonomy Management**: Organize resources by type and topic
- **Advanced Meta Fields**: SEO settings, analytics, file attachments
- **Shortcode Support**: Display resources anywhere with flexible options
- **Analytics Integration**: Track views and downloads

### Built-in Shortcodes
```php
// Display resource grid
[aifm_resources limit="6" type="whitepaper" columns="3"]

// Show resource types
[aifm_resource_types columns="4" show_count="true"]

// Featured resources carousel
[aifm_featured_resources limit="3" style="carousel"]
```

## 📚 Documentation

- **[Setup Guide](docs/SETUP.md)**: Complete installation and configuration
- **[HubSpot Integration](docs/HUBSPOT.md)**: Marketing automation setup
- **[QA Checklist](docs/QA_CHECKLIST.md)**: Pre-launch quality assurance
- **[Accessibility Guide](qa/accessibility.md)**: WCAG 2.1 compliance
- **[SEO Guide](qa/seo.md)**: Search engine optimization
- **[Performance Guide](qa/perf.md)**: Speed and optimization

## 🛠️ Development

### Local Development Setup

1. **Set up WordPress locally** (using Local, XAMPP, or Docker)

2. **Install development tools**:
   ```bash
   # For theme/plugin development
   npm install -g @wordpress/env
   npm install -g @wordpress/scripts
   
   # For asset compilation (if customizing)
   npm install
   ```

3. **Enable debugging** in wp-config.php:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   ```

### Code Standards
- Follow WordPress Coding Standards
- Use proper sanitization and validation
- Implement proper error handling
- Document all functions and classes

## 🔧 Customization

### Theme Customization

**Child Theme Approach**:
- Modify `style.css` for visual changes
- Add functions to `functions.php` for new features
- Override parent theme templates as needed

**Block Theme Approach**:
- Edit `theme.json` for global styles and settings
- Modify template files in `/templates` and `/parts`
- Add custom block patterns and styles

### Plugin Extensions

Add new functionality by extending the core plugin:

```php
// Add custom resource fields
add_action('aifm_resource_meta_fields', 'add_custom_resource_fields');
function add_custom_resource_fields($post_id) {
    // Add your custom fields here
}

// Extend resource shortcode
add_filter('aifm_resources_shortcode_args', 'extend_resources_shortcode');
function extend_resources_shortcode($args) {
    // Modify shortcode functionality
    return $args;
}
```

## 🚦 Testing

### Automated Testing
```bash
# Run accessibility tests
npm run test:a11y

# Performance testing
npm run test:performance

# WordPress coding standards
phpcs --standard=WordPress .
```

### Manual Testing Checklist
- [ ] Test all forms and functionality
- [ ] Verify responsive design on all devices
- [ ] Check accessibility with screen readers
- [ ] Validate SEO elements
- [ ] Test performance on various connections

## 📊 Performance Targets

- **Page Load Time**: < 3 seconds
- **Lighthouse Score**: > 90
- **Core Web Vitals**: All green
- **Accessibility**: WCAG 2.1 AA compliant
- **SEO**: Schema markup implemented

## 🔒 Security Features

- Input sanitization and validation
- Nonce verification for forms
- Capability checks for admin functions
- Secure file upload handling
- SQL injection prevention

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📋 Deployment Checklist

Before going live:
- [ ] Complete the [QA Checklist](docs/QA_CHECKLIST.md)
- [ ] Set up SSL certificate
- [ ] Configure caching and CDN
- [ ] Set up monitoring and backups
- [ ] Test all functionality thoroughly
- [ ] Configure analytics and tracking

## 🐛 Troubleshooting

### Common Issues

**Theme not displaying correctly**:
- Check file permissions (755 for directories, 644 for files)
- Verify all required files are present
- Clear any caching

**Plugin activation errors**:
- Check PHP version compatibility
- Increase memory limit in wp-config.php
- Review error logs

**Performance issues**:
- Refer to the [Performance Guide](qa/perf.md)
- Check for plugin conflicts
- Optimize images and assets

## 📞 Support

For issues and questions:
- Check the documentation in `/docs` and `/qa` folders
- Review WordPress Codex for WordPress-specific issues
- Contact the development team for custom functionality

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 🏗️ Built With

- **WordPress** - Content Management System
- **PHP** - Server-side scripting
- **JavaScript** - Interactive functionality
- **CSS/SCSS** - Styling and layout
- **WordPress Block Editor** - Content creation
- **Schema.org** - Structured data

---

**AI First Movers** - Leading the way in artificial intelligence strategy and implementation.
