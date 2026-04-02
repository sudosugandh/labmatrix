<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('website_settings', 'footer_tagline')) {
                $table->string('footer_tagline', 500)->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'pre_footer_cta_enabled')) {
                $table->boolean('pre_footer_cta_enabled')->default(true);
            }
            if (! Schema::hasColumn('website_settings', 'pre_footer_heading')) {
                $table->string('pre_footer_heading', 255)->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'pre_footer_subtitle')) {
                $table->string('pre_footer_subtitle', 500)->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'footer_show_trust_badges')) {
                $table->boolean('footer_show_trust_badges')->default(true);
            }
            if (! Schema::hasColumn('website_settings', 'footer_trust_nabl')) {
                $table->boolean('footer_trust_nabl')->default(true);
            }
            if (! Schema::hasColumn('website_settings', 'footer_trust_iso')) {
                $table->boolean('footer_trust_iso')->default(true);
            }
            if (! Schema::hasColumn('website_settings', 'footer_trust_patients')) {
                $table->boolean('footer_trust_patients')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            foreach ([
                'footer_tagline',
                'pre_footer_cta_enabled',
                'pre_footer_heading',
                'pre_footer_subtitle',
                'footer_show_trust_badges',
                'footer_trust_nabl',
                'footer_trust_iso',
                'footer_trust_patients',
            ] as $col) {
                if (Schema::hasColumn('website_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
