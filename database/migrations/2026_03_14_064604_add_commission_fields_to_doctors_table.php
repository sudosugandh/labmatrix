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
        Schema::table('doctors', function (Blueprint $table) {
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage')->after('is_active');
            $table->decimal('commission_rate', 5, 2)->default(0)->after('commission_type'); // Percentage or fixed amount
            $table->decimal('total_commission_earned', 10, 2)->default(0)->after('commission_rate');
            $table->decimal('total_commission_paid', 10, 2)->default(0)->after('total_commission_earned');
            $table->decimal('pending_commission', 10, 2)->default(0)->after('total_commission_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'commission_type',
                'commission_rate',
                'total_commission_earned',
                'total_commission_paid',
                'pending_commission',
            ]);
        });
    }
};
