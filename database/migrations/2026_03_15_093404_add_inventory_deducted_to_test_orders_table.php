<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('test_orders', 'inventory_deducted')) {
                $table->boolean('inventory_deducted')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->dropColumn('inventory_deducted');
        });
    }
};
