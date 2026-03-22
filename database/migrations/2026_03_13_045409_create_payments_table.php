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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('bill_id')->constrained('bills')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'online', 'cheque', 'bank_transfer'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->string('cheque_number')->nullable();
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['lab_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
