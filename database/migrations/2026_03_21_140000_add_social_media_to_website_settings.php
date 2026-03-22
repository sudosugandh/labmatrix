<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('promos_heading');
            }
            if (!Schema::hasColumn('website_settings', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('website_settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('website_settings', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('website_settings', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('linkedin_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url', 'youtube_url']);
        });
    }
};
