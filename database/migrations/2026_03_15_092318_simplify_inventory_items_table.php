<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('inventory_items', 'preferred_supplier_id')) {
                $table->dropForeign(['preferred_supplier_id']);
    }

            // Drop columns that exist
            $columnsToDrop = [
                'barcode',
                'description',
                'category',
                'maximum_stock',
                'selling_price',
                'preferred_supplier_id',
                'location',
                'batch_number',
                'is_active'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('inventory_items', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Rename unit_cost to cost if it exists
            if (Schema::hasColumn('inventory_items', 'unit_cost')) {
                $table->renameColumn('unit_cost', 'cost');
            } else {
                // Add cost column if unit_cost doesn't exist
                $table->decimal('cost', 10, 2)->default(0)->after('minimum_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            // Restore removed columns
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->decimal('maximum_stock', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->string('batch_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('preferred_supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            
            // Rename cost back to unit_cost
            $table->renameColumn('cost', 'unit_cost');
        });
    }
};
