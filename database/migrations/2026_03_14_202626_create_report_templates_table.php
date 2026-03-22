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
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('report_type'); // patients, appointments, orders, bills, doctors, etc.
            $table->json('selected_fields'); // Array of field names to include
            $table->json('filters'); // Array of filter conditions
            $table->json('sorting'); // Array of sort configurations
            $table->json('grouping')->nullable(); // Array of grouping configurations
            $table->json('formatting')->nullable(); // Formatting options (date format, number format, etc.)
            $table->boolean('is_public')->default(false); // Share with other users in lab
            $table->boolean('is_default')->default(false); // Default template for this report type
            $table->integer('usage_count')->default(0); // Track how many times used
            $table->timestamps();
            
            $table->index(['lab_id', 'report_type']);
            $table->index(['lab_id', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
