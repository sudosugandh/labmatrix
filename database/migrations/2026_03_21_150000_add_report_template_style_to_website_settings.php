<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'report_template_style')) {
                $table->string('report_template_style', 50)->default('lal_pathlabs')->after('printer_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'report_template_style')) {
                $table->dropColumn('report_template_style');
            }
        });
    }
};
