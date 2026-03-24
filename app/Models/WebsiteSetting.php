<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'lab_id',
        'brand_name',
        'domain',
        'logo',
        'favicon',
        'primary_color',
        'phone',
        'email',
        'contact_email',
        'contact_phone',
        'address',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'banner_images',
        'footer_text',
        'header_content',
        'footer_content',
        'content_after_banner',
        'show_services_section',
        'services_heading',
        'services_subtitle',
        'show_testimonials_section',
        'testimonials_heading',
        'testimonials_subtitle',
        'show_faq_section',
        'faq_heading',
        'faq_subtitle',
        'home_collection_popup_enabled',
        'home_collection_popup_delay_seconds',
        'home_collection_popup_frequency',
        'announcement_enabled',
        'announcement_text',
        'announcement_link',
        'meta_title',
        'meta_description',
        'og_image',
        'show_promos_section',
        'promos_heading',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'description',
        'whatsapp_enabled',
        'whatsapp_account_sid',
        'whatsapp_auth_token',
        'whatsapp_from_number',
        'online_booking_enabled',
        'online_reports_enabled',
        'notifications_enabled',
        'analytics_enabled',
        'printer_name',
        'printer_ip',
        'printer_port',
        'paper_size',
        'printer_type',
        'report_template_style',
        'payments_enabled',
        'booking_require_payment',
        'payment_gateway',
        'payment_api_key',
        'payment_api_secret',
        'payment_test_mode',
        'default_language',
        'available_languages',
        'two_factor_enabled',
        'session_timeout',
        'ip_whitelist_enabled',
        // Alert Settings
        'alerts_email_enabled',
        'alerts_sms_enabled',
        'alert_test_results',
        'alert_appointments',
        'alert_payments',
        'alert_inventory',
        'appointment_reminder_hours',
        'appointment_reminder_enabled',
        'payment_reminder_days',
        'payment_reminder_enabled',
        'ip_whitelist',
        'password_policy_enabled',
        'password_min_length',
    ];

    protected $casts = [
        'whatsapp_enabled' => 'boolean',
        'banner_images' => 'array',
        'available_languages' => 'array',
        'ip_whitelist' => 'array',
        'show_services_section' => 'boolean',
        'show_testimonials_section' => 'boolean',
        'show_faq_section' => 'boolean',
        'home_collection_popup_enabled' => 'boolean',
        'announcement_enabled' => 'boolean',
        'show_promos_section' => 'boolean',
        'online_booking_enabled' => 'boolean',
        'online_reports_enabled' => 'boolean',
        'notifications_enabled' => 'boolean',
        'analytics_enabled' => 'boolean',
        'payments_enabled' => 'boolean',
        'booking_require_payment' => 'boolean',
        'payment_test_mode' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'ip_whitelist_enabled' => 'boolean',
        'password_policy_enabled' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Get or create website settings for current lab.
     */
    public static function getForCurrentLab()
    {
        $labId = currentLabId();
        if (!$labId) {
            return null;
        }

        $lab = currentLab();
        
        return static::firstOrCreate(
            ['lab_id' => $labId],
            [
                'brand_name' => $lab?->name ?? 'HealthMatrix',
                'primary_color' => '#3B82F6',
                'hero_title' => 'Welcome to ' . ($lab?->name ?? 'HealthMatrix'),
                'hero_subtitle' => 'Your trusted diagnostic center',
                'online_booking_enabled' => true,
                'online_reports_enabled' => true,
                'notifications_enabled' => true,
                'analytics_enabled' => true,
                'paper_size' => 'A4',
                'printer_type' => 'thermal',
                'report_template_style' => 'lal_pathlabs',
                'default_language' => 'en',
                'available_languages' => ['en'],
                'session_timeout' => 120,
                'password_policy_enabled' => true,
                'password_min_length' => 8,
            ]
        );
    }

    /**
     * Lab intends to collect payment at booking (feature toggles on).
     */
    public static function wantsPayWhenBooking(?self $settings): bool
    {
        return $settings
            && ($settings->payments_enabled ?? false)
            && ($settings->booking_require_payment ?? false);
    }

    /**
     * True when redirect to pay-bill after booking will work (matches PublicBillPaymentController requirements).
     * PayPal is not supported on the public pay page.
     */
    public static function bookingPaymentRedirectReady(?self $settings): bool
    {
        if (! static::wantsPayWhenBooking($settings)) {
            return false;
        }
        $gw = $settings->payment_gateway ?? '';
        if (! in_array($gw, ['razorpay', 'stripe'], true)) {
            return false;
        }

        return ! empty($settings->payment_api_key) && ! empty($settings->payment_api_secret);
    }

    /**
     * User-facing hint when pay-at-booking is enabled but redirect cannot run (empty string if none).
     */
    public static function bookingPaymentSetupHint(?self $settings): string
    {
        if (! static::wantsPayWhenBooking($settings)) {
            return '';
        }
        if (static::bookingPaymentRedirectReady($settings)) {
            return '';
        }
        $gw = $settings->payment_gateway ?? '';
        if ($gw === 'paypal' || $gw === '') {
            return 'Online payment at booking requires Razorpay or Stripe (not PayPal). Open Settings → Payments, choose Razorpay or Stripe, and save your API Key and Secret.';
        }
        if (! in_array($gw, ['razorpay', 'stripe'], true)) {
            return 'Configure Razorpay or Stripe under Settings → Payments to enable pay-at-booking.';
        }

        return 'Add both API Key and API Secret under Settings → Payments so the payment page can charge cards.';
    }
}
