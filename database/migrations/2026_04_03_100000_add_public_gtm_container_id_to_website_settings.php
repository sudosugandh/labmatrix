<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('website_settings', 'public_gtm_container_id')) {
                $table->string('public_gtm_container_id', 24)->nullable()->after('analytics_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'public_gtm_container_id')) {
                $table->dropColumn('public_gtm_container_id');
            }
        });
    }
};
