<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Lab;
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
                'email' => 'care@healthmatrix.local',
                'phone' => '+91 98765 43210',
                'address' => '42, Ring Road, Rajouri Garden, New Delhi 110027, India',
                'level' => 'large',
            ]
        );

        $apolloLab = Lab::firstOrCreate(
            ['name' => 'Apollo Diagnostic Center'],
            [
                'email' => 'info@appolo.local.com',
                'phone' => '+91 11 4123 4567',
                'address' => 'Plot 12, Sector 18, Noida, Uttar Pradesh 201301, India',
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
