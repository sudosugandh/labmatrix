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
        Schema::table('labs', function (Blueprint $table) {
            $table->enum('subscription_plan', ['trial', 'basic', 'pro', 'enterprise'])->default('trial')->after('status');
            $table->enum('subscription_status', ['active', 'expired', 'suspended', 'cancelled'])->default('active')->after('subscription_plan');
            $table->date('subscription_start_date')->nullable()->after('subscription_status');
            $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
            $table->decimal('monthly_fee', 10, 2)->nullable()->after('subscription_end_date');
            $table->text('billing_notes')->nullable()->after('monthly_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_plan',
                'subscription_status',
                'subscription_start_date',
                'subscription_end_date',
                'monthly_fee',
                'billing_notes',
            ]);
        });
    }
};
