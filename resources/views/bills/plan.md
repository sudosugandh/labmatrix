Current State Summary
HealthMatrix is a multi-tenant diagnostic lab management SaaS with:

Domain-based tenancy, SuperAdmin, subscription fields (trial/basic/pro/enterprise), lab support notes
Per-lab: patients, tests, orders, reports, bills, appointments, inventory, payments
Feature gating only for Technicians (isLargeLab()), not by subscription plan
Features to Add (Prioritized by Business Impact)
1. Self-Service Lab Onboarding
Lab signup flow: Public signup page → verification → trial activation
Automated setup: Create lab + default domain (e.g. {slug}.healthmatrix.app)
Domain provisioning: Subdomain creation, DNS guidance, SSL via Let’s Encrypt
Why: Lets you scale without manual SuperAdmin work for each lab
2. Subscription & Billing Automation
Stripe/Billing integration: Subscription plans, usage billing
Feature gating by plan: e.g. Basic (no inventory), Pro (inventory + technicians), Enterprise (multiple domains, API)
Usage limits: Patients, orders, or reports per month
Dunning: Grace period, payment retries, suspension for non-payment
Why: subscription_plan, monthly_fee exist but are manual; automation turns them into revenue.
3. Centralized Billing Dashboard
MRR, churn, revenue per plan
Outstanding invoices, dunning stats
Usage-based metrics (patients, orders, reports by lab)
Why: Lets you see financial health and sales vs support impact.
4. Lab Usage Analytics & Health Scores
Per-lab usage: Orders, reports, active users, feature usage
Health scores: Engagement, adoption, trend
Alerts: Dropping usage, long inactivity, churn risk
Why: Proactive retention and upsell targeting.
5. White-Label & Branding Controls
Custom domains per lab (already have domains)
Branding kit: Logo, colors, favicon, email templates per lab
Email from addresses: e.g. reports@{lab-domain}
Why: Labs see it as “their” product; improves retention.
6. Support & Helpdesk
In-app support: From any lab tenant to your support team
Ticket system: Built on LabSupportNote (general, support, billing, technical)
Knowledge base: Help docs, FAQs
Status page: Uptime, incidents, maintenance
Why: Reduces support load and improves satisfaction.
7. Platform Health Monitoring
Availability checks: Public endpoints, domain resolution
Scheduled job monitoring: Queue, reports, cron
Error rate by lab: Exceptions, failed jobs
Why: You see problems before labs complain.
8. API Access (Enterprise)
Lab API keys: Auth for integrations (LIS, EHR, etc.)
Webhooks: Order created, report ready, payment received
Rate limits by plan
Why: Enterprise labs often need integrations; API is a strong differentiator.
9. Compliance & Audit
Audit log per lab: Who did what, when
Data export (GDPR/right to portability): Export lab data
Data deletion: For churn or compliance
HIPAA-friendly setup: BAA, encryption, access logs
Why: Required for regulated healthcare labs.
10. Multi-Location Labs
Branches per lab: Multiple collection centers
Branch-specific inventory and staff
Central reporting across branches
Why: Larger labs operate multiple sites.
11. Marketplace & Integrations
Integration directory: e.g. LIS, accounting, marketing
One-click install: OAuth / API key flow
Partner integrations: Billing for premium integrations
Why: Ecosystem growth and stickiness.
12. Referral & Growth
Lab referral program: Credits for new labs
In-app upgrade prompts: When approaching plan limits
Trial conversion: Onboarding checklist, success metrics
Why: Lower CAC and higher trial → paid conversion.
13. Automated Communication
Lifecycle emails: Welcome, trial ending, upgrade nudges, churn winback
In-app announcements: New features, maintenance
Lab-specific notifications: Configurable alerts
Why: Increases awareness and retention.
14. Resource & Limits Management
Storage limits by plan: Reports, documents, images
User limits: Admins, technicians, etc.
Concurrent session limits
Why: Clear plan boundaries and upgrade incentives.
15. Reseller / Channel Partners
Reseller portal: Manage multiple labs, revenue share
White-label reseller: Their branding, your platform
Partner commissions dashboard
Why: Increases distribution and market reach.
Quick Wins (Lower Effort, High Value)
Feature	Effort	Impact
Feature gating by subscription_plan	Low	High – enforce plans, drive upgrades
Automated trial expiration (e.g. 14 days)	Low	Medium – clear lifecycle
In-app upgrade CTA when hitting limits	Low	Medium – upsell at right moment
Support ticket from Settings	Low	Medium – better support workflow
Lab usage stats on SuperAdmin dashboard	Low	High – visibility into health
Automated dunning emails	Medium	High – fewer involuntary churn
These align with HealthMatrix’s current structure and support a SaaS lab platform focused on growth, retention, and scalability.



