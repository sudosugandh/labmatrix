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
        Schema::create('test_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->string('parameter_name');
            $table->string('method_name')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('reference_range_low', 10, 2)->nullable();
            $table->decimal('reference_range_high', 10, 2)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['lab_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_parameters');
    }
};
