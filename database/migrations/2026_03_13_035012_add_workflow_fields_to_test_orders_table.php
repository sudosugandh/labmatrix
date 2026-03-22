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
        Schema::table('test_orders', function (Blueprint $table) {
            $table->string('sample_status')->default('pending')->after('status'); // pending, collected, sent_to_lab
            $table->foreignId('collected_by')->nullable()->after('sample_collected_at')->constrained('users')->onDelete('set null');
            $table->foreignId('technician_id')->nullable()->after('collected_by')->constrained('technicians')->onDelete('set null');
            // Update status to include report_uploaded
            // Status values: pending, sample_collected, processing, completed, report_uploaded
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->dropForeign(['collected_by']);
            $table->dropForeign(['technician_id']);
            $table->dropColumn(['sample_status', 'collected_by', 'technician_id']);
        });
    }
};
