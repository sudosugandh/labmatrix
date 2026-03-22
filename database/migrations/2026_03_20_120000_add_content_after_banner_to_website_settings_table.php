<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('website_settings', 'content_after_banner')) {
            return;
        }
        Schema::table('website_settings', function (Blueprint $table) {
            $table->longText('content_after_banner')->nullable()->after('footer_content');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('content_after_banner');
        });
    }
};
