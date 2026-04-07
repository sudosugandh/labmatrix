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
        'theme_preset',
        'secondary_color',
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
        'footer_tagline',
        'pre_footer_cta_enabled',
        'pre_footer_heading',
        'pre_footer_subtitle',
        'footer_show_trust_badges',
        'footer_trust_nabl',
        'footer_trust_iso',
        'footer_trust_patients',
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
        'trust_strip_enabled',
        'trust_strip_items',
        'hero_cta_primary_label',
        'hero_cta_primary_url',
        'hero_cta_secondary_label',
        'hero_cta_secondary_url',
        'hero_cta_tertiary_label',
        'hero_cta_tertiary_url',
        'promos_today_strip_enabled',
        'promos_today_heading',
        'promos_today_subtitle',
        'promos_subtitle',
        'spotlight_enabled',
        'spotlight_name',
        'spotlight_credentials',
        'spotlight_tagline',
        'spotlight_image',
        'spotlight_experience',
        'about_intro',
        'about_why_cards',
        'about_stat_tests',
        'about_stat_patients',
        'about_stat_years',
        'about_working_hours',
        'contact_working_hours',
        'about_team_trust_line',
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
        'trust_strip_enabled' => 'boolean',
        'trust_strip_items' => 'array',
        'promos_today_strip_enabled' => 'boolean',
        'spotlight_enabled' => 'boolean',
        'about_why_cards' => 'array',
        'pre_footer_cta_enabled' => 'boolean',
        'footer_show_trust_badges' => 'boolean',
        'footer_trust_nabl' => 'boolean',
        'footer_trust_iso' => 'boolean',
        'footer_trust_patients' => 'boolean',
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
        if (! $labId) {
            return null;
        }

        $lab = currentLab();

        return static::firstOrCreate(
            ['lab_id' => $labId],
            [
                'brand_name' => $lab?->name ?? 'HealthMatrix',
                'primary_color' => '#3B82F6',
                'theme_preset' => 'violet',
                'hero_title' => 'Accurate Lab Tests. Fast Reports. Trusted Care.',
                'hero_subtitle' => 'Book your test online and get reliable reports quickly.',
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

    /**
     * Resolve a vendor-entered URL for the public site (absolute, relative path, tel, mailto, whatsapp).
     */
    public static function resolvePublicUrl(?string $url): string
    {
        $u = trim((string) $url);
        if ($u === '') {
            return '';
        }
        if (preg_match('#^(https?://|tel:|mailto:|whatsapp:)#i', $u)) {
            return $u;
        }

        return url($u);
    }

    /**
     * Build https://wa.me/{digits} from a display phone number (contact_phone / phone).
     * 10-digit numbers are treated as India (+91). Other lengths use digits as stored after stripping formatting.
     */
    public static function whatsappUrlFromPhone(?string $phone): ?string
    {
        $raw = trim((string) $phone);
        if ($raw === '') {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $raw);
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if ($digits === '') {
            return null;
        }
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }

        return 'https://wa.me/'.$digits;
    }

    /** Default copy when contact_working_hours / about_working_hours are empty (public contact page). */
    public static function defaultContactWorkingHours(): string
    {
        return "Mon–Sat: 8:00 AM – 8:00 PM\nSunday: 9:00 AM – 2:00 PM";
    }

    public function heroPrimaryLabel(): string
    {
        return trim((string) ($this->hero_cta_primary_label ?? '')) ?: 'Book Appointment';
    }

    public function heroPrimaryUrl(): string
    {
        $u = trim((string) ($this->hero_cta_primary_url ?? ''));

        return $u !== '' ? self::resolvePublicUrl($u) : route('public.appointments.create');
    }

    public function heroSecondaryLabel(): string
    {
        return trim((string) ($this->hero_cta_secondary_label ?? '')) ?: 'View Reports';
    }

    public function heroSecondaryUrl(): string
    {
        $u = trim((string) ($this->hero_cta_secondary_url ?? ''));

        return $u !== '' ? self::resolvePublicUrl($u) : route('public.reports.search');
    }

    public function heroTertiaryLabel(): ?string
    {
        $t = trim((string) ($this->hero_cta_tertiary_label ?? ''));

        return $t !== '' ? $t : null;
    }

    public function heroTertiaryUrl(): ?string
    {
        $u = trim((string) ($this->hero_cta_tertiary_url ?? ''));
        if ($u !== '') {
            return self::resolvePublicUrl($u);
        }

        $phone = trim((string) ($this->contact_phone ?? '')) ?: trim((string) ($this->phone ?? ''));

        return self::whatsappUrlFromPhone($phone);
    }

    /**
     * @return list<array{label: string, url: string, image?: string}>
     */
    public function trustStripItemsForDisplay(): array
    {
        $items = $this->trust_strip_items ?? [];
        if (! is_array($items)) {
            return [];
        }
        $out = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $label = trim((string) ($item['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            $row = [
                'label' => $label,
                'url' => trim((string) ($item['url'] ?? '')),
            ];
            if (! empty($item['image'])) {
                $row['image'] = (string) $item['image'];
            }
            $out[] = $row;
        }

        return $out;
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    public static function defaultAboutWhyCards(): array
    {
        return [
            ['title' => 'Accurate Reports', 'body' => 'Validated methods, internal quality controls, and clear reporting so you and your doctor can make informed decisions.'],
            ['title' => 'Fast Turnaround', 'body' => 'Efficient processing with same-day or next-day results for many routine tests, without compromising accuracy.'],
            ['title' => 'Experienced Team', 'body' => 'Qualified pathologists and trained technologists oversee every stage of sample handling and analysis.'],
            ['title' => 'Modern Equipment', 'body' => 'Up-to-date analysers and digital workflows reduce error and support consistent, reproducible results.'],
            ['title' => 'Trusted by Patients', 'body' => 'Thousands rely on us for routine checkups, specialised panels, and pre-treatment workups.'],
        ];
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    public function aboutWhyCardsForDisplay(): array
    {
        $raw = $this->about_why_cards ?? [];
        if (! is_array($raw)) {
            return self::defaultAboutWhyCards();
        }
        $out = [];
        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $out[] = [
                'title' => $title,
                'body' => trim((string) ($row['body'] ?? '')),
            ];
        }

        return count($out) > 0 ? $out : self::defaultAboutWhyCards();
    }

    /**
     * @return array{tests: string, patients: string, years: string}
     */
    public function aboutStatsDisplay(): array
    {
        return [
            'tests' => trim((string) ($this->about_stat_tests ?? '')) ?: '10,000+',
            'patients' => trim((string) ($this->about_stat_patients ?? '')) ?: '5,000+',
            'years' => trim((string) ($this->about_stat_years ?? '')) ?: '15+',
        ];
    }

    /**
     * Hours shown on /about — About-specific first, then Contact page hours.
     */
    public function aboutWorkingHoursDisplay(): string
    {
        $about = trim((string) ($this->about_working_hours ?? ''));
        if ($about !== '') {
            return $about;
        }

        return trim((string) ($this->contact_working_hours ?? ''));
    }

    /**
     * Split "why choose us" cards into rows: 5 cards → 3 + 2 (centered); 4 → 2×2; 6 → 3 + 3.
     *
     * @param  list<array{title: string, body: string}>  $cards
     * @return list<list<array{title: string, body: string}>>
     */
    public static function chunkAboutWhyRows(array $cards): array
    {
        $n = count($cards);
        if ($n === 0) {
            return [];
        }
        if ($n <= 3) {
            return [$cards];
        }
        if ($n === 4) {
            return [
                array_slice($cards, 0, 2),
                array_slice($cards, 2, 2),
            ];
        }
        if ($n === 5) {
            return [
                array_slice($cards, 0, 3),
                array_slice($cards, 3, 2),
            ];
        }

        return [
            array_slice($cards, 0, 3),
            array_slice($cards, 3, 3),
        ];
    }
}
