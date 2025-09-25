# AIFirstMovers.net - WordPress Project

A comprehensive WordPress project for AI First Movers Advantage featuring custom themes, plugins, and documentation for a professional AI consulting website.

## Project Structure

```
aifirstmovers-net/
├── theme-aifm-child/          # Child theme based on Twenty Twenty-Five
│   ├── style.css              # Child theme styles
│   ├── functions.php          # Theme functionality
│   └── assets/                # Theme assets (CSS, JS, images)
│       ├── css/
│       ├── js/
│       └── images/
├── theme-aifm-block/          # Custom block theme with FSE support
│   ├── theme.json             # Theme configuration
│   ├── style.css              # Theme styles
│   ├── functions.php          # Theme functionality
│   ├── templates/             # Block templates
│   │   ├── header.html
│   │   └── footer.html
│   ├── parts/                 # Template parts
│   └── assets/                # Theme assets
├── plugin-aifm-core/          # Core functionality plugin
│   ├── plugin-aifm-core.php   # Main plugin file
│   ├── includes/              # Plugin includes
│   ├── assets/                # Plugin assets
│   └── languages/             # Translation files
├── docs/                      # Project documentation
│   ├── SETUP.md               # Setup instructions
│   ├── HUBSPOT.md             # HubSpot integration guide
│   └── QA_CHECKLIST.md        # Quality assurance checklist
├── qa/                        # Quality assurance guidelines
│   ├── accessibility.md       # Accessibility guidelines
│   ├── seo.md                 # SEO best practices
│   └── perf.md                # Performance optimization
├── .gitignore                 # Git ignore rules for WordPress
└── README.md                  # This file
```

## Features

### Child Theme (theme-aifm-child)
- **Parent Theme**: Twenty Twenty-Five
- **Custom Styling**: AI-focused color scheme and typography
- **Responsive Design**: Mobile-first approach
- **Custom Functions**: Enhanced functionality and optimizations
- **Asset Management**: Organized CSS, JavaScript, and image assets

### Block Theme (theme-aifm-block)
- **Full Site Editing**: Complete FSE support with theme.json
- **Custom Color Palette**: Brand-specific colors for AI First Movers
- **Typography System**: Fluid typography with system fonts
- **Block Patterns**: Pre-designed patterns for common layouts
- **Template Parts**: Modular header and footer templates
- **Custom Block Styles**: Enhanced styling for core blocks

### Core Plugin (plugin-aifm-core)
- **Custom Post Type**: "Resource" for AI-related content
- **Custom Taxonomy**: "Type" for categorizing resources
- **Default Terms**: Pre-populated taxonomy terms (Article, Tutorial, Case Study, etc.)
- **Admin Interface**: User-friendly administration experience
- **Asset Management**: Conditional loading of plugin assets

### Documentation
- **Setup Guide**: Comprehensive installation and configuration instructions
- **HubSpot Integration**: CRM integration for lead management and analytics
- **QA Checklist**: Quality assurance procedures and testing protocols
- **Accessibility Guidelines**: WCAG 2.1 compliance standards and implementation
- **SEO Best Practices**: Search optimization strategies and technical SEO
- **Performance Guidelines**: Speed optimization and monitoring procedures

## Quick Start

### Prerequisites
- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.7 or higher

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/tokingteepee-ai/aifirstmovers-net.git
   cd aifirstmovers-net
   ```

2. **Install themes**
   ```bash
   # Copy child theme
   cp -r theme-aifm-child /path/to/wordpress/wp-content/themes/
   
   # Copy block theme
   cp -r theme-aifm-block /path/to/wordpress/wp-content/themes/
   ```

3. **Install plugin**
   ```bash
   cp -r plugin-aifm-core /path/to/wordpress/wp-content/plugins/
   ```

4. **Activate in WordPress**
   - Log into WordPress admin
   - Go to Appearance → Themes and activate desired theme
   - Go to Plugins and activate "AIFirstMovers Core"

### Theme Selection

**Choose between two theme options:**

**Option 1: Child Theme (Recommended for traditional editing)**
- Based on Twenty Twenty-Five
- Compatible with WordPress Customizer
- Traditional theme structure
- Easier for non-technical users

**Option 2: Block Theme (Recommended for full site editing)**
- Modern FSE approach
- Complete design control
- Block-based editing
- Future-proof architecture

## Development

### Local Development Setup

1. **Install WordPress locally** using your preferred method:
   - Local by Flywheel
   - XAMPP/WAMP/MAMP
   - Docker with wp-cli

2. **Configure development environment**
   ```php
   // wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   define('SCRIPT_DEBUG', true);
   ```

3. **Asset development** (if customizing themes)
   ```bash
   cd theme-aifm-child
   # Install dependencies (if package.json exists)
   npm install
   
   # Development mode
   npm run dev
   
   # Production build
   npm run build
   ```

### Customization Guidelines

- **Child Theme**: Modify `style.css` and `functions.php` for customizations
- **Block Theme**: Edit `theme.json` for global styles, add custom templates in `/templates`
- **Plugin**: Add new features to the core plugin or create additional plugins

## Core Features

### Custom Post Type: Resources
- **Purpose**: Manage AI-related content (articles, tutorials, case studies)
- **Fields**: Title, content, featured image, excerpt, custom fields
- **Taxonomies**: Resource Type (Article, Tutorial, Case Study, White Paper, Video, Tool, Research)
- **Archive**: Dedicated archive page with filtering and pagination

### Resource Types Taxonomy
- **Articles**: In-depth articles about AI topics
- **Tutorials**: Step-by-step guides and tutorials
- **Case Studies**: Real-world AI implementation examples
- **White Papers**: Technical documentation and research
- **Videos**: Educational videos and presentations
- **Tools**: AI tools and software recommendations
- **Research**: Latest AI research and findings

## Integration Options

### HubSpot CRM
- Lead capture forms
- Contact lifecycle management
- Email marketing automation
- Analytics tracking
- See `docs/HUBSPOT.md` for complete integration guide

### Analytics
- Google Analytics 4 integration
- Core Web Vitals monitoring
- Custom event tracking
- Performance metrics

### SEO
- Yoast SEO or RankMath compatibility
- Schema markup for rich snippets
- Open Graph and Twitter Cards
- XML sitemaps
- See `qa/seo.md` for optimization strategies

## Quality Assurance

### Testing Procedures
- **Functionality**: Core WordPress and plugin features
- **Compatibility**: Browser and device testing
- **Performance**: Page speed and Core Web Vitals
- **Accessibility**: WCAG 2.1 AA compliance
- **SEO**: Technical and content optimization

### Guidelines Documentation
- **Accessibility**: `qa/accessibility.md` - WCAG compliance and testing
- **SEO**: `qa/seo.md` - Search optimization strategies
- **Performance**: `qa/perf.md` - Speed optimization techniques
- **QA Checklist**: `docs/QA_CHECKLIST.md` - Complete testing procedures

## Deployment

### Production Checklist
- [ ] WordPress core, themes, and plugins updated
- [ ] SSL certificate installed and configured
- [ ] Caching solution implemented
- [ ] CDN configured (if applicable)
- [ ] Security measures in place
- [ ] Analytics and monitoring tools set up
- [ ] Backup solution configured
- [ ] Performance optimization completed

### Security Recommendations
- Use strong passwords and two-factor authentication
- Install security plugins (Wordfence, Sucuri)
- Regular security scans and updates
- Limit login attempts
- Hide wp-admin from unauthorized access

## Support and Maintenance

### Regular Maintenance Tasks
- **Weekly**: Update WordPress core, themes, plugins
- **Monthly**: Database optimization, security scans
- **Quarterly**: Performance audit, backup testing
- **Annually**: Comprehensive security audit

### Troubleshooting
1. **Check error logs**: `/wp-content/debug.log`
2. **Disable plugins**: Test for plugin conflicts
3. **Switch themes**: Test with default theme
4. **Check server resources**: CPU, memory, disk space
5. **Review recent changes**: Identify potential causes

## Contributing

### Development Guidelines
- Follow WordPress coding standards
- Test thoroughly before committing
- Document new features and changes
- Maintain backward compatibility
- Consider accessibility and performance impact

### Pull Request Process
1. Create feature branch from main
2. Make changes and test locally
3. Update documentation if needed
4. Submit pull request with clear description
5. Address feedback and make necessary changes

## License

This project is proprietary software developed for AI First Movers Advantage. All rights reserved.

## Support

For technical support or questions:
- Review documentation in `/docs` directory
- Check issue tracker for known problems
- Contact development team for assistance

---

**Version**: 1.0.0  
**Last Updated**: January 2024  
**WordPress**: 6.0+  
**PHP**: 8.0+
