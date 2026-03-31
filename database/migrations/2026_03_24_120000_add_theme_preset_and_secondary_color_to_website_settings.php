<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('theme_preset', 32)->default('violet')->after('primary_color');
            $table->string('secondary_color', 7)->nullable()->after('theme_preset');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['theme_preset', 'secondary_color']);
        });
    }
};
