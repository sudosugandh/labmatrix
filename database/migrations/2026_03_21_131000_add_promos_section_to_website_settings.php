<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'show_promos_section')) {
                $table->boolean('show_promos_section')->default(true)->after('og_image');
            }
            if (!Schema::hasColumn('website_settings', 'promos_heading')) {
                $table->string('promos_heading')->nullable()->after('show_promos_section');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['show_promos_section', 'promos_heading']);
        });
    }
};
