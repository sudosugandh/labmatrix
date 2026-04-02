<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Lab;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default labs for development
        $labs = [
            [
                'name' => 'HealthMatrix Test Lab',
                'email' => 'care@healthmatrix.local',
                'phone' => '+91 98765 43210',
                'address' => '42, Ring Road, Rajouri Garden, New Delhi 110027, India',
                'level' => 'large',
                'domains' => ['localhost', '127.0.0.1'],
            ],
            [
                'name' => 'Apollo Diagnostic Center',
                'email' => 'info@appolo.local.com',
                'phone' => '+91 11 4123 4567',
                'address' => 'Plot 12, Sector 18, Noida, Uttar Pradesh 201301, India',
                'level' => 'large',
                'domains' => ['appolo.local.com', 'appolo.local.com:8000'],
            ],
        ];

        foreach ($labs as $labData) {
            $domains = $labData['domains'];
            unset($labData['domains']);

            $lab = Lab::firstOrCreate(
                ['name' => $labData['name']],
                $labData
            );

            foreach ($domains as $domainName) {
                Domain::firstOrCreate(
                    ['domain' => $domainName],
                    [
                        'lab_id' => $lab->id,
                        'is_active' => true,
                    ]
                );
            }

            $this->command->info("Created/Updated Lab: {$lab->name} (ID: {$lab->id})");
        }
    }
}
