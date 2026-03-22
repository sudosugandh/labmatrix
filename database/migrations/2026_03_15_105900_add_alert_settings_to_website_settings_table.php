<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // Alert Settings
            $table->boolean('alerts_email_enabled')->default(true)->after('notifications_enabled');
            $table->boolean('alerts_sms_enabled')->default(true)->after('alerts_email_enabled');
            
            // Alert Preferences
            $table->boolean('alert_test_results')->default(true)->after('alerts_sms_enabled');
            $table->boolean('alert_appointments')->default(true)->after('alert_test_results');
            $table->boolean('alert_payments')->default(true)->after('alert_appointments');
            $table->boolean('alert_inventory')->default(true)->after('alert_payments');
            
            // Appointment Reminder Settings
            $table->integer('appointment_reminder_hours')->default(24)->after('alert_inventory');
            $table->boolean('appointment_reminder_enabled')->default(true)->after('appointment_reminder_hours');
            
            // Payment Reminder Settings
            $table->integer('payment_reminder_days')->default(7)->after('appointment_reminder_enabled');
            $table->boolean('payment_reminder_enabled')->default(true)->after('payment_reminder_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'alerts_email_enabled',
                'alerts_sms_enabled',
                'alert_test_results',
                'alert_appointments',
                'alert_payments',
                'alert_inventory',
                'appointment_reminder_hours',
                'appointment_reminder_enabled',
                'payment_reminder_days',
                'payment_reminder_enabled',
            ]);
        });
    }
};
