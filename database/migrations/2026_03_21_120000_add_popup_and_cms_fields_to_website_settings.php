<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'home_collection_popup_enabled')) {
                $table->boolean('home_collection_popup_enabled')->default(true)->after('faq_subtitle');
            }
            if (!Schema::hasColumn('website_settings', 'home_collection_popup_delay_seconds')) {
                $table->unsignedTinyInteger('home_collection_popup_delay_seconds')->default(2)->after('home_collection_popup_enabled');
            }
            if (!Schema::hasColumn('website_settings', 'home_collection_popup_frequency')) {
                $table->string('home_collection_popup_frequency', 20)->default('session')->after('home_collection_popup_delay_seconds');
            }
            // Announcement bar
            if (!Schema::hasColumn('website_settings', 'announcement_enabled')) {
                $table->boolean('announcement_enabled')->default(false)->after('home_collection_popup_frequency');
            }
            if (!Schema::hasColumn('website_settings', 'announcement_text')) {
                $table->string('announcement_text')->nullable()->after('announcement_enabled');
            }
            if (!Schema::hasColumn('website_settings', 'announcement_link')) {
                $table->string('announcement_link')->nullable()->after('announcement_text');
            }
            // SEO
            if (!Schema::hasColumn('website_settings', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('announcement_link');
            }
            if (!Schema::hasColumn('website_settings', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('website_settings', 'og_image')) {
                $table->string('og_image')->nullable()->after('meta_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $cols = [
                'home_collection_popup_enabled', 'home_collection_popup_delay_seconds', 'home_collection_popup_frequency',
                'announcement_enabled', 'announcement_text', 'announcement_link',
                'meta_title', 'meta_description', 'og_image',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('website_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
