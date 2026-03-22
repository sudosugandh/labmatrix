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
        Schema::create('template_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('diagnostic_report_templates')->onDelete('cascade');
            $table->enum('section_type', ['parameters', 'text', 'table', 'calculated', 'notes', 'interpretation'])->default('parameters');
            $table->string('title')->nullable();
            $table->json('content_json')->nullable(); // Stores section-specific configuration
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            
            $table->index(['template_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_sections');
    }
};
