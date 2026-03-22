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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('test_order_id')->constrained('test_orders')->onDelete('cascade');
            $table->string('parameter_name');
            $table->string('result_value')->nullable();
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();
            $table->enum('status', ['normal', 'low', 'high', 'critical'])->default('normal');
            $table->text('remarks')->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('entered_at')->nullable();
            $table->timestamps();
            
            $table->index(['lab_id', 'test_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
