<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Menu;
use App\Models\Page;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function index(Request $request): View
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $lab = Lab::find(currentLabId());
        $activeTab = $request->get('tab', 'branding');
        if ($activeTab === 'contact') {
            return redirect()->route('admin.settings.index', ['tab' => 'branding']);
        }

        $mainMenu = null;
        $cmsPages = collect();
        if ($activeTab === 'cms') {
            $mainMenu = Menu::where('lab_id', currentLabId())
                ->where('name', 'Main Menu')
                ->with('items')
                ->first();
            if (! $mainMenu) {
                $mainMenu = Menu::create(['lab_id' => currentLabId(), 'name' => 'Main Menu']);
                $mainMenu->load('items');
            }
            $cmsPages = Page::where('lab_id', currentLabId())->latest()->get();
        }

        $phpUploadMax = ini_get('upload_max_filesize') ?: 'unknown';
        $phpPostMax = ini_get('post_max_size') ?: 'unknown';

        return view('admin.settings.index', compact('settings', 'lab', 'activeTab', 'mainMenu', 'cmsPages', 'phpUploadMax', 'phpPostMax'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $tab = $request->get('tab', 'branding');

        $this->stripInvalidOptionalFileUploads($request);

        $rules = $this->getValidationRules($tab);
        $validated = $request->validate($rules);

        if ($tab === 'branding' && array_key_exists('secondary_color', $validated) && ($validated['secondary_color'] === '' || $validated['secondary_color'] === null)) {
            $validated['secondary_color'] = null;
        }

        // Handle file uploads
        $this->handleFileUploads($request, $settings, $validated);

        // Handle array fields
        if ($request->has('banner_images')) {
            $validated['banner_images'] = $this->handleBannerImages($request, $settings);
        }

        if ($request->has('available_languages')) {
            $validated['available_languages'] = $request->available_languages ?? [];
        }

        if ($request->has('ip_whitelist')) {
            $validated['ip_whitelist'] = array_filter(explode(',', $request->ip_whitelist ?? ''));
        }

        if ($request->has('header_content')) {
            $validated['header_content'] = $request->header_content;
        }
        if ($request->has('footer_content')) {
            $validated['footer_content'] = $request->footer_content;
        }
        if ($request->has('content_after_banner')) {
            $validated['content_after_banner'] = $request->content_after_banner;
        }
        if ($request->has('banner_images')) {
            $validated['banner_images'] = $this->handleBannerImages($request, $settings);
        }

        if ($tab === 'cms') {
            $validated['show_services_section'] = $request->boolean('show_services_section');
            $validated['show_testimonials_section'] = $request->boolean('show_testimonials_section');
            $validated['show_faq_section'] = $request->boolean('show_faq_section');
            $validated['home_collection_popup_enabled'] = $request->boolean('home_collection_popup_enabled');
            $validated['home_collection_popup_delay_seconds'] = (int) $request->input('home_collection_popup_delay_seconds', 2);
            $validated['home_collection_popup_frequency'] = in_array($request->input('home_collection_popup_frequency'), ['session', 'daily']) ? $request->home_collection_popup_frequency : 'session';
            $validated['announcement_enabled'] = $request->boolean('announcement_enabled');
            $validated['announcement_text'] = $request->input('announcement_text');
            $validated['announcement_link'] = $request->input('announcement_link');
            $validated['meta_title'] = $request->input('meta_title');
            $validated['meta_description'] = $request->input('meta_description');
            $validated['show_promos_section'] = $request->boolean('show_promos_section');
            $validated['promos_heading'] = $request->input('promos_heading');
            $validated['promos_subtitle'] = $request->input('promos_subtitle');
            $validated['promos_today_strip_enabled'] = $request->boolean('promos_today_strip_enabled');
            $validated['promos_today_heading'] = $request->input('promos_today_heading');
            $validated['promos_today_subtitle'] = $request->input('promos_today_subtitle');
            $validated['trust_strip_enabled'] = $request->boolean('trust_strip_enabled');
            $validated['spotlight_enabled'] = $request->boolean('spotlight_enabled');
            $validated['trust_strip_items'] = $this->mergeTrustStripItemsFromRequest($request, $settings);
            $validated['about_intro'] = $request->input('about_intro');
            $validated['about_why_cards'] = $this->mergeAboutWhyCardsFromRequest($request);
            $validated['home_how_heading'] = $request->input('home_how_heading');
            $validated['home_how_subtitle'] = $request->input('home_how_subtitle');
            $validated['home_how_steps'] = $this->mergeHomeHowStepsFromRequest($request);
            $validated['home_why_heading'] = $request->input('home_why_heading');
            $validated['home_why_subtitle'] = $request->input('home_why_subtitle');
            $validated['home_why_cards'] = $this->mergeHomeWhyCardsFromRequest($request);
            $validated['home_show_stats_strip'] = $request->boolean('home_show_stats_strip');
            $validated['home_map_url'] = $request->input('home_map_url') ?: null;
            $validated['home_show_packages_teaser'] = $request->boolean('home_show_packages_teaser');
            $validated['about_stat_tests'] = $request->input('about_stat_tests');
            $validated['about_stat_patients'] = $request->input('about_stat_patients');
            $validated['about_stat_years'] = $request->input('about_stat_years');
            $validated['about_working_hours'] = $request->input('about_working_hours');
            $validated['spotlight_experience'] = $request->input('spotlight_experience');
            $validated['about_team_trust_line'] = $request->input('about_team_trust_line');
            $validated['contact_working_hours'] = $request->input('contact_working_hours');
            $validated['footer_tagline'] = $request->input('footer_tagline');
            $validated['pre_footer_heading'] = $request->input('pre_footer_heading');
            $validated['pre_footer_subtitle'] = $request->input('pre_footer_subtitle');
            $validated['pre_footer_cta_enabled'] = $request->boolean('pre_footer_cta_enabled');
            $validated['footer_show_trust_badges'] = $request->boolean('footer_show_trust_badges');
            $validated['footer_trust_nabl'] = $request->boolean('footer_trust_nabl');
            $validated['footer_trust_iso'] = $request->boolean('footer_trust_iso');
            $validated['footer_trust_patients'] = $request->boolean('footer_trust_patients');

            if ($request->boolean('spotlight_remove_image')) {
                if ($settings->spotlight_image) {
                    Storage::disk('public')->delete($settings->spotlight_image);
                }
                $validated['spotlight_image'] = null;
            }
            if ($request->hasFile('spotlight_image')) {
                if ($settings->spotlight_image) {
                    Storage::disk('public')->delete($settings->spotlight_image);
                }
                $validated['spotlight_image'] = $request->file('spotlight_image')->store('website', 'public');
            }
            unset($validated['spotlight_remove_image'], $validated['trust_strip'], $validated['about_why'], $validated['home_how'], $validated['home_why']);
            $validated['facebook_url'] = $request->input('facebook_url') ?: null;
            $validated['twitter_url'] = $request->input('twitter_url') ?: null;
            $validated['instagram_url'] = $request->input('instagram_url') ?: null;
            $validated['linkedin_url'] = $request->input('linkedin_url') ?: null;
            $validated['youtube_url'] = $request->input('youtube_url') ?: null;
            $gtm = trim((string) $request->input('public_gtm_container_id', ''));
            $validated['public_gtm_container_id'] = $gtm === '' ? null : strtoupper($gtm);
            if ($request->hasFile('og_image')) {
                if ($settings->og_image) {
                    Storage::disk('public')->delete($settings->og_image);
                }
                $validated['og_image'] = $request->file('og_image')->store('website', 'public');
            }
        }

        $settings->update($validated);

        if ($tab === 'branding' && ! empty($validated['brand_name'] ?? null) && currentLabId()) {
            Lab::where('id', currentLabId())->update(['name' => $validated['brand_name']]);
        }

        return redirect()->route('admin.settings.index', ['tab' => $tab])
            ->with('success', 'Settings updated successfully.');
    }

    protected function getValidationRules(string $tab): array
    {
        $rules = [];

        switch ($tab) {
            case 'general':
                $rules = [
                    'domain' => 'nullable|string|max:255',
                    'banner_images.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/svg+xml,video/mp4,video/quicktime,video/x-msvideo,video/webm|max:51200',
                    'description' => 'nullable|string|max:1000',
                ];
                break;

            case 'branding':
                $rules = [
                    'brand_name' => 'nullable|string|max:255',
                    'logo' => $this->optionalUploadImageRules(2048),
                    'favicon' => $this->optionalUploadImageRules(512),
                    'primary_color' => 'nullable|string|max:7',
                    'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'contact_phone' => 'nullable|string|max:20',
                    'contact_email' => 'nullable|email|max:255',
                    'address' => 'nullable|string',
                ];
                break;

            case 'features':
                $rules = [
                    'online_booking_enabled' => 'nullable|boolean',
                    'online_reports_enabled' => 'nullable|boolean',
                    'notifications_enabled' => 'nullable|boolean',
                    'analytics_enabled' => 'nullable|boolean',
                    'payments_enabled' => 'nullable|boolean',
                    'booking_require_payment' => 'nullable|boolean',
                ];
                break;

            case 'printer':
                $rules = [
                    'printer_name' => 'nullable|string|max:255',
                    'printer_ip' => 'nullable|ip',
                    'printer_port' => 'nullable|integer|min:1|max:65535',
                    'paper_size' => 'nullable|in:A4,A5,Letter',
                    'printer_type' => 'nullable|in:thermal,inkjet,laser',
                    'report_template_style' => 'nullable|in:lal_pathlabs,default',
                ];
                break;

            case 'payments':
                $rules = [
                    'payments_enabled' => 'nullable|boolean',
                    'payment_gateway' => 'nullable|in:stripe,razorpay,paypal',
                    'payment_api_key' => 'nullable|string|max:255',
                    'payment_api_secret' => 'nullable|string|max:255',
                    'payment_test_mode' => 'nullable|boolean',
                ];
                break;

            case 'languages':
                $rules = [
                    'default_language' => 'nullable|in:en,hi,gu,es,fr',
                    'available_languages' => 'nullable|array',
                    'available_languages.*' => 'in:en,hi,gu,es,fr',
                ];
                break;

            case 'security':
                $rules = [
                    'two_factor_enabled' => 'nullable|boolean',
                    'session_timeout' => 'nullable|integer|min:5|max:1440',
                    'ip_whitelist_enabled' => 'nullable|boolean',
                    'ip_whitelist' => 'nullable|string',
                    'password_policy_enabled' => 'nullable|boolean',
                    'password_min_length' => 'nullable|integer|min:6|max:32',
                ];
                break;

            case 'cms':
                $rules = [
                    'hero_title' => 'nullable|string|max:255',
                    'hero_subtitle' => 'nullable|string',
                    'hero_image' => $this->optionalUploadImageRules(5120),
                    'footer_text' => 'nullable|string',
                    'footer_tagline' => 'nullable|string|max:500',
                    'pre_footer_heading' => 'nullable|string|max:255',
                    'pre_footer_subtitle' => 'nullable|string|max:500',
                    'pre_footer_cta_enabled' => 'nullable|boolean',
                    'footer_show_trust_badges' => 'nullable|boolean',
                    'footer_trust_nabl' => 'nullable|boolean',
                    'footer_trust_iso' => 'nullable|boolean',
                    'footer_trust_patients' => 'nullable|boolean',
                    'header_content' => 'nullable|string',
                    'footer_content' => 'nullable|string',
                    'content_after_banner' => 'nullable|string',
                    'show_services_section' => 'nullable|boolean',
                    'services_heading' => 'nullable|string|max:255',
                    'services_subtitle' => 'nullable|string|max:500',
                    'show_testimonials_section' => 'nullable|boolean',
                    'testimonials_heading' => 'nullable|string|max:255',
                    'testimonials_subtitle' => 'nullable|string|max:500',
                    'show_faq_section' => 'nullable|boolean',
                    'faq_heading' => 'nullable|string|max:255',
                    'faq_subtitle' => 'nullable|string|max:500',
                    'home_collection_popup_enabled' => 'nullable|boolean',
                    'home_collection_popup_delay_seconds' => 'nullable|integer|min:0|max:60',
                    'home_collection_popup_frequency' => 'nullable|in:session,daily',
                    'announcement_enabled' => 'nullable|boolean',
                    'announcement_text' => 'nullable|string|max:500',
                    'announcement_link' => 'nullable|string|max:500',
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:500',
                    'og_image' => $this->optionalUploadImageRules(2048),
                    'show_promos_section' => 'nullable|boolean',
                    'promos_heading' => 'nullable|string|max:255',
                    'promos_subtitle' => 'nullable|string|max:500',
                    'promos_today_strip_enabled' => 'nullable|boolean',
                    'promos_today_heading' => 'nullable|string|max:255',
                    'promos_today_subtitle' => 'nullable|string|max:500',
                    'trust_strip_enabled' => 'nullable|boolean',
                    'trust_strip' => 'nullable|array',
                    'trust_strip.*.label' => 'nullable|string|max:120',
                    'trust_strip.*.url' => 'nullable|string|max:500',
                    'trust_strip.*.image_keep' => 'nullable|string|max:500',
                    'trust_strip.*.image' => $this->optionalUploadImageRules(2048),
                    'hero_cta_primary_label' => 'nullable|string|max:120',
                    'hero_cta_primary_url' => 'nullable|string|max:500',
                    'hero_cta_secondary_label' => 'nullable|string|max:120',
                    'hero_cta_secondary_url' => 'nullable|string|max:500',
                    'hero_cta_tertiary_label' => 'nullable|string|max:120',
                    'hero_cta_tertiary_url' => 'nullable|string|max:500',
                    'spotlight_enabled' => 'nullable|boolean',
                    'spotlight_name' => 'nullable|string|max:255',
                    'spotlight_credentials' => 'nullable|string|max:255',
                    'spotlight_tagline' => 'nullable|string|max:500',
                    'spotlight_experience' => 'nullable|string|max:255',
                    'spotlight_image' => $this->optionalUploadImageRules(2048),
                    'spotlight_remove_image' => 'nullable|boolean',
                    'about_intro' => 'nullable|string',
                    'about_why' => 'nullable|array',
                    'about_why.*.title' => 'nullable|string|max:120',
                    'about_why.*.body' => 'nullable|string|max:500',
                    'home_how_heading' => 'nullable|string|max:255',
                    'home_how_subtitle' => 'nullable|string|max:500',
                    'home_how' => 'nullable|array',
                    'home_how.*.title' => 'nullable|string|max:120',
                    'home_how.*.body' => 'nullable|string|max:500',
                    'home_why_heading' => 'nullable|string|max:255',
                    'home_why_subtitle' => 'nullable|string|max:500',
                    'home_why' => 'nullable|array',
                    'home_why.*.title' => 'nullable|string|max:120',
                    'home_why.*.body' => 'nullable|string|max:500',
                    'home_show_stats_strip' => 'nullable|boolean',
                    'home_map_url' => 'nullable|string|max:500',
                    'home_show_packages_teaser' => 'nullable|boolean',
                    'about_stat_tests' => 'nullable|string|max:80',
                    'about_stat_patients' => 'nullable|string|max:80',
                    'about_stat_years' => 'nullable|string|max:80',
                    'about_working_hours' => 'nullable|string|max:2000',
                    'about_team_trust_line' => 'nullable|string|max:500',
                    'contact_working_hours' => 'nullable|string|max:2000',
                    'facebook_url' => 'nullable|url|max:500',
                    'twitter_url' => 'nullable|url|max:500',
                    'instagram_url' => 'nullable|url|max:500',
                    'linkedin_url' => 'nullable|url|max:500',
                    'youtube_url' => 'nullable|url|max:500',
                    'public_gtm_container_id' => [
                        'nullable',
                        'string',
                        'max:24',
                        function (string $attribute, mixed $value, \Closure $fail): void {
                            $v = trim((string) $value);
                            if ($v !== '' && ! preg_match('/^GTM-[A-Z0-9]+$/i', $v)) {
                                $fail('Use a valid Google Tag Manager ID (e.g. GTM-XXXXXXX) or leave empty.');
                            }
                        },
                    ],
                    'banner_images.*' => $this->optionalUploadImageRules(5120),
                ];
                break;

            case 'alerts':
                $rules = [
                    'alerts_email_enabled' => 'nullable|boolean',
                    'alerts_sms_enabled' => 'nullable|boolean',
                    'alert_test_results' => 'nullable|boolean',
                    'alert_appointments' => 'nullable|boolean',
                    'alert_payments' => 'nullable|boolean',
                    'alert_inventory' => 'nullable|boolean',
                    'appointment_reminder_hours' => 'nullable|integer|min:1|max:168',
                    'appointment_reminder_enabled' => 'nullable|boolean',
                    'payment_reminder_days' => 'nullable|integer|min:1|max:30',
                    'payment_reminder_enabled' => 'nullable|boolean',
                ];
                break;
        }

        return $rules;
    }

    /**
     * Remove failed/empty file inputs so nullable optional fields do not fail the `image` / `mimes` rules.
     */
    protected function stripInvalidOptionalFileUploads(Request $request): void
    {
        foreach (['logo', 'favicon', 'hero_image', 'spotlight_image', 'og_image'] as $key) {
            if (! $request->hasFile($key)) {
                continue;
            }
            $file = $request->file($key);
            if ($file instanceof UploadedFile && ! $file->isValid()) {
                $request->files->remove($key);
            }
        }

        $all = $request->files->all();
        if (isset($all['trust_strip']) && is_array($all['trust_strip'])) {
            foreach ($all['trust_strip'] as $i => $row) {
                if (! is_array($row) || empty($row['image'])) {
                    continue;
                }
                $img = $row['image'];
                if ($img instanceof UploadedFile && ! $img->isValid()) {
                    unset($all['trust_strip'][$i]['image']);
                }
            }
            $request->files->replace($all);
        }
    }

    /**
     * Laravel's built-in `image` rule only allows jpeg/png/gif/bmp/webp (+svg optionally).
     * Phones often send AVIF/HEIC — include those for CMS/branding uploads.
     */
    protected function optionalUploadImageRules(int $maxKb): string
    {
        return 'nullable|file|max:'.$maxKb.'|mimes:jpeg,jpg,png,gif,bmp,webp,svg,avif,heic,heif';
    }

    protected function handleFileUploads(Request $request, WebsiteSetting $settings, array &$validated): void
    {
        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('website', 'public');
        } else {
            unset($validated['logo']);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('website', 'public');
        } else {
            unset($validated['favicon']);
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            if ($settings->hero_image) {
                Storage::disk('public')->delete($settings->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('website', 'public');
        } else {
            unset($validated['hero_image']);
        }
    }

    protected function handleBannerImages(Request $request, WebsiteSetting $settings): array
    {
        $bannerImages = $settings->banner_images ?? [];

        // Handle new banner uploads
        if ($request->hasFile('banner_images')) {
            foreach ($request->file('banner_images') as $file) {
                $bannerImages[] = $file->store('website/banners', 'public');
            }
        }

        // Handle banner deletions
        if ($request->has('delete_banners')) {
            $deleteIndexes = $request->delete_banners;
            foreach ($deleteIndexes as $index) {
                if (isset($bannerImages[$index])) {
                    Storage::disk('public')->delete($bannerImages[$index]);
                    unset($bannerImages[$index]);
                }
            }
            $bannerImages = array_values($bannerImages); // Re-index array
        }

        return $bannerImages;
    }

    public function deleteBanner(Request $request, int $index): RedirectResponse
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $bannerImages = $settings->banner_images ?? [];

        if (isset($bannerImages[$index])) {
            Storage::disk('public')->delete($bannerImages[$index]);
            unset($bannerImages[$index]);
            $bannerImages = array_values($bannerImages);
            $settings->update(['banner_images' => $bannerImages]);
        }

        return redirect()->route('admin.settings.index', ['tab' => 'general'])
            ->with('success', 'Banner image deleted successfully.');
    }

    /**
     * @return list<array{label: string, url: string, image?: string}>
     */
    protected function mergeTrustStripItemsFromRequest(Request $request, WebsiteSetting $settings): array
    {
        $existing = $settings->trust_strip_items ?? [];
        $oldImages = collect(is_array($existing) ? $existing : [])->pluck('image')->filter()->all();

        $rows = $request->input('trust_strip', []);
        if (! is_array($rows)) {
            $rows = [];
        }

        $items = [];
        foreach ($rows as $i => $row) {
            if (! is_array($row)) {
                continue;
            }
            $label = trim((string) ($row['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            if (count($items) >= 8) {
                break;
            }
            $item = [
                'label' => $label,
                'url' => trim((string) ($row['url'] ?? '')),
            ];
            $file = $request->file("trust_strip.$i.image");
            if ($file && $file->isValid()) {
                if (! empty($row['image_keep'])) {
                    Storage::disk('public')->delete($row['image_keep']);
                }
                $item['image'] = $file->store('website/trust', 'public');
            } elseif (! empty($row['image_keep'])) {
                $item['image'] = $row['image_keep'];
            }
            $items[] = $item;
        }

        $newImages = collect($items)->pluck('image')->filter()->all();
        foreach ($oldImages as $path) {
            if ($path && ! in_array($path, $newImages, true)) {
                Storage::disk('public')->delete($path);
            }
        }

        return $items;
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    protected function mergeAboutWhyCardsFromRequest(Request $request): array
    {
        $rows = $request->input('about_why', []);
        if (! is_array($rows)) {
            return [];
        }
        $items = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $items[] = [
                'title' => $title,
                'body' => trim((string) ($row['body'] ?? '')),
            ];
            if (count($items) >= 6) {
                break;
            }
        }

        return $items;
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    protected function mergeHomeHowStepsFromRequest(Request $request): array
    {
        $rows = $request->input('home_how', []);
        if (! is_array($rows)) {
            return [];
        }
        $items = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $items[] = [
                'title' => $title,
                'body' => trim((string) ($row['body'] ?? '')),
            ];
            if (count($items) >= 3) {
                break;
            }
        }

        return $items;
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    protected function mergeHomeWhyCardsFromRequest(Request $request): array
    {
        $rows = $request->input('home_why', []);
        if (! is_array($rows)) {
            return [];
        }
        $items = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $items[] = [
                'title' => $title,
                'body' => trim((string) ($row['body'] ?? '')),
            ];
            if (count($items) >= 4) {
                break;
            }
        }

        return $items;
    }
}
