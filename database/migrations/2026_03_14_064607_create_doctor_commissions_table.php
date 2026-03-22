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
        Schema::create('doctor_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('test_order_id')->nullable()->constrained('test_orders')->onDelete('set null');
            $table->foreignId('bill_id')->nullable()->constrained('bills')->onDelete('set null');
            $table->decimal('order_amount', 10, 2); // Original order/bill amount
            $table->decimal('commission_amount', 10, 2); // Calculated commission
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->date('commission_date'); // Date when commission was earned
            $table->date('payment_date')->nullable(); // Date when commission was paid
            $table->text('notes')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['lab_id', 'doctor_id', 'status']);
            $table->index(['lab_id', 'commission_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_commissions');
    }
};
