<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Promo;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

/**
 * Demo data for vendor-facing homepage features (trust strip, hero CTAs,
 * “today’s packages” strip, spotlight, promos with valid_from / valid_until).
 *
 * Run after LabSeeder (and migrate):
 *   php artisan db:seed --class=VendorLandingDemoSeeder
 *
 * Then open your lab’s public homepage (e.g. http://127.0.0.1:8000 for HealthMatrix Test Lab).
 * WhatsApp links on the public site use the lab phone from Settings → Branding when the hero tertiary URL is empty.
 */
class VendorLandingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $labs = Lab::all();
        if ($labs->isEmpty()) {
            $this->command->warn('VendorLandingDemoSeeder: No labs found. Run LabSeeder first.');

            return;
        }

        foreach ($labs as $lab) {
            $this->seedWebsiteSettings($lab);
            $this->seedPromos($lab->id);
            $this->command->info("  [{$lab->name}] Vendor landing demo applied.");
        }

        $this->command->newLine();
        $this->command->info('Done. Visit the public homepage for each lab to preview.');
        $this->command->line('  Tip: set phone under Settings → Branding for Call / WhatsApp; optional custom wa.me under Hero buttons.');
    }

    protected function seedWebsiteSettings(Lab $lab): void
    {
        $settings = WebsiteSetting::firstOrCreate(
            ['lab_id' => $lab->id],
            ['brand_name' => $lab->name]
        );

        $settings->update([
            'trust_strip_enabled' => true,
            'trust_strip_items' => [
                ['label' => 'NABL accredited', 'url' => ''],
                ['label' => 'ISO certified lab', 'url' => ''],
                ['label' => '15+ years trusted', 'url' => ''],
                ['label' => 'Home collection', 'url' => '/book-appointment'],
            ],
            'hero_cta_primary_label' => 'Book a test',
            'hero_cta_primary_url' => '',
            'hero_cta_secondary_label' => 'Patient reports',
            'hero_cta_secondary_url' => '',
            'hero_cta_tertiary_label' => 'WhatsApp us',
            'hero_cta_tertiary_url' => '',
            'phone' => $lab->phone ?: $settings->phone,
            'email' => $lab->email ?: $settings->email,
            'address' => $lab->address ?: $settings->address,
            'promos_today_strip_enabled' => true,
            'promos_today_heading' => 'Popular health packages',
            'show_promos_section' => false,
            'promos_today_subtitle' => 'Offers respect start/end dates — expired promos disappear automatically.',
            'promos_subtitle' => 'Seasonal panels and family bundles. Check validity on each card.',
            'spotlight_enabled' => true,
            'spotlight_name' => 'Dr. '.(str_contains($lab->name, 'Apollo') ? 'Anita Rao' : 'Priya Sharma'),
            'spotlight_credentials' => 'MD Pathology · Lab director',
            'spotlight_tagline' => 'We prioritise accurate results, fast turnaround, and clear reporting for every patient.',
            'spotlight_experience' => '15+ years in clinical pathology & lab leadership',
            'about_team_trust_line' => 'Our team follows CAP-style quality discipline: every run is traceable, every abnormal flag is reviewed, and every patient deserves a clear answer.',
            'about_stat_tests' => '50,000+',
            'about_stat_patients' => '12,000+',
            'about_stat_years' => '15+',
            'about_working_hours' => "Mon–Sat: 8:00 AM – 8:00 PM\nSunday: 9:00 AM – 2:00 PM",
            'contact_working_hours' => "Mon–Sat: 8:00 AM – 8:00 PM\nSunday: 9:00 AM – 2:00 PM",
        ]);
    }

    protected function seedPromos(int $labId): void
    {
        Promo::updateOrCreate(
            ['lab_id' => $labId, 'title' => '[Demo] Full body checkup — 20% off'],
            [
                'short_text' => 'Liver, kidney, lipid profile and CBC. Valid for a limited window — see end date on card.',
                'link' => '/book-appointment',
                'valid_from' => now()->subDays(3)->toDateString(),
                'valid_until' => now()->addMonth()->toDateString(),
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Promo::updateOrCreate(
            ['lab_id' => $labId, 'title' => '[Demo] Thyroid panel — ending soon'],
            [
                'short_text' => 'TSH, T3, T4. Shows “Ends …” badge in the today strip.',
                'link' => '/book-appointment',
                'valid_from' => now()->subWeek()->toDateString(),
                'valid_until' => now()->addDays(7)->toDateString(),
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        Promo::updateOrCreate(
            ['lab_id' => $labId, 'title' => '[Demo] Future offer (not visible yet)'],
            [
                'short_text' => 'This stays hidden until “Valid from” — useful for scheduled campaigns.',
                'link' => '/packages',
                'valid_from' => now()->addDays(14)->toDateString(),
                'valid_until' => now()->addMonths(3)->toDateString(),
                'is_active' => true,
                'sort_order' => 99,
            ]
        );
    }
}
