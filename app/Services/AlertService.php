<?php

namespace App\Services;

use App\Models\WebsiteSetting;
use App\Models\Patient;
use App\Models\User;
use App\Mail\TestResultReady;
use App\Mail\AppointmentReminder;
use App\Mail\PaymentReminder;
use App\Mail\InventoryAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class AlertService
{
    protected $settings;
    protected $whatsappService;
    protected $emailEnabled;
    protected $smsEnabled;

    public function __construct()
    {
        $this->settings = WebsiteSetting::getForCurrentLab();
        $this->whatsappService = app(WhatsAppService::class);
        
        // Check if alerts are enabled
        $this->emailEnabled = $this->settings->alerts_email_enabled ?? true;
        $this->smsEnabled = $this->settings->alerts_sms_enabled ?? true;
    }

    /**
     * Send test result ready notification
     */
    public function sendTestResultReady($testOrder, $report = null): bool
    {
        if (!$testOrder->patient) {
            return false;
        }

        $sent = false;

        // Send Email
        if ($this->emailEnabled && $testOrder->patient->email) {
            try {
                Mail::to($testOrder->patient->email)->send(new TestResultReady($testOrder, $report));
                $sent = true;
                Log::info("Test result email sent to {$testOrder->patient->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send test result email: " . $e->getMessage());
            }
        }

        // Send SMS/WhatsApp
        if ($this->smsEnabled && $testOrder->patient->phone) {
            try {
                $message = "Your test report for {$testOrder->test->name} is ready. ";
                $message .= "View it at: " . route('public.reports.search');
                
                $this->whatsappService->sendMessage(
                    $testOrder->patient->phone,
                    $message
                );
                $sent = true;
            } catch (\Exception $e) {
                Log::error("Failed to send test result SMS: " . $e->getMessage());
            }
        }

        return $sent;
    }

    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder($appointment, $hoursBefore = 24): bool
    {
        if (!$appointment->patient) {
            return false;
        }

        $sent = false;

        // Send Email
        if ($this->emailEnabled && $appointment->patient->email) {
            try {
                Mail::to($appointment->patient->email)->send(new AppointmentReminder($appointment));
                $sent = true;
                Log::info("Appointment reminder email sent to {$appointment->patient->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send appointment reminder email: " . $e->getMessage());
            }
        }

        // Send SMS/WhatsApp
        if ($this->smsEnabled && $appointment->patient->phone) {
            try {
                $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y');
                $message = "Reminder: You have an appointment on {$appointmentDate} at {$appointment->appointment_time}. ";
                $message .= "View details: " . route('public.appointments.show', $appointment);
                
                $this->whatsappService->sendMessage(
                    $appointment->patient->phone,
                    $message
                );
                $sent = true;
            } catch (\Exception $e) {
                Log::error("Failed to send appointment reminder SMS: " . $e->getMessage());
            }
        }

        return $sent;
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder($bill): bool
    {
        if (!$bill->patient) {
            return false;
        }

        $sent = false;

        // Send Email
        if ($this->emailEnabled && $bill->patient->email) {
            try {
                Mail::to($bill->patient->email)->send(new PaymentReminder($bill));
                $sent = true;
                Log::info("Payment reminder email sent to {$bill->patient->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send payment reminder email: " . $e->getMessage());
            }
        }

        // Send SMS/WhatsApp
        if ($this->smsEnabled && $bill->patient->phone) {
            try {
                $message = "Payment Reminder: You have a pending bill of ₹{$bill->total_amount} (Bill #{$bill->bill_number}). ";
                $message .= "Please make payment at your earliest convenience.";
                
                $this->whatsappService->sendMessage(
                    $bill->patient->phone,
                    $message
                );
                $sent = true;
            } catch (\Exception $e) {
                Log::error("Failed to send payment reminder SMS: " . $e->getMessage());
            }
        }

        return $sent;
    }

    /**
     * Send inventory alert to admins
     */
    public function sendInventoryAlert($item, $alertType): bool
    {
        // Get all admin users for the lab
        $admins = User::where('lab_id', currentLabId())
            ->where('role', 'admin')
            ->get();

        if ($admins->isEmpty()) {
            return false;
        }

        $sent = false;

        foreach ($admins as $admin) {
            // Send Email
            if ($this->emailEnabled && $admin->email) {
                try {
                    Mail::to($admin->email)->send(new InventoryAlert($item, $alertType));
                    $sent = true;
                } catch (\Exception $e) {
                    Log::error("Failed to send inventory alert email: " . $e->getMessage());
                }
            }
        }

        // Send SMS to primary admin (first admin)
        if ($this->smsEnabled && $admins->first() && $this->settings->contact_phone) {
            try {
                $alertMessages = [
                    'low_stock' => "⚠️ Low Stock Alert: {$item->name} is running low. Current: {$item->current_stock} {$item->unit}, Minimum: {$item->minimum_stock} {$item->unit}",
                    'out_of_stock' => "🚨 Out of Stock Alert: {$item->name} is out of stock. Please reorder immediately.",
                    'expiring_soon' => "⏰ Expiring Soon: {$item->name} expires on {$item->expiry_date->format('M d, Y')}. Current stock: {$item->current_stock} {$item->unit}",
                    'expired' => "❌ Expired Item: {$item->name} expired on {$item->expiry_date->format('M d, Y')}. Please remove from inventory.",
                ];

                $message = $alertMessages[$alertType] ?? "Inventory Alert: {$item->name}";
                
                $this->whatsappService->sendMessage(
                    $this->settings->contact_phone,
                    $message
                );
                $sent = true;
            } catch (\Exception $e) {
                Log::error("Failed to send inventory alert SMS: " . $e->getMessage());
            }
        }

        return $sent;
    }

    /**
     * Send appointment booked confirmation
     */
    public function sendAppointmentBooked($appointment): bool
    {
        if (!$appointment->patient) {
            return false;
        }

        $sent = false;

        // Send Email
        if ($this->emailEnabled && $appointment->patient->email) {
            try {
                Mail::to($appointment->patient->email)->send(new \App\Mail\AppointmentBooked($appointment));
                $sent = true;
            } catch (\Exception $e) {
                Log::error("Failed to send appointment booked email: " . $e->getMessage());
            }
        }

        // SMS is already handled by WhatsAppService in PublicAppointmentController
        // So we don't duplicate here

        return $sent;
    }

    /**
     * Send custom notification
     */
    public function sendCustomNotification($recipient, $subject, $message, $type = 'email'): bool
    {
        try {
            if ($type === 'email' && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                Mail::raw($message, function ($mail) use ($recipient, $subject) {
                    $mail->to($recipient)->subject($subject);
                });
                return true;
            } elseif ($type === 'sms') {
                $this->whatsappService->sendMessage($recipient, $message);
                return true;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send custom notification: " . $e->getMessage());
        }

        return false;
    }
}
