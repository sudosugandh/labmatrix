<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create labs
        $localhostLab = Lab::firstOrCreate(
            ['name' => 'HealthMatrix Test Lab'],
            [
                'email' => 'test@healthmatrix.local',
                'phone' => '+1234567890',
                'address' => '123 Test Street, Test City, TC 12345',
                'level' => 'large',
            ]
        );

        $apolloLab = Lab::firstOrCreate(
            ['name' => 'Apollo Diagnostic Center'],
            [
                'email' => 'info@appolo.local.com',
                'phone' => '+1234567890',
                'address' => 'Apollo Address',
                'level' => 'large',
            ]
        );

        // Create domains for localhost
        Domain::firstOrCreate(
            ['domain' => 'localhost'],
            [
                'lab_id' => $localhostLab->id,
                'is_active' => true,
            ]
        );

        Domain::firstOrCreate(
            ['domain' => '127.0.0.1'],
            [
                'lab_id' => $localhostLab->id,
                'is_active' => true,
            ]
        );

        // Create domains for appolo.local.com
        Domain::firstOrCreate(
            ['domain' => 'appolo.local.com'],
            [
                'lab_id' => $apolloLab->id,
                'is_active' => true,
            ]
        );

        Domain::firstOrCreate(
            ['domain' => 'appolo.local.com:8000'],
            [
                'lab_id' => $apolloLab->id,
                'is_active' => true,
            ]
        );

        $this->command->info('Domains seeded successfully!');
        $this->command->info("Localhost domain mapped to Lab: {$localhostLab->name} (ID: {$localhostLab->id})");
        $this->command->info("Appolo domain mapped to Lab: {$apolloLab->name} (ID: {$apolloLab->id})");
    }
}
