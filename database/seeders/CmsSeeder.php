<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Seed lab navigation, content after banner (TinyMCE), and CMS pages (About, Contact).
     */
    public function run(): void
    {
        $labs = Lab::all();
        if ($labs->isEmpty()) {
            $this->command->warn('No labs found. Run LabSeeder first.');
            return;
        }

        foreach ($labs as $lab) {
            $this->seedLabCms($lab);
        }

        $this->command->info('CMS seeding completed.');
    }

    protected function seedLabCms(Lab $lab): void
    {
        $labId = $lab->id;
        $labName = $lab->name;

        // Website settings: content after banner (TinyMCE – shown right after banner)
        $settings = WebsiteSetting::firstOrCreate(
            ['lab_id' => $labId],
            ['brand_name' => $labName]
        );
        $defaultContentAfterBanner = '<p>' . e($labName) . ' is committed to providing accurate and timely diagnostic services to help you maintain your health and well-being.</p>
<p>We offer a wide range of tests and health packages. Book an appointment online or visit us to learn more.</p>';
        if (empty($settings->content_after_banner)) {
            $settings->update(['content_after_banner' => $defaultContentAfterBanner]);
            $this->command->info("  [{$labName}] Set content_after_banner (TinyMCE).");
        }

        // Main menu + navigation items
        $menu = Menu::firstOrCreate(
            ['lab_id' => $labId, 'name' => 'Main Menu'],
            []
        );
        $items = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'About Us', 'url' => '/about'],
            ['title' => 'Contact', 'url' => '/contact'],
            ['title' => 'Packages', 'url' => '/packages'],
            ['title' => 'Book Appointment', 'url' => '/book-appointment'],
            ['title' => 'Reports', 'url' => '/patient-reports'],
        ];
        foreach ($items as $order => $row) {
            MenuItem::firstOrCreate(
                [
                    'menu_id' => $menu->id,
                    'url' => $row['url'],
                ],
                [
                    'title' => $row['title'],
                    'order' => $order,
                ]
            );
        }
        $this->command->info("  [{$labName}] Main menu and navigation items seeded.");

        // About Us page (TinyMCE content – editable in admin)
        $aboutContent = '<p>' . e($labName) . ' is committed to providing accurate and timely diagnostic services to help you maintain your health and well-being.</p>
<p>Our team uses modern equipment and follows strict quality standards to deliver reliable results. We offer a range of tests and health packages to suit your needs.</p>
<p>Visit our <a href="/packages">Packages</a> page for offers, or <a href="/book-appointment">book an appointment</a> online.</p>';
        Page::updateOrCreate(
            ['lab_id' => $labId, 'slug' => 'about'],
            [
                'title' => 'About Us',
                'content' => $aboutContent,
                'meta_title' => 'About Us - ' . $labName,
                'meta_description' => $labName . ' - Your trusted diagnostic center.',
                'status' => 'published',
            ]
        );
        $this->command->info("  [{$labName}] About Us page seeded.");

        // Contact Us page (TinyMCE; phone/email/address shown below from Settings → Contact)
        $contactContent = '<p>Get in touch with us for appointments, reports, or any queries. Our contact details are listed below.</p>
<p>You can <a href="/book-appointment">book an appointment online</a> or check <a href="/patient-reports">your reports</a> here.</p>';
        Page::updateOrCreate(
            ['lab_id' => $labId, 'slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'content' => $contactContent,
                'meta_title' => 'Contact Us - ' . $labName,
                'meta_description' => 'Contact ' . $labName . ' for appointments and enquiries.',
                'status' => 'published',
            ]
        );
        $this->command->info("  [{$labName}] Contact Us page seeded.");
    }
}
