<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->unsignedInteger('max_users')->nullable()->after('level');
            $table->unsignedInteger('max_storage_mb')->nullable()->after('max_users');
            $table->string('stripe_customer_id')->nullable()->after('billing_notes');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->dropColumn([
                'max_users',
                'max_storage_mb',
                'stripe_customer_id',
                'stripe_subscription_id',
            ]);
        });
    }
};
