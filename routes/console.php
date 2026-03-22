<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Generate scheduled reports every hour
Schedule::command('reports:generate-scheduled')->hourly();

// Schedule appointment reminders (run daily at 9 AM)
Schedule::command('appointments:send-reminders')
    ->dailyAt('09:00')
    ->timezone('Asia/Kolkata')
    ->description('Send appointment reminders to patients');

// Schedule payment reminders (run daily at 10 AM)
Schedule::command('payments:send-reminders')
    ->dailyAt('10:00')
    ->timezone('Asia/Kolkata')
    ->description('Send payment reminders for pending bills');
