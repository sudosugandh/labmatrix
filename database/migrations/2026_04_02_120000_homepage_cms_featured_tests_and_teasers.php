<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Use text (not varchar) for new string fields: website_settings is wide and MySQL's
        // ~65KB row size limit counts inline VARCHAR length; TEXT/BLOB store off-row.
        Schema::table('website_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('website_settings', 'home_how_heading')) {
                $table->text('home_how_heading')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_how_subtitle')) {
                $table->text('home_how_subtitle')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_how_steps')) {
                $table->json('home_how_steps')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_why_heading')) {
                $table->text('home_why_heading')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_why_subtitle')) {
                $table->text('home_why_subtitle')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_why_cards')) {
                $table->json('home_why_cards')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_show_stats_strip')) {
                $table->boolean('home_show_stats_strip')->default(false);
            }
            if (! Schema::hasColumn('website_settings', 'home_map_url')) {
                $table->text('home_map_url')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'home_show_packages_teaser')) {
                $table->boolean('home_show_packages_teaser')->default(false);
            }
        });

        Schema::table('tests', function (Blueprint $table) {
            if (! Schema::hasColumn('tests', 'featured_on_homepage')) {
                $table->boolean('featured_on_homepage')->default(false);
            }
            if (! Schema::hasColumn('tests', 'homepage_sort_order')) {
                $table->unsignedInteger('homepage_sort_order')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            foreach ([
                'home_how_heading', 'home_how_subtitle', 'home_how_steps',
                'home_why_heading', 'home_why_subtitle', 'home_why_cards',
                'home_show_stats_strip', 'home_map_url', 'home_show_packages_teaser',
            ] as $col) {
                if (Schema::hasColumn('website_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('tests', function (Blueprint $table) {
            if (Schema::hasColumn('tests', 'featured_on_homepage')) {
                $table->dropColumn('featured_on_homepage');
            }
            if (Schema::hasColumn('tests', 'homepage_sort_order')) {
                $table->dropColumn('homepage_sort_order');
            }
        });
    }
};
