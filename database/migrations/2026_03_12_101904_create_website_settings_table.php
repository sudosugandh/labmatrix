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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->unique()->constrained('labs')->onDelete('cascade');
            $table->string('logo')->nullable();
            $table->string('primary_color')->default('#3B82F6');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
