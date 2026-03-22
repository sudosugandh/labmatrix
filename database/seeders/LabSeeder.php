<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Domain;
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
                'email' => 'test@healthmatrix.local',
                'phone' => '+1234567890',
                'address' => '123 Test Street, Test City, TC 12345',
                'level' => 'large',
                'domains' => ['localhost', '127.0.0.1'],
            ],
            [
                'name' => 'Apollo Diagnostic Center',
                'email' => 'info@appolo.local.com',
                'phone' => '+1234567890',
                'address' => 'Apollo Address',
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
