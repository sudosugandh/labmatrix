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
            $table->foreignId('bill_id')->nullable()->after('report_status')->constrained('bills')->onDelete('set null');
            $table->decimal('price', 10, 2)->default(0)->after('bill_id');
            $table->decimal('discount', 10, 2)->default(0)->after('price');
            $table->decimal('final_price', 10, 2)->default(0)->after('discount');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->after('final_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->dropForeign(['bill_id']);
            $table->dropColumn(['bill_id', 'price', 'discount', 'final_price', 'payment_status']);
        });
    }
};
