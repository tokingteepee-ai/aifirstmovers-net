# Accessibility Guidelines

This document outlines accessibility standards and best practices for the AI First Movers website to ensure compliance with WCAG 2.1 AA standards and provide an inclusive user experience.

## Overview

Web accessibility ensures that people with disabilities can perceive, understand, navigate, and interact with the web. This includes users with:

- Visual impairments (blindness, low vision, color blindness)
- Hearing impairments (deafness, hard of hearing)
- Motor disabilities (limited fine motor control, paralysis)
- Cognitive disabilities (learning disabilities, memory issues)

## WCAG 2.1 AA Compliance

### The Four Principles

#### 1. Perceivable
Information and UI components must be presentable in ways users can perceive.

**Color and Contrast**
- Text contrast ratio: minimum 4.5:1 for normal text, 3:1 for large text
- Non-text contrast: minimum 3:1 for UI components and graphics
- Don't rely solely on color to convey information

**Images and Media**
- All images must have descriptive alt text
- Decorative images should have empty alt attributes (`alt=""`)
- Complex images (charts, graphs) need detailed descriptions
- Videos should have captions and transcripts

**Text and Typography**
- Text must be resizable up to 200% without loss of functionality
- Use relative units (em, rem, %) instead of fixed pixels
- Maintain readable font sizes (minimum 16px base)

#### 2. Operable
UI components and navigation must be operable by all users.

**Keyboard Navigation**
- All functionality must be available via keyboard
- Logical tab order throughout the site
- Visible focus indicators on all interactive elements
- No keyboard traps

**Timing and Motion**
- Users can control or disable auto-playing content
- Provide adequate time limits or ability to extend them
- Avoid content that flashes more than 3 times per second

#### 3. Understandable
Information and UI operation must be understandable.

**Language and Readability**
- Declare page language (`<html lang="en">`)
- Use clear, simple language
- Define unusual words and abbreviations
- Consistent navigation and identification

**Predictable Interface**
- Navigation order is consistent across pages
- Form elements have clear labels and instructions
- Error messages are clear and constructive

#### 4. Robust
Content must be robust enough for various assistive technologies.

**Code Quality**
- Valid HTML markup
- Proper semantic structure
- ARIA attributes used correctly
- Compatible with screen readers

## Implementation Guidelines

### HTML Structure

#### Semantic HTML
```html
<!-- Use semantic elements -->
<header>
  <nav aria-label="Main navigation">
    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="/about">About</a></li>
      <li><a href="/resources">Resources</a></li>
    </ul>
  </nav>
</header>

<main>
  <article>
    <h1>Page Title</h1>
    <section>
      <h2>Section Heading</h2>
      <p>Content...</p>
    </section>
  </article>
</main>

<aside>
  <h2>Related Links</h2>
  <!-- sidebar content -->
</aside>

<footer>
  <!-- footer content -->
</footer>
```

#### Heading Structure
```html
<!-- Proper heading hierarchy -->
<h1>Main Page Title</h1>
  <h2>Major Section</h2>
    <h3>Subsection</h3>
    <h3>Another Subsection</h3>
      <h4>Sub-subsection</h4>
  <h2>Another Major Section</h2>
```

### Forms and Inputs

#### Accessible Form Structure
```html
<form>
  <fieldset>
    <legend>Contact Information</legend>
    
    <div class="form-group">
      <label for="name">
        Full Name <span aria-label="required">*</span>
      </label>
      <input 
        type="text" 
        id="name" 
        name="name" 
        required 
        aria-describedby="name-error"
        aria-invalid="false"
      >
      <div id="name-error" class="error-message" aria-live="polite"></div>
    </div>
    
    <div class="form-group">
      <label for="email">
        Email Address <span aria-label="required">*</span>
      </label>
      <input 
        type="email" 
        id="email" 
        name="email" 
        required 
        aria-describedby="email-help email-error"
      >
      <div id="email-help" class="help-text">
        We'll never share your email address
      </div>
      <div id="email-error" class="error-message" aria-live="polite"></div>
    </div>
    
    <button type="submit" class="submit-button">
      Send Message
    </button>
  </fieldset>
</form>
```

#### Error Handling
```javascript
// Accessible error handling
function showError(inputId, message) {
  const input = document.getElementById(inputId);
  const errorDiv = document.getElementById(inputId + '-error');
  
  input.setAttribute('aria-invalid', 'true');
  errorDiv.textContent = message;
  errorDiv.setAttribute('role', 'alert');
  
  // Focus the first error
  if (document.querySelectorAll('[aria-invalid="true"]').length === 1) {
    input.focus();
  }
}
```

### Navigation

#### Skip Links
```html
<a href="#main-content" class="skip-link">Skip to main content</a>
<a href="#navigation" class="skip-link">Skip to navigation</a>

<style>
.skip-link {
  position: absolute;
  top: -40px;
  left: 6px;
  background: #000;
  color: #fff;
  padding: 8px;
  text-decoration: none;
  z-index: 9999;
}

.skip-link:focus {
  top: 6px;
}
</style>
```

#### Accessible Menus
```html
<nav aria-label="Main navigation">
  <ul class="main-menu">
    <li>
      <a href="/resources" aria-expanded="false" aria-haspopup="true">
        Resources
      </a>
      <ul class="submenu">
        <li><a href="/resources/whitepapers">White Papers</a></li>
        <li><a href="/resources/case-studies">Case Studies</a></li>
        <li><a href="/resources/guides">Guides</a></li>
      </ul>
    </li>
  </ul>
</nav>
```

### Images and Media

#### Image Accessibility
```html
<!-- Informative image -->
<img 
  src="ai-strategy-chart.jpg" 
  alt="Bar chart showing AI adoption rates across industries: Healthcare 78%, Finance 65%, Manufacturing 52%"
>

<!-- Decorative image -->
<img src="decorative-pattern.jpg" alt="" role="presentation">

<!-- Complex image with long description -->
<img 
  src="complex-diagram.jpg" 
  alt="AI implementation process diagram"
  aria-describedby="diagram-description"
>
<div id="diagram-description">
  <h3>Detailed Description</h3>
  <p>This diagram shows the five-step AI implementation process...</p>
</div>
```

#### Video Accessibility
```html
<video controls>
  <source src="ai-intro.mp4" type="video/mp4">
  <track kind="captions" src="captions.vtt" srclang="en" label="English">
  <track kind="descriptions" src="descriptions.vtt" srclang="en" label="Audio descriptions">
  <p>Your browser doesn't support HTML video. <a href="ai-intro.mp4">Download the video</a>.</p>
</video>
```

### ARIA Labels and Roles

#### Common ARIA Patterns
```html
<!-- Expandable content -->
<button 
  aria-expanded="false" 
  aria-controls="content-panel"
  id="toggle-button"
>
  Show More Information
</button>
<div id="content-panel" aria-labelledby="toggle-button" hidden>
  <!-- expandable content -->
</div>

<!-- Search with live results -->
<div role="search">
  <label for="search-input">Search Resources</label>
  <input 
    type="search" 
    id="search-input"
    aria-describedby="search-results-status"
    aria-expanded="false"
    aria-autocomplete="list"
  >
  <div id="search-results-status" aria-live="polite" aria-atomic="true"></div>
  <ul role="listbox" aria-label="Search suggestions">
    <!-- search results -->
  </ul>
</div>

<!-- Modal dialog -->
<div 
  role="dialog" 
  aria-labelledby="modal-title"
  aria-describedby="modal-description"
  aria-modal="true"
>
  <h2 id="modal-title">Confirm Action</h2>
  <p id="modal-description">Are you sure you want to delete this resource?</p>
  <button type="button">Cancel</button>
  <button type="button">Delete</button>
</div>
```

## CSS and Styling

### Focus Management
```css
/* Visible focus indicators */
button:focus,
a:focus,
input:focus,
textarea:focus,
select:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}

/* High contrast focus for better visibility */
@media (prefers-contrast: high) {
  button:focus,
  a:focus,
  input:focus,
  textarea:focus,
  select:focus {
    outline: 3px solid #000;
    outline-offset: 3px;
  }
}
```

### Responsive Text
```css
/* Ensure text scales properly */
html {
  font-size: 16px; /* Base font size */
}

body {
  font-size: 1rem;
  line-height: 1.5;
}

/* Support user zoom up to 200% */
@media (max-width: 1280px) {
  html {
    font-size: 14px;
  }
}

@media (max-width: 960px) {
  html {
    font-size: 12px;
  }
}
```

### Motion and Animation
```css
/* Respect reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

/* Safe animations */
.fade-in {
  opacity: 0;
  transition: opacity 0.3s ease;
}

.fade-in.visible {
  opacity: 1;
}

@media (prefers-reduced-motion: reduce) {
  .fade-in {
    opacity: 1;
    transition: none;
  }
}
```

## JavaScript and Interactions

### Keyboard Event Handling
```javascript
// Accessible dropdown menu
class AccessibleDropdown {
  constructor(element) {
    this.dropdown = element;
    this.button = element.querySelector('[aria-haspopup]');
    this.menu = element.querySelector('[role="menu"]');
    this.menuItems = this.menu.querySelectorAll('[role="menuitem"]');
    
    this.init();
  }
  
  init() {
    this.button.addEventListener('click', this.toggle.bind(this));
    this.button.addEventListener('keydown', this.handleButtonKeydown.bind(this));
    
    this.menuItems.forEach((item, index) => {
      item.addEventListener('keydown', (e) => this.handleMenuKeydown(e, index));
    });
  }
  
  toggle() {
    const isOpen = this.button.getAttribute('aria-expanded') === 'true';
    this.button.setAttribute('aria-expanded', !isOpen);
    this.menu.hidden = isOpen;
    
    if (!isOpen && this.menuItems.length > 0) {
      this.menuItems[0].focus();
    }
  }
  
  handleButtonKeydown(e) {
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      this.open();
      this.menuItems[0].focus();
    }
  }
  
  handleMenuKeydown(e, index) {
    switch (e.key) {
      case 'ArrowUp':
        e.preventDefault();
        const prevIndex = index > 0 ? index - 1 : this.menuItems.length - 1;
        this.menuItems[prevIndex].focus();
        break;
        
      case 'ArrowDown':
        e.preventDefault();
        const nextIndex = index < this.menuItems.length - 1 ? index + 1 : 0;
        this.menuItems[nextIndex].focus();
        break;
        
      case 'Escape':
        this.close();
        this.button.focus();
        break;
    }
  }
}
```

### Screen Reader Announcements
```javascript
// Announce dynamic content changes
function announceToScreenReader(message, priority = 'polite') {
  const announcement = document.createElement('div');
  announcement.setAttribute('aria-live', priority);
  announcement.setAttribute('aria-atomic', 'true');
  announcement.className = 'sr-only';
  announcement.textContent = message;
  
  document.body.appendChild(announcement);
  
  // Remove after announcement
  setTimeout(() => {
    document.body.removeChild(announcement);
  }, 1000);
}

// Usage examples
announceToScreenReader('Search results updated', 'polite');
announceToScreenReader('Error: Please fix the highlighted fields', 'assertive');
```

## Testing and Validation

### Automated Testing Tools

#### axe-core Integration
```javascript
// Add axe testing to your test suite
import { toHaveNoViolations } from 'jest-axe';
import { axe } from 'jest-axe';

expect.extend(toHaveNoViolations);

test('should not have accessibility violations', async () => {
  const results = await axe(document.body);
  expect(results).toHaveNoViolations();
});
```

### Manual Testing Checklist

#### Keyboard Navigation
- [ ] Tab through all interactive elements
- [ ] Shift+Tab works in reverse order
- [ ] Enter/Space activate buttons and links
- [ ] Arrow keys work in menus and lists
- [ ] Escape closes modals and menus
- [ ] Focus is visible on all elements
- [ ] Focus doesn't get trapped

#### Screen Reader Testing
- [ ] Test with NVDA (free) or JAWS
- [ ] All content is announced
- [ ] Headings create proper structure
- [ ] Form labels are associated correctly
- [ ] ARIA labels provide context
- [ ] Dynamic content changes are announced

#### Color and Contrast
- [ ] Use WebAIM Contrast Checker
- [ ] Test with color blindness simulators
- [ ] Ensure information isn't color-dependent
- [ ] High contrast mode compatibility

## WordPress Specific Considerations

### Theme Development
```php
// Add accessibility features to functions.php
function aifm_accessibility_features() {
    // Add skip links
    add_action('wp_body_open', 'aifm_add_skip_links');
    
    // Ensure proper focus management
    wp_enqueue_script('aifm-a11y', get_template_directory_uri() . '/assets/js/accessibility.js', array('jquery'), '1.0.0', true);
}
add_action('after_setup_theme', 'aifm_accessibility_features');

function aifm_add_skip_links() {
    echo '<a class="skip-link screen-reader-text" href="#main">Skip to main content</a>';
    echo '<a class="skip-link screen-reader-text" href="#navigation">Skip to navigation</a>';
}
```

### Plugin Integration
```php
// Ensure custom post types are accessible
function aifm_accessible_resource_archive() {
    if (is_post_type_archive('resource')) {
        // Add proper heading structure
        add_filter('wp_title', function($title) {
            return 'Resources Archive - ' . get_bloginfo('name');
        });
        
        // Add aria-label to pagination
        add_filter('paginate_links', function($link) {
            return str_replace('<a ', '<a aria-label="Page navigation" ', $link);
        });
    }
}
add_action('wp', 'aifm_accessible_resource_archive');
```

## Resources and References

### Testing Tools
- **WAVE Web Accessibility Evaluator**: Free browser extension
- **axe DevTools**: Browser extension for developers
- **Lighthouse**: Built into Chrome DevTools
- **Color Oracle**: Color blindness simulator
- **NVDA Screen Reader**: Free screen reader for testing

### Guidelines and Standards
- **WCAG 2.1 Guidelines**: Official W3C accessibility guidelines
- **WebAIM**: Web accessibility tutorials and resources
- **A11y Project**: Community-driven accessibility checklist
- **MDN Accessibility**: Mozilla's accessibility documentation

### WordPress Resources
- **WordPress Accessibility Handbook**: Official WordPress accessibility guide
- **WP Accessibility Plugin**: Adds accessibility features to themes
- **WordPress.org Accessibility Team**: Active community for accessibility

## Maintenance and Updates

### Regular Audits
- **Monthly**: Run automated accessibility scans
- **Quarterly**: Conduct manual testing sessions
- **Annually**: Comprehensive accessibility audit by experts

### Team Training
- Provide accessibility training for all team members
- Include accessibility in code review processes
- Maintain accessibility documentation and guidelines
- Stay updated with WCAG changes and best practices

Remember: Accessibility is not a one-time fix but an ongoing commitment to inclusive design.