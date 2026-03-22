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
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('report_template_id')->constrained('report_templates')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'custom'])->default('monthly');
            $table->json('schedule_config')->nullable(); // Day of week, day of month, etc.
            $table->time('send_time')->default('09:00');
            $table->json('recipients'); // Array of email addresses
            $table->enum('format', ['pdf', 'excel', 'csv'])->default('pdf');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->integer('run_count')->default(0);
            $table->timestamps();
            
            $table->index(['lab_id', 'is_active', 'next_run_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_reports');
    }
};
