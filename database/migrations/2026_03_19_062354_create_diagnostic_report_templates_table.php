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
        Schema::create('diagnostic_report_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->string('name'); // e.g., "CBC Template", "Lipid Profile Template"
            $table->text('description')->nullable();
            $table->enum('template_type', ['simple', 'panel', 'advanced'])->default('simple');
            $table->enum('layout_type', ['table', 'grouped', 'mixed'])->default('table');
            $table->boolean('is_default')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['lab_id', 'template_type']);
            $table->index(['lab_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_report_templates');
    }
};
