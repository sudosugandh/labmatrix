<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->boolean('whatsapp_enabled')->default(false)->after('footer_text');
            $table->string('whatsapp_account_sid')->nullable()->after('whatsapp_enabled');
            $table->string('whatsapp_auth_token')->nullable()->after('whatsapp_account_sid');
            $table->string('whatsapp_from_number')->nullable()->after('whatsapp_auth_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_enabled',
                'whatsapp_account_sid',
                'whatsapp_auth_token',
                'whatsapp_from_number',
            ]);
        });
    }
};
