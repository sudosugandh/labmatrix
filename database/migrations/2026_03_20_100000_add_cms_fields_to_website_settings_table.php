<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('website_settings', 'header_content')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->longText('header_content')->nullable()->after('footer_text');
            });
        }
        if (!Schema::hasColumn('website_settings', 'footer_content')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->longText('footer_content')->nullable()->after('header_content');
            });
        }
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['header_content', 'footer_content']);
        });
    }
};
