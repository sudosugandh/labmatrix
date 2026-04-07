<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            LabSeeder::class,
            CmsSeeder::class,
            LabDataSeeder::class,
            ReportTemplateSeeder::class,
            B2BPortalExampleSeeder::class,
        ]);

        // Homepage vendor demo (trust strip, CTAs, spotlight, dated promos):
        // php artisan db:seed --class=VendorLandingDemoSeeder
    }
}
