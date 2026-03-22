<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\WebsiteSetting;

class WhatsAppService
{
    protected $client;
    protected $fromNumber;
    protected $enabled;

    public function __construct()
    {
        $settings = WebsiteSetting::where('lab_id', currentLabId())->first();
        
        $accountSid = $settings->whatsapp_account_sid ?? env('TWILIO_ACCOUNT_SID');
        $authToken = $settings->whatsapp_auth_token ?? env('TWILIO_AUTH_TOKEN');
        $this->fromNumber = $settings->whatsapp_from_number ?? env('TWILIO_WHATSAPP_FROM');
        $this->enabled = $settings->whatsapp_enabled ?? env('WHATSAPP_ENABLED', false);

        if ($this->enabled && $accountSid && $authToken) {
            try {
                $this->client = new Client($accountSid, $authToken);
            } catch (\Exception $e) {
                Log::error('WhatsApp Service initialization failed: ' . $e->getMessage());
                $this->enabled = false;
            }
        }
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage(string $to, string $message): bool
    {
        if (!$this->enabled || !$this->client) {
            Log::info('WhatsApp is disabled or not configured');
            return false;
        }

        // Format phone number (remove + if present, add country code if needed)
        $to = $this->formatPhoneNumber($to);

        try {
            $this->client->messages->create(
                "whatsapp:{$to}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $message
                ]
            );
            
            Log::info("WhatsApp message sent to {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("WhatsApp message failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If phone doesn't start with country code, assume it's Indian (+91)
        if (strlen($phone) == 10) {
            $phone = '91' . $phone;
        }
        
        return $phone;
    }

    /**
     * Send appointment booking confirmation
     */
    public function sendAppointmentBooked($appointment): bool
    {
        $patient = $appointment->patient;
        $phone = $patient->phone;
        
        if (!$phone) {
            return false;
        }

        $date = $appointment->appointment_date->format('l, F d, Y');
        $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
        $time = \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
        $appointmentId = str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
        $labName = currentLab()?->name ?? 'HealthMatrix';
        
        $message = "🎉 *Appointment Confirmed!*\n\n";
        $message .= "Hello {$patient->name},\n\n";
        $message .= "Your appointment has been successfully booked.\n\n";
        $message .= "📅 *Date:* {$date}\n";
        $message .= "⏰ *Time:* {$time}\n";
        $message .= "🆔 *Appointment ID:* #{$appointmentId}\n\n";
        
        if ($appointment->package) {
            $message .= "📦 *Package:* {$appointment->package->name}\n";
        }
        
        $message .= "\n📍 *Lab:* {$labName}\n";
        $message .= "📞 *Contact:* " . (currentLab()?->phone ?? 'N/A') . "\n\n";
        $message .= "Please arrive 10 minutes before your scheduled time.\n\n";
        $message .= "View details: " . route('public.appointments.show', $appointment, absolute: true) . "\n\n";
        $message .= "Thank you! 🙏";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder($appointment): bool
    {
        $patient = $appointment->patient;
        $phone = $patient->phone;
        
        if (!$phone) {
            return false;
        }

        $date = $appointment->appointment_date->format('l, F d, Y');
        $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
        $time = \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
        $appointmentId = str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
        $labName = currentLab()?->name ?? 'HealthMatrix';
        
        $message = "⏰ *Appointment Reminder*\n\n";
        $message .= "Hello {$patient->name},\n\n";
        $message .= "This is a reminder for your upcoming appointment.\n\n";
        $message .= "📅 *Date:* {$date}\n";
        $message .= "⏰ *Time:* {$time}\n";
        $message .= "🆔 *Appointment ID:* #{$appointmentId}\n\n";
        $message .= "📍 *Lab:* {$labName}\n\n";
        $message .= "Please arrive 10 minutes before your scheduled time.\n\n";
        $message .= "If you need to cancel or reschedule, please contact us at least 24 hours in advance.\n\n";
        $message .= "Thank you! 🙏";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send report ready notification
     */
    public function sendReportReady($report): bool
    {
        $patient = $report->patient;
        $phone = $patient->phone;
        
        if (!$phone) {
            return false;
        }

        $testName = $report->testOrder->test->name ?? 'Test Report';
        $labName = currentLab()?->name ?? 'HealthMatrix';
        $reportId = $report->id;
        
        $message = "📄 *Test Report Ready!*\n\n";
        $message .= "Hello {$patient->name},\n\n";
        $message .= "Your test report is now available for download.\n\n";
        $message .= "🧪 *Test:* {$testName}\n";
        $message .= "🆔 *Report ID:* #{$reportId}\n\n";
        $message .= "📍 *Lab:* {$labName}\n\n";
        $message .= "Download your report: " . route('public.reports.download', $reportId, absolute: true) . "\n\n";
        $message .= "Thank you! 🙏";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send appointment cancellation notification
     */
    public function sendAppointmentCancelled($appointment): bool
    {
        $patient = $appointment->patient;
        $phone = $patient->phone;
        
        if (!$phone) {
            return false;
        }

        $date = $appointment->appointment_date->format('l, F d, Y');
        $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
        $time = \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
        $appointmentId = str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
        $labName = currentLab()?->name ?? 'HealthMatrix';
        
        $message = "❌ *Appointment Cancelled*\n\n";
        $message .= "Hello {$patient->name},\n\n";
        $message .= "Your appointment has been cancelled.\n\n";
        $message .= "📅 *Date:* {$date}\n";
        $message .= "⏰ *Time:* {$time}\n";
        $message .= "🆔 *Appointment ID:* #{$appointmentId}\n\n";
        $message .= "📍 *Lab:* {$labName}\n\n";
        $message .= "If you need to reschedule, please book a new appointment.\n\n";
        $message .= "Thank you! 🙏";

        return $this->sendMessage($phone, $message);
    }
}
