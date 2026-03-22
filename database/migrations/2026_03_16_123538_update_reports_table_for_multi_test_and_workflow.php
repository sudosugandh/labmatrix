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
        Schema::table('reports', function (Blueprint $table) {
            // Make test_order_id nullable to support multi-test reports
            $table->foreignId('test_order_id')->nullable()->change();
            
            // Add workflow status
            $table->enum('workflow_status', ['draft', 'reviewed', 'released'])->default('draft')->after('status');
            
            // Add interpretation fields
            $table->text('interpretation')->nullable()->after('remarks');
            $table->timestamp('reviewed_at')->nullable()->after('workflow_status');
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->onDelete('set null');
            $table->timestamp('released_at')->nullable()->after('reviewed_by');
            $table->foreignId('released_by')->nullable()->after('released_at')->constrained('users')->onDelete('set null');
            
            // Add report number for tracking
            $table->string('report_number')->unique()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('test_order_id')->nullable(false)->change();
            $table->dropColumn([
                'workflow_status',
                'interpretation',
                'reviewed_at',
                'reviewed_by',
                'released_at',
                'released_by',
                'report_number'
            ]);
        });
    }
};
