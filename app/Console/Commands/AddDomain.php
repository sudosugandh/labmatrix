<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Lab;
use Illuminate\Console\Command;

class AddDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:add {domain} {--lab-id= : Lab ID to map the domain to} {--lab-name= : Lab name (will create if doesn\'t exist)} {--inactive : Mark domain as inactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new domain and map it to a lab';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = $this->argument('domain');
        $labId = $this->option('lab-id');
        $labName = $this->option('lab-name');
        $isActive = !$this->option('inactive');

        // Validate domain
        if (empty($domain)) {
            $this->error('Domain is required!');
            return 1;
        }

        // Check if domain already exists
        $existingDomain = Domain::where('domain', $domain)->first();
        if ($existingDomain) {
            $this->warn("Domain '{$domain}' already exists!");
            $this->info("Current mapping: Lab ID {$existingDomain->lab_id} ({$existingDomain->lab->name})");
            
            if (!$this->confirm('Do you want to update it?')) {
                return 0;
            }
        }

        // Get or create lab
        $lab = null;
        if ($labId) {
            $lab = Lab::find($labId);
            if (!$lab) {
                $this->error("Lab with ID {$labId} not found!");
                return 1;
            }
        } elseif ($labName) {
            $lab = Lab::firstOrCreate(
                ['name' => $labName],
                [
                    'email' => "info@{$domain}",
                    'phone' => '+1234567890',
                    'address' => '',
                    'level' => 'large',
                ]
            );
            $this->info("Created/Found Lab: {$lab->name} (ID: {$lab->id})");
        } else {
            // Show available labs and let user choose
            $labs = Lab::all();
            if ($labs->isEmpty()) {
                $this->error('No labs found. Please create a lab first or use --lab-name option.');
                return 1;
            }

            $this->info('Available Labs:');
            $labChoices = [];
            foreach ($labs as $index => $labOption) {
                $labChoices[$index + 1] = $labOption->id;
                $this->line("  [{$index + 1}] {$labOption->name} (ID: {$labOption->id})");
            }

            $choice = $this->ask('Select lab number', 1);
            $labId = $labChoices[$choice] ?? null;
            
            if (!$labId) {
                $this->error('Invalid selection!');
                return 1;
            }

            $lab = Lab::find($labId);
        }

        // Create or update domain
        Domain::updateOrCreate(
            ['domain' => $domain],
            [
                'lab_id' => $lab->id,
                'is_active' => $isActive,
            ]
        );

        $this->info("✓ Domain '{$domain}' mapped to Lab: {$lab->name} (ID: {$lab->id})");
        $this->info("Status: " . ($isActive ? 'Active' : 'Inactive'));

        // Also create domain with port if it doesn't have one
        $hostWithoutPort = explode(':', $domain)[0];
        if ($domain !== $hostWithoutPort) {
            // Domain has port, also create without port
            Domain::updateOrCreate(
                ['domain' => $hostWithoutPort],
                [
                    'lab_id' => $lab->id,
                    'is_active' => $isActive,
                ]
            );
            $this->info("✓ Also created mapping for '{$hostWithoutPort}'");
        }

        return 0;
    }
}
