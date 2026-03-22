<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders 24 hours before scheduled appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending appointment reminders...');

        // Get appointments scheduled for tomorrow
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        $appointments = Appointment::where('appointment_date', $tomorrow)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->with(['patient', 'package'])
            ->get();

        $sentCount = 0;
        $alertService = app(\App\Services\AlertService::class);
        $settings = \App\Models\WebsiteSetting::getForCurrentLab();
        
        // Check if appointment reminders are enabled
        if (!($settings->appointment_reminder_enabled ?? true)) {
            $this->info('Appointment reminders are disabled in settings.');
            return Command::SUCCESS;
        }
        
        $reminderHours = $settings->appointment_reminder_hours ?? 24;

        foreach ($appointments as $appointment) {
            try {
                if ($alertService->sendAppointmentReminder($appointment, $reminderHours)) {
                    $this->info("Reminder sent to {$appointment->patient->name}");
                    $sentCount++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to {$appointment->patient->name}: " . $e->getMessage());
            }
        }

        $this->info("Reminders sent: {$sentCount} total");
        
        return Command::SUCCESS;
    }
}
