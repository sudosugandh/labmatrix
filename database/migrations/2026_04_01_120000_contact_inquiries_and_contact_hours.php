<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->string('name');
            $table->string('phone', 80);
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('website_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('website_settings', 'contact_working_hours')) {
                $table->text('contact_working_hours')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');

        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'contact_working_hours')) {
                $table->dropColumn('contact_working_hours');
            }
        });
    }
};
