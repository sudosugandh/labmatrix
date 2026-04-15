<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Promo;
use App\Models\Test;
use App\Models\TestPackage;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

/**
 * Demo data for the public homepage: CMS “How it works” / “Why choose us”, stats strip,
 * map link, packages teaser, featured tests, trust strip, and sample promos.
 *
 * Requires LabSeeder + LabDataSeeder (tests). Run full seed:
 *   php artisan db:seed
 *
 * Or only this class (after labs + tests exist):
 *   php artisan db:seed --class=HomepageDemoSeeder
 */
class HomepageDemoSeeder extends Seeder
{
    public function run(): void
    {
        $labs = Lab::all();
        if ($labs->isEmpty()) {
            $this->command->warn('HomepageDemoSeeder: No labs. Run LabSeeder first.');

            return;
        }

        foreach ($labs as $lab) {
            $this->seedWebsiteSettings($lab);
            $this->seedFeaturedTests($lab->id);
            $this->seedPackages($lab->id);
            $this->seedPromos($lab->id);
            $this->command->line("  [{$lab->name}] Homepage demo applied.");
        }

        $this->command->newLine();
        $this->command->info('Open the lab public homepage (e.g. http://127.0.0.1:8000 with localhost domain) to preview.');
    }

    protected function seedWebsiteSettings(Lab $lab): void
    {
        $settings = WebsiteSetting::firstOrCreate(
            ['lab_id' => $lab->id],
            ['brand_name' => $lab->name]
        );

        $address = $lab->address ?: 'New Delhi, India';
        $mapQuery = 'https://www.google.com/maps/search/?api=1&query='.urlencode($address);

        $settings->update([
            'home_how_heading' => 'How it works',
            'home_how_subtitle' => 'Book, visit or get home collection, then download your report — it is that simple.',
            'home_how_steps' => [
                ['title' => 'Book online', 'body' => 'Pick tests or a package and choose a slot that works for you.'],
                ['title' => 'Sample collection', 'body' => 'Visit our centre or request a trained phlebotomist at home.'],
                ['title' => 'Reports online', 'body' => 'Get notified and view or download reports securely anytime.'],
            ],
            'home_why_heading' => 'Why choose us',
            'home_why_subtitle' => 'Quality diagnostics with care at every step.',
            'home_why_cards' => [
                ['title' => 'Accurate results', 'body' => 'Validated methods, internal QC, and reports you can trust.'],
                ['title' => 'Fast turnaround', 'body' => 'Same-day or next-day results for many routine tests.'],
                ['title' => 'Home collection', 'body' => 'Trained staff visit you — convenient and hygienic.'],
                ['title' => 'Trusted team', 'body' => 'Experienced pathologists and modern equipment.'],
            ],
            'home_show_stats_strip' => true,
            'home_show_packages_teaser' => true,
            'home_map_url' => $mapQuery,
            'about_stat_tests' => $settings->about_stat_tests ?: '50,000+',
            'about_stat_patients' => $settings->about_stat_patients ?: '12,000+',
            'about_stat_years' => $settings->about_stat_years ?: '15+',
            'trust_strip_enabled' => true,
            'trust_strip_items' => [
                ['label' => 'NABL accredited', 'url' => ''],
                ['label' => 'Home collection', 'url' => '/book-appointment'],
                ['label' => 'Online reports', 'url' => '/patient-reports'],
            ],
            'promos_today_strip_enabled' => true,
            'promos_today_heading' => 'Health offers',
            'promos_today_subtitle' => 'Sample promos — dates apply automatically.',
            'show_promos_section' => false,
        ]);
    }

    protected function seedFeaturedTests(int $labId): void
    {
        $tests = Test::where('lab_id', $labId)->orderBy('id')->get();
        foreach ($tests as $index => $test) {
            $featured = $index < 6;
            $test->update([
                'featured_on_homepage' => $featured,
                'homepage_sort_order' => $featured ? $index + 1 : null,
            ]);
        }
    }

    protected function seedPackages(int $labId): void
    {
        $rows = [
            [
                'name' => 'Executive Health Checkup',
                'description' => 'Liver, kidney, lipid, thyroid, CBC — comprehensive screening in one package.',
                'price' => 3499.00,
                'sort_order' => 1,
            ],
            [
                'name' => 'Diabetes Care Panel',
                'description' => 'Fasting sugar, HbA1c, and kidney markers — ideal for diabetes monitoring.',
                'price' => 1299.00,
                'sort_order' => 2,
            ],
            [
                'name' => 'Thyroid Wellness',
                'description' => 'TSH, T3, T4 — full thyroid function assessment.',
                'price' => 899.00,
                'sort_order' => 3,
            ],
        ];

        foreach ($rows as $row) {
            $price = $row['price'];
            TestPackage::updateOrCreate(
                ['lab_id' => $labId, 'name' => $row['name']],
                [
                    'description' => $row['description'],
                    'price' => $price,
                    'discount' => 0,
                    'final_price' => $price,
                    'is_active' => true,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }
    }

    protected function seedPromos(int $labId): void
    {
        Promo::updateOrCreate(
            ['lab_id' => $labId, 'title' => '[Homepage demo] Full body checkup — 15% off'],
            [
                'short_text' => 'Liver, kidney, lipid profile and more. Book before the offer ends.',
                'link' => '/book-appointment',
                'valid_from' => now()->subDays(2)->toDateString(),
                'valid_until' => now()->addWeeks(3)->toDateString(),
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Promo::updateOrCreate(
            ['lab_id' => $labId, 'title' => '[Homepage demo] Lipid profile special'],
            [
                'short_text' => 'Heart health screening at a reduced rate this month.',
                'link' => '/book-appointment',
                'valid_from' => now()->subDay()->toDateString(),
                'valid_until' => now()->addDays(14)->toDateString(),
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }
}
