<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'show_testimonials_section')) {
                $table->boolean('show_testimonials_section')->default(true)->after('services_subtitle');
            }
            if (!Schema::hasColumn('website_settings', 'testimonials_heading')) {
                $table->string('testimonials_heading')->nullable()->after('show_testimonials_section');
            }
            if (!Schema::hasColumn('website_settings', 'testimonials_subtitle')) {
                $table->string('testimonials_subtitle')->nullable()->after('testimonials_heading');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['show_testimonials_section', 'testimonials_heading', 'testimonials_subtitle']);
        });
    }
};
