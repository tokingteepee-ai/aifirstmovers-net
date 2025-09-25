# Accessibility Guidelines

This document outlines accessibility standards and guidelines to ensure AIFirstMovers.net is usable by people with disabilities and complies with WCAG 2.1 AA standards.

## Overview

Web accessibility ensures that websites are usable by everyone, including people with:
- Visual impairments (blindness, low vision, color blindness)
- Hearing impairments (deafness, hard of hearing)
- Motor impairments (limited fine motor control, paralysis)
- Cognitive impairments (dyslexia, ADHD, autism)

## WCAG 2.1 Principles

### 1. Perceivable
Information must be presentable in ways users can perceive.

#### Color and Contrast
- **Requirement**: Color contrast ratio of at least 4.5:1 for normal text, 3:1 for large text
- **Implementation**: 
  - Use high contrast color combinations
  - Don't rely solely on color to convey information
  - Test with color blindness simulators

```css
/* Good contrast examples */
.primary-text {
    color: #333333; /* Dark gray on white background */
    background: #ffffff;
}

.button-primary {
    color: #ffffff; /* White text */
    background: #007cba; /* Blue background - meets contrast requirements */
}
```

#### Images and Media
- **Alt Text**: All images must have descriptive alt text
- **Decorative Images**: Use empty alt attribute (`alt=""`) for decorative images
- **Complex Images**: Provide detailed descriptions for charts, graphs, infographics

```html
<!-- Informative image -->
<img src="ai-workflow.jpg" alt="AI workflow diagram showing data input, processing, and output stages">

<!-- Decorative image -->
<img src="decorative-pattern.jpg" alt="" role="presentation">

<!-- Complex image with description -->
<img src="sales-chart.jpg" alt="Q4 Sales Chart - see description below">
<p>Detailed description: Sales increased 25% from Q3 to Q4...</p>
```

#### Text Alternatives
- Provide captions for videos
- Transcripts for audio content
- Sign language interpretation for important announcements

### 2. Operable
Interface components must be operable by all users.

#### Keyboard Navigation
- **Tab Order**: Logical tab sequence through interactive elements
- **Focus Indicators**: Visible focus states for all interactive elements
- **Skip Links**: Allow users to skip to main content

```css
/* Visible focus indicators */
a:focus,
button:focus,
input:focus {
    outline: 2px solid #007cba;
    outline-offset: 2px;
}

/* Skip link */
.skip-link {
    position: absolute;
    left: -9999px;
    z-index: 999999;
    padding: 8px 16px;
    background: #000;
    color: #fff;
    text-decoration: none;
}

.skip-link:focus {
    left: 6px;
    top: 7px;
}
```

#### Time Limits
- No automatic timeouts without warning
- Provide controls to extend time limits
- Allow users to turn off moving content

#### Seizures and Physical Reactions
- Avoid content that flashes more than 3 times per second
- Provide controls for auto-playing media
- Use smooth animations, avoid jarring movements

### 3. Understandable
Information and UI operation must be understandable.

#### Readable Text
- **Language**: Specify page language and language changes
- **Reading Level**: Use clear, simple language when possible
- **Abbreviations**: Provide expansions for abbreviations

```html
<html lang="en">
<head>
    <title>AI First Movers - Home</title>
</head>
<body>
    <p>We use <abbr title="Artificial Intelligence">AI</abbr> to solve complex problems.</p>
    <p lang="es">Hola mundo</p> <!-- Language change indicator -->
</body>
</html>
```

#### Predictable Interface
- Consistent navigation across pages
- Predictable functionality
- Clear labeling of form controls

#### Input Assistance
- Clear form labels and instructions
- Error identification and suggestions
- Confirmation for important actions

```html
<!-- Form with proper labels and error handling -->
<form>
    <label for="email">Email Address (required)</label>
    <input type="email" id="email" name="email" required aria-describedby="email-error">
    <div id="email-error" class="error" role="alert" style="display: none;">
        Please enter a valid email address
    </div>
    
    <label for="message">Message</label>
    <textarea id="message" name="message" aria-describedby="message-help"></textarea>
    <div id="message-help" class="help-text">
        Please provide details about your inquiry
    </div>
</form>
```

### 4. Robust
Content must be robust enough for interpretation by assistive technologies.

#### Valid HTML
- Use semantic HTML elements
- Ensure code validates
- Proper use of ARIA when needed

```html
<!-- Semantic HTML structure -->
<header>
    <nav role="navigation" aria-label="Main navigation">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/resources">Resources</a></li>
        </ul>
    </nav>
</header>

<main>
    <article>
        <h1>Article Title</h1>
        <p>Article content...</p>
    </article>
</main>

<footer>
    <p>&copy; 2024 AI First Movers</p>
</footer>
```

## Accessibility Testing

### Automated Testing Tools

#### Browser Extensions
- **axe DevTools**: Comprehensive accessibility testing
- **WAVE**: Visual accessibility evaluation
- **Lighthouse**: Built into Chrome DevTools

#### Command Line Tools
```bash
# Install axe-core CLI
npm install -g @axe-core/cli

# Run accessibility audit
axe-core https://aifirstmovers.net

# Pa11y testing
npm install -g pa11y
pa11y https://aifirstmovers.net
```

### Manual Testing

#### Keyboard Testing
1. Navigate entire site using only keyboard
2. Ensure all interactive elements are reachable
3. Check tab order is logical
4. Verify focus indicators are visible
5. Test escape key functionality in modals

#### Screen Reader Testing
- **Windows**: NVDA (free), JAWS
- **macOS**: VoiceOver (built-in)
- **Mobile**: TalkBack (Android), VoiceOver (iOS)

#### Testing Checklist
- [ ] All images have alt text
- [ ] Forms have proper labels
- [ ] Headings are in logical order
- [ ] Links are descriptive
- [ ] Color contrast meets standards
- [ ] Site works without CSS
- [ ] Site works without JavaScript
- [ ] Videos have captions
- [ ] Error messages are clear

### User Testing
- Recruit users with disabilities
- Test with actual assistive technologies
- Gather feedback on usability
- Iterate based on user input

## Implementation Guidelines

### WordPress Accessibility Features

#### Theme Development
```php
// Add accessibility features in functions.php
function aifm_accessibility_setup() {
    // Add skip link
    add_action('wp_body_open', 'aifm_skip_link');
    
    // Enhance menu accessibility
    add_filter('nav_menu_link_attributes', 'aifm_menu_accessibility', 10, 3);
}

function aifm_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#main">Skip to main content</a>';
}

function aifm_menu_accessibility($atts, $item, $args) {
    // Add ARIA labels for menu items
    if (in_array('menu-item-has-children', $item->classes)) {
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
    }
    return $atts;
}
```

#### Content Guidelines
- Use heading hierarchy (H1 → H2 → H3)
- Write descriptive link text
- Provide alt text for images
- Use lists for grouped content
- Include form labels and instructions

### Plugin Accessibility
```php
// Ensure AIFM Core plugin is accessible
function aifm_core_accessibility() {
    // Add ARIA labels to custom post type archives
    add_filter('post_class', 'aifm_add_aria_labels');
    
    // Enhance search results
    add_filter('get_search_form', 'aifm_accessible_search_form');
}

function aifm_accessible_search_form($form) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
        <label for="search-field" class="screen-reader-text">Search for:</label>
        <input type="search" id="search-field" name="s" placeholder="Search..." required>
        <button type="submit">Search</button>
    </form>';
    return $form;
}
```

## Common Accessibility Issues

### Issues to Avoid

1. **Missing Alt Text**
   - Problem: Images without alt attributes
   - Solution: Add descriptive alt text or alt="" for decorative images

2. **Poor Color Contrast**
   - Problem: Text that's hard to read
   - Solution: Use color contrast analyzers, test with different color combinations

3. **Keyboard Traps**
   - Problem: Focus gets stuck in elements
   - Solution: Ensure escape routes from all interactive elements

4. **Missing Form Labels**
   - Problem: Form controls without associated labels
   - Solution: Use explicit labels or aria-label attributes

5. **Unclear Link Text**
   - Problem: Links with text like "click here" or "read more"
   - Solution: Use descriptive link text that makes sense out of context

### Quick Fixes

```css
/* Screen reader only text */
.screen-reader-text {
    position: absolute !important;
    clip: rect(1px, 1px, 1px, 1px);
    overflow: hidden;
    height: 1px;
    width: 1px;
}

/* Focus states for all interactive elements */
a:focus,
button:focus,
input:focus,
select:focus,
textarea:focus {
    outline: 2px solid #005fcc;
    outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .card {
        border: 2px solid;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```

## Resources and Tools

### Testing Tools
- [axe DevTools](https://www.deque.com/axe/devtools/)
- [WAVE Web Accessibility Evaluator](https://wave.webaim.org/)
- [Color Contrast Analyzers](https://www.tpgi.com/color-contrast-checker/)
- [Lighthouse Accessibility Audit](https://developers.google.com/web/tools/lighthouse/)

### Guidelines and Documentation
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WebAIM Resources](https://webaim.org/)
- [A11y Project](https://www.a11yproject.com/)
- [WordPress Accessibility Handbook](https://make.wordpress.org/accessibility/handbook/)

### Assistive Technologies
- [NVDA Screen Reader](https://www.nvaccess.org/)
- [JAWS Screen Reader](https://www.freedomscientific.com/products/software/jaws/)
- [Dragon Speech Recognition](https://www.nuance.com/dragon.html)

## Maintenance and Updates

### Regular Audits
- Monthly automated accessibility scans
- Quarterly manual accessibility testing
- Annual comprehensive accessibility audit
- User testing with people with disabilities

### Team Training
- Accessibility awareness training
- Tool usage training
- Regular updates on best practices
- Guest speakers from disability community

### Documentation Updates
- Keep accessibility guidelines current
- Update testing procedures
- Document new tools and techniques
- Share success stories and learnings