<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Services\AlertService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders for pending bills';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending payment reminders...');

        $settings = \App\Models\WebsiteSetting::getForCurrentLab();
        
        // Check if payment reminders are enabled
        if (!($settings->payment_reminder_enabled ?? true)) {
            $this->info('Payment reminders are disabled in settings.');
            return Command::SUCCESS;
        }
        
        $reminderDays = $settings->payment_reminder_days ?? 7;
        
        // Get bills that are pending and due within reminder days
        $bills = Bill::where('lab_id', currentLabId())
            ->where('payment_status', 'pending')
            ->where(function($query) use ($reminderDays) {
                $query->whereNull('due_date')
                      ->orWhere('due_date', '<=', now()->addDays($reminderDays))
                      ->orWhere('due_date', '<=', now()); // Overdue bills
            })
            ->with('patient')
            ->get();

        $sentCount = 0;
        $alertService = app(AlertService::class);

        foreach ($bills as $bill) {
            if (!$bill->patient) {
                continue;
            }

            try {
                if ($alertService->sendPaymentReminder($bill)) {
                    $this->info("Payment reminder sent for Bill #{$bill->bill_number} to {$bill->patient->name}");
                    $sentCount++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to send payment reminder for Bill #{$bill->bill_number}: " . $e->getMessage());
            }
        }

        $this->info("Payment reminders sent: {$sentCount} total");
        
        return Command::SUCCESS;
    }
}
