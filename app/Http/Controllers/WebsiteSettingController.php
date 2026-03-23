<?php

namespace App\Http\Controllers;

use App\Models\WebsiteSetting;
use App\Models\Lab;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function index(Request $request): View
    {
        $settings = WebsiteSetting::getForCurrentLab();
        $lab = Lab::find(currentLabId());
        $activeTab = $request->get('tab', 'general');

        $mainMenu = null;
        $cmsPages = collect();
        if ($activeTab === 'cms') {
            $mainMenu = Menu::where('lab_id', currentLabId())
                ->where('name', 'Main Menu')
                ->with('items')
                ->first();
            if (!$mainMenu) {
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
        $tab = $request->get('tab', 'general');
        
        $rules = $this->getValidationRules($tab);
        $validated = $request->validate($rules);

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
            $validated['facebook_url'] = $request->input('facebook_url') ?: null;
            $validated['twitter_url'] = $request->input('twitter_url') ?: null;
            $validated['instagram_url'] = $request->input('instagram_url') ?: null;
            $validated['linkedin_url'] = $request->input('linkedin_url') ?: null;
            $validated['youtube_url'] = $request->input('youtube_url') ?: null;
            if ($request->hasFile('og_image')) {
                if ($settings->og_image) {
                    Storage::disk('public')->delete($settings->og_image);
                }
                $validated['og_image'] = $request->file('og_image')->store('website', 'public');
            }
        }

        $settings->update($validated);

        return redirect()->route('admin.settings.index', ['tab' => $tab])
            ->with('success', 'Settings updated successfully.');
    }

    protected function getValidationRules(string $tab): array
    {
        $rules = [];

        switch ($tab) {
            case 'general':
                $rules = [
                    'brand_name' => 'nullable|string|max:255',
                    'domain' => 'nullable|string|max:255',
                    'logo' => 'nullable|image|max:2048',
                    'favicon' => 'nullable|image|max:512',
                    'banner_images.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/svg+xml,video/mp4,video/quicktime,video/x-msvideo,video/webm|max:51200',
                    'description' => 'nullable|string|max:1000',
                ];
                break;

            case 'branding':
                $rules = [
                    'primary_color' => 'nullable|string|max:7',
                    'hero_title' => 'nullable|string|max:255',
                    'hero_subtitle' => 'nullable|string',
                    'hero_image' => 'nullable|image|max:5120',
                    'footer_text' => 'nullable|string',
                ];
                break;

            case 'contact':
                $rules = [
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
                    'og_image' => 'nullable|image|max:2048',
                    'show_promos_section' => 'nullable|boolean',
                    'promos_heading' => 'nullable|string|max:255',
                    'facebook_url' => 'nullable|url|max:500',
                    'twitter_url' => 'nullable|url|max:500',
                    'instagram_url' => 'nullable|url|max:500',
                    'linkedin_url' => 'nullable|url|max:500',
                    'youtube_url' => 'nullable|url|max:500',
                    'banner_images.*' => 'nullable|image|max:5120',
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
}
