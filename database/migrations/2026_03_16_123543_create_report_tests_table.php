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
        Schema::create('report_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('test_order_id')->constrained('test_orders')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->text('interpretation')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['report_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_tests');
    }
};
