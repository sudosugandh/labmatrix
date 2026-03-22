<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'show_faq_section')) {
                $table->boolean('show_faq_section')->default(true)->after('testimonials_subtitle');
            }
            if (!Schema::hasColumn('website_settings', 'faq_heading')) {
                $table->string('faq_heading')->nullable()->after('show_faq_section');
            }
            if (!Schema::hasColumn('website_settings', 'faq_subtitle')) {
                $table->string('faq_subtitle')->nullable()->after('faq_heading');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['show_faq_section', 'faq_heading', 'faq_subtitle']);
        });
    }
};
