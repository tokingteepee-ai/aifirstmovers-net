# HubSpot Integration Guide

This guide covers integrating the AI First Movers website with HubSpot for lead generation, marketing automation, and analytics.

## Overview

HubSpot integration will enable:
- Contact form submissions to HubSpot CRM
- Lead tracking and scoring
- Email marketing automation
- Analytics and reporting
- Resource download tracking

## Prerequisites

- Active HubSpot account (Marketing Hub recommended)
- WordPress admin access
- API access permissions in HubSpot

## Installation Methods

### Method 1: Official HubSpot Plugin (Recommended)

1. **Install Plugin**:
   - Go to **Plugins > Add New**
   - Search for "HubSpot All-In-One Marketing"
   - Install and activate

2. **Connect Account**:
   - Go to **HubSpot > Settings**
   - Click "Connect HubSpot Account"
   - Authorize WordPress access

3. **Configure Settings**:
   - Enable form submissions
   - Set up contact sync
   - Configure tracking code

### Method 2: Manual Integration

If using custom forms or advanced features:

1. **Get HubSpot API Key**:
   - Go to HubSpot Settings > Integrations > API Key
   - Generate and copy the key

2. **Add to wp-config.php**:
   ```php
   define('HUBSPOT_API_KEY', 'your-api-key-here');
   define('HUBSPOT_PORTAL_ID', 'your-portal-id-here');
   ```

## Configuration Steps

### 1. Tracking Code Setup

#### Automatic (Plugin Method)
The plugin will automatically add the tracking code to all pages.

#### Manual Method
Add this code to your theme's header.php or use a code injection plugin:

```html
<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/YOUR-PORTAL-ID.js"></script>
<!-- End of HubSpot Embed Code -->
```

### 2. Form Integration

#### Contact Forms

Create HubSpot forms and embed them:

1. **In HubSpot**:
   - Go to Marketing > Lead Capture > Forms
   - Create a new form
   - Configure fields and styling
   - Get embed code

2. **In WordPress**:
   - Add form to pages using shortcode or embed code
   - Style to match your theme

#### Resource Download Forms

For gated content and resource downloads:

```html
<!-- HubSpot Form for Resource Downloads -->
<div id="hubspot-form-download"></div>
<script>
hbspt.forms.create({
    region: "na1",
    portalId: "YOUR-PORTAL-ID",
    formId: "your-form-id",
    target: "#hubspot-form-download",
    onFormSubmit: function($form) {
        // Track download event
        gtag('event', 'download', {
            'event_category': 'Resources',
            'event_label': 'Resource Name'
        });
    },
    onFormReady: function($form) {
        // Customize form after it loads
        $form.find('input[type="submit"]').addClass('aifm-button-primary');
    }
});
</script>
```

### 3. Contact Properties

Set up custom properties in HubSpot for better lead scoring:

#### Standard Properties
- First Name
- Last Name
- Email
- Company
- Job Title
- Phone

#### Custom Properties
- **AI Interest Level** (dropdown: Beginner, Intermediate, Advanced, Expert)
- **Company Size** (dropdown: 1-10, 11-50, 51-200, 201-1000, 1000+)
- **AI Budget** (number)
- **Implementation Timeline** (dropdown: Immediate, 3 months, 6 months, 12+ months)
- **Resource Downloads** (multi-checkbox of downloaded resources)
- **Lead Source** (Website, Social Media, Referral, etc.)

### 4. Lead Scoring

Configure lead scoring based on:

#### Demographic Information
- Job title (C-level: +20, Manager: +10, etc.)
- Company size (1000+: +25, 201-1000: +15, etc.)
- Industry relevance (+10 for tech companies)

#### Behavioral Scoring
- Resource downloads (+5 each)
- Page views (+1 each, +3 for pricing page)
- Form submissions (+15)
- Email engagement (+5 for opens, +10 for clicks)

#### Implementation in HubSpot
1. Go to **Settings > Properties**
2. Create "HubSpot Score" property
3. Set up scoring rules in **Marketing > Lead Scoring**

### 5. Workflows and Automation

#### Welcome Email Series
Create a workflow for new contacts:

1. **Trigger**: Contact submits any form
2. **Actions**:
   - Send welcome email immediately
   - Wait 3 days, send resource recommendation
   - Wait 1 week, send case study
   - Wait 2 weeks, send consultation offer

#### Resource-Specific Follow-ups
For resource downloads:

1. **Trigger**: Contact downloads specific resource
2. **Actions**:
   - Send related resources
   - Tag contact with interest area
   - Notify sales team for high-value resources

### 6. Analytics and Reporting

#### Website Analytics
Track key metrics:
- Page views and sessions
- Form conversion rates
- Resource download rates
- Traffic sources

#### Marketing Attribution
Set up attribution reporting:
- First-touch attribution
- Last-touch attribution
- Multi-touch attribution for complex sales cycles

#### Custom Reports
Create reports for:
- Lead generation by source
- Resource performance
- Conversion funnel analysis
- ROI by channel

## Advanced Features

### 1. Progressive Profiling

Implement progressive profiling to gather more information over time:

```javascript
// Progressive profiling example
if (hbspt && hbspt.forms) {
    hbspt.forms.create({
        region: "na1",
        portalId: "YOUR-PORTAL-ID",
        formId: "your-form-id",
        target: "#progressive-form",
        progressiveFields: [
            {name: "company", order: 1},
            {name: "jobtitle", order: 2},
            {name: "phone", order: 3}
        ]
    });
}
```

### 2. Smart Content

Use HubSpot's smart content for personalization:

#### Implementation
1. Create smart content rules in HubSpot
2. Add smart content to key pages
3. Personalize based on:
   - Industry
   - Company size
   - Previous resource downloads
   - Lead score

### 3. Chatbots

Integrate HubSpot chatbots:

1. **Create Chatbot** in HubSpot
2. **Configure Flow**:
   - Qualify visitors
   - Route to appropriate resources
   - Capture contact information
   - Schedule meetings

3. **Install on Website**:
   - Add chatbot code to specific pages
   - Customize appearance to match theme

## WordPress Plugin Integration

### Custom Plugin Enhancements

Add HubSpot integration to the AI First Movers Core plugin:

```php
// Add to plugin-aifm-core.php

/**
 * Track resource downloads in HubSpot
 */
function aifm_track_hubspot_download($resource_id) {
    $api_key = defined('HUBSPOT_API_KEY') ? HUBSPOT_API_KEY : '';
    
    if (!$api_key) return;
    
    $resource = get_post($resource_id);
    $user_email = wp_get_current_user()->user_email;
    
    if ($resource && $user_email) {
        $data = array(
            'properties' => array(
                array(
                    'property' => 'resource_downloaded',
                    'value' => $resource->post_title
                ),
                array(
                    'property' => 'last_resource_download',
                    'value' => current_time('mysql')
                )
            )
        );
        
        // Send to HubSpot API
        wp_remote_post('https://api.hubapi.com/contacts/v1/contact/email/' . $user_email . '/profile', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data)
        ));
    }
}
```

## Testing and Validation

### 1. Form Testing
- Test all form submissions
- Verify data appears in HubSpot
- Check email notifications

### 2. Tracking Validation
- Use HubSpot's tracking tool
- Verify page views are recorded
- Test conversion tracking

### 3. Automation Testing
- Submit test forms
- Verify workflows trigger
- Check email delivery

## Privacy and Compliance

### GDPR Compliance
1. **Cookie Consent**: Implement cookie consent banner
2. **Data Processing**: Add privacy notices to forms
3. **Right to be Forgotten**: Set up data deletion workflows

### Cookie Management
```javascript
// Cookie consent integration
if (typeof cookieConsent !== 'undefined' && cookieConsent.marketing) {
    // Load HubSpot tracking
    (function(h,u,b,s,p,o,t){h[p]=h[p]||[],h[p].push(s);})(window,document,'script','//js.hs-scripts.com/YOUR-PORTAL-ID.js','hbspt');
}
```

## Maintenance and Monitoring

### Regular Tasks
- **Weekly**: Review form performance
- **Monthly**: Analyze lead quality and scoring
- **Quarterly**: Optimize workflows and automation

### Monitoring
Set up alerts for:
- Form submission failures
- API connection issues
- Workflow errors
- Integration sync problems

## Troubleshooting

### Common Issues

#### Forms Not Submitting
- Check API keys and permissions
- Verify form IDs
- Review browser console for errors

#### Data Not Syncing
- Check HubSpot integration logs
- Verify field mappings
- Review API rate limits

#### Tracking Issues
- Verify tracking code installation
- Check for JavaScript conflicts
- Review privacy settings

## Support Resources

- **HubSpot Academy**: Free training and certification
- **HubSpot Support**: Technical support for account holders
- **Developer Documentation**: developers.hubspot.com
- **Community Forums**: community.hubspot.com

## Next Steps

After HubSpot integration:
1. Set up sales enablement tools
2. Create customer journey mapping
3. Implement advanced analytics
4. Train team on HubSpot features
5. Establish reporting schedules