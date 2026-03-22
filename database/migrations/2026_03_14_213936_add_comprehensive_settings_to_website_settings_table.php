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
            // General Settings
            $table->string('brand_name')->nullable()->after('lab_id');
            $table->string('domain')->nullable()->after('brand_name');
            $table->json('banner_images')->nullable()->after('hero_image'); // Store array of banner image paths
            $table->string('favicon')->nullable()->after('logo');
            $table->text('description')->nullable()->after('footer_text');
            
            // Contact Settings
            $table->string('contact_email')->nullable()->after('email');
            $table->string('contact_phone')->nullable()->after('phone');
            
            // Features Settings
            $table->boolean('online_booking_enabled')->default(true)->after('whatsapp_from_number');
            $table->boolean('online_reports_enabled')->default(true)->after('online_booking_enabled');
            $table->boolean('notifications_enabled')->default(true)->after('online_reports_enabled');
            $table->boolean('analytics_enabled')->default(true)->after('notifications_enabled');
            
            // Printer Settings
            $table->string('printer_name')->nullable()->after('analytics_enabled');
            $table->string('printer_ip')->nullable()->after('printer_name');
            $table->integer('printer_port')->nullable()->after('printer_ip');
            $table->string('paper_size')->default('A4')->after('printer_port');
            $table->string('printer_type')->default('thermal')->after('paper_size'); // thermal, inkjet, laser
            
            // Payment Settings
            $table->boolean('payments_enabled')->default(false)->after('printer_type');
            $table->string('payment_gateway')->nullable()->after('payments_enabled'); // stripe, razorpay, paypal
            $table->string('payment_api_key')->nullable()->after('payment_gateway');
            $table->string('payment_api_secret')->nullable()->after('payment_api_key');
            $table->boolean('payment_test_mode')->default(true)->after('payment_api_secret');
            
            // Language Settings
            $table->string('default_language')->default('en')->after('payment_test_mode');
            $table->json('available_languages')->nullable()->after('default_language'); // ['en', 'hi', 'gu']
            
            // Security Settings
            $table->boolean('two_factor_enabled')->default(false)->after('available_languages');
            $table->integer('session_timeout')->default(120)->after('two_factor_enabled'); // minutes
            $table->boolean('ip_whitelist_enabled')->default(false)->after('session_timeout');
            $table->json('ip_whitelist')->nullable()->after('ip_whitelist_enabled');
            $table->boolean('password_policy_enabled')->default(true)->after('ip_whitelist');
            $table->integer('password_min_length')->default(8)->after('password_policy_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'brand_name',
                'domain',
                'banner_images',
                'favicon',
                'description',
                'contact_email',
                'contact_phone',
                'online_booking_enabled',
                'online_reports_enabled',
                'notifications_enabled',
                'analytics_enabled',
                'printer_name',
                'printer_ip',
                'printer_port',
                'paper_size',
                'printer_type',
                'payments_enabled',
                'payment_gateway',
                'payment_api_key',
                'payment_api_secret',
                'payment_test_mode',
                'default_language',
                'available_languages',
                'two_factor_enabled',
                'session_timeout',
                'ip_whitelist_enabled',
                'ip_whitelist',
                'password_policy_enabled',
                'password_min_length',
            ]);
        });
    }
};
