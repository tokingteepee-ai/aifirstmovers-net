# HubSpot Integration Guide

This document outlines the integration strategy between AIFirstMovers.net WordPress site and HubSpot CRM for lead management, marketing automation, and analytics tracking.

## Overview

HubSpot integration enables:
- Lead capture from website forms
- Contact lifecycle management
- Email marketing automation
- Analytics and reporting
- Customer journey tracking

## Integration Methods

### 1. HubSpot WordPress Plugin

#### Installation
1. Install the official HubSpot plugin from WordPress repository
2. Connect your HubSpot account using API key or OAuth
3. Configure tracking code installation

#### Features
- Automatic tracking code installation
- Form creation and management
- Contact synchronization
- Lead scoring integration

### 2. HubSpot Tracking Code

Add to theme files or use plugin:

```javascript
<!-- HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/YOUR-HUB-ID.js"></script>
```

### 3. Forms Integration

#### Contact Forms
- Use HubSpot forms or integrate existing WordPress forms
- Map form fields to HubSpot contact properties
- Set up automatic lead assignment

#### Newsletter Signup
```html
<!-- HubSpot Newsletter Form -->
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
<script>
  hbspt.forms.create({
    region: "na1",
    portalId: "YOUR-PORTAL-ID",
    formId: "YOUR-FORM-ID"
  });
</script>
```

## Contact Management

### Lead Capture Points

1. **Homepage Contact Form**
   - Name, Email, Company, Message
   - Lead source: Website

2. **Resource Downloads**
   - Gated content forms
   - Progressive profiling
   - Lead scoring based on content type

3. **Newsletter Subscription**
   - Email capture
   - Preference settings
   - Automated nurture sequences

4. **Demo Requests**
   - Detailed qualification form
   - Immediate sales notification
   - Follow-up automation

### Contact Properties

Map WordPress user data to HubSpot:

```php
// Custom fields mapping
$contact_properties = array(
    'email' => $user_email,
    'firstname' => $first_name,
    'lastname' => $last_name,
    'company' => $company_name,
    'website' => $website_url,
    'industry' => $industry,
    'lead_source' => 'Website',
    'lead_status' => 'New'
);
```

### Lifecycle Stages

1. **Subscriber** - Newsletter signups
2. **Lead** - Downloaded resources
3. **Marketing Qualified Lead (MQL)** - Engaged with multiple resources
4. **Sales Qualified Lead (SQL)** - Requested demo/consultation
5. **Opportunity** - In active sales process
6. **Customer** - Purchased services

## Marketing Automation

### Email Workflows

#### Welcome Series
1. Immediate welcome email
2. Company introduction (Day 1)
3. Resource recommendations (Day 3)
4. Case study sharing (Day 7)
5. Consultation offer (Day 14)

#### Lead Nurturing
- Score-based triggers
- Content recommendations
- Industry-specific messaging
- Behavioral email sends

#### Re-engagement
- Inactive contact identification
- Win-back campaign
- Content preferences update

### Lead Scoring

Points assignment:
- Email open: +1 point
- Email click: +3 points
- Website visit: +2 points
- Resource download: +10 points
- Demo request: +50 points
- Multiple page views: +5 points

## Analytics & Reporting

### Key Metrics

1. **Traffic Analytics**
   - Website visitors
   - Page views
   - Session duration
   - Bounce rate

2. **Conversion Metrics**
   - Form submissions
   - Conversion rates by source
   - Lead quality scores
   - Customer acquisition cost

3. **Content Performance**
   - Resource download rates
   - Email engagement
   - Social shares
   - Time on content pages

### Custom Reports

Create HubSpot reports for:
- Monthly lead generation
- Content ROI analysis
- Sales pipeline velocity
- Customer journey analysis

## Implementation Checklist

### Phase 1: Basic Setup
- [ ] Install HubSpot WordPress plugin
- [ ] Configure tracking code
- [ ] Set up basic contact forms
- [ ] Create contact properties
- [ ] Map existing contacts

### Phase 2: Forms & Lead Capture
- [ ] Design lead capture forms
- [ ] Implement progressive profiling
- [ ] Set up form notifications
- [ ] Create thank you pages
- [ ] Configure form analytics

### Phase 3: Automation
- [ ] Build welcome email series
- [ ] Create lead nurturing workflows
- [ ] Set up lead scoring
- [ ] Configure sales notifications
- [ ] Design re-engagement campaigns

### Phase 4: Analytics
- [ ] Set up conversion tracking
- [ ] Create custom reports
- [ ] Configure dashboard views
- [ ] Set up automated reporting
- [ ] Train team on analytics

## Best Practices

### Data Privacy
- GDPR compliance
- Cookie consent management
- Data retention policies
- Opt-out mechanisms
- Privacy policy updates

### Form Optimization
- Mobile-responsive design
- Minimal required fields
- Clear value propositions
- Social proof elements
- A/B testing

### Email Marketing
- Personalization tokens
- Segmented messaging
- Mobile optimization
- Clear CTAs
- Unsubscribe options

## Technical Configuration

### API Integration

```php
// HubSpot API integration example
function sync_wordpress_user_to_hubspot($user_id) {
    $user = get_user_by('id', $user_id);
    $hubspot_api_key = get_option('hubspot_api_key');
    
    $contact_data = array(
        'properties' => array(
            array(
                'property' => 'email',
                'value' => $user->user_email
            ),
            array(
                'property' => 'firstname',
                'value' => $user->first_name
            ),
            array(
                'property' => 'lastname',
                'value' => $user->last_name
            )
        )
    );
    
    // API call to HubSpot
    $response = wp_remote_post('https://api.hubapi.com/contacts/v1/contact', array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $hubspot_api_key
        ),
        'body' => json_encode($contact_data)
    ));
}
```

### Webhook Setup
- Configure HubSpot webhooks for real-time updates
- Handle contact property changes
- Sync deal stage updates
- Process form submissions

## Maintenance

### Regular Tasks
- Monitor form performance
- Review automation workflows
- Update email templates
- Audit contact data quality
- Optimize conversion rates

### Monthly Reviews
- Analyze lead generation trends
- Review email campaign performance
- Update contact scoring rules
- Audit automation effectiveness

## Support Resources

- [HubSpot WordPress Plugin Documentation](https://knowledge.hubspot.com/integrations/how-to-install-the-hubspot-wordpress-plugin)
- [HubSpot API Documentation](https://developers.hubspot.com/)
- [HubSpot Academy Training](https://academy.hubspot.com/)
- [WordPress Integration Best Practices](https://knowledge.hubspot.com/integrations/hubspot-wordpress-plugin-best-practices)