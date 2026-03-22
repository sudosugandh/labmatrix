<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'show_services_section')) {
                $table->boolean('show_services_section')->default(true)->after('content_after_banner');
            }
            if (!Schema::hasColumn('website_settings', 'services_heading')) {
                $table->string('services_heading')->nullable()->after('show_services_section');
            }
            if (!Schema::hasColumn('website_settings', 'services_subtitle')) {
                $table->string('services_subtitle')->nullable()->after('services_heading');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['show_services_section', 'services_heading', 'services_subtitle']);
        });
    }
};
