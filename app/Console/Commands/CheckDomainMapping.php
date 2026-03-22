<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Lab;
use Illuminate\Console\Command;

class CheckDomainMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:check {domain?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check domain to lab mapping';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = $this->argument('domain');

        if ($domain) {
            $this->checkSpecificDomain($domain);
        } else {
            $this->listAllDomains();
        }

        return 0;
    }

    protected function checkSpecificDomain(string $domain)
    {
        $domainRecord = Domain::where('domain', $domain)
            ->orWhere('domain', explode(':', $domain)[0])
            ->first();

        if (!$domainRecord) {
            $this->error("Domain '{$domain}' not found in database!");
            $this->info("\nTo add this domain, run:");
            $this->info("php artisan domain:add {$domain} --lab-id=<lab_id>");
            return;
        }

        $lab = $domainRecord->lab;

        $this->info("Domain: {$domainRecord->domain}");
        $this->info("Status: " . ($domainRecord->is_active ? 'Active' : 'Inactive'));
        $this->info("Lab ID: {$domainRecord->lab_id}");
        $this->info("Lab Name: {$lab->name}");
        $this->info("Lab Email: {$lab->email}");
        $this->info("Lab Level: {$lab->level}");
    }

    protected function listAllDomains()
    {
        $domains = Domain::with('lab')->get();

        if ($domains->isEmpty()) {
            $this->warn('No domains found in database.');
            $this->info('Run: php artisan db:seed --class=DomainSeeder');
            return;
        }

        $this->info('Domain to Lab Mapping:');
        $this->newLine();

        $headers = ['Domain', 'Status', 'Lab ID', 'Lab Name', 'Lab Email'];
        $rows = [];

        foreach ($domains as $domain) {
            $rows[] = [
                $domain->domain,
                $domain->is_active ? '✓ Active' : '✗ Inactive',
                $domain->lab_id,
                $domain->lab->name ?? 'N/A',
                $domain->lab->email ?? 'N/A',
            ];
        }

        $this->table($headers, $rows);
    }
}
