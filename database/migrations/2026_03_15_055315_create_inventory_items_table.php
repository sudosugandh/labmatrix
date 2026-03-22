<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->nullable(); // Stock Keeping Unit
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // Test Kit, Reagent, Consumable, Equipment
            $table->string('unit')->default('piece'); // piece, box, pack, liter, kg
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('minimum_stock', 10, 2)->default(0); // Reorder level
            $table->decimal('maximum_stock', 10, 2)->nullable();
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->foreignId('preferred_supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->string('location')->nullable(); // Storage location
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['lab_id', 'category']);
            $table->index(['lab_id', 'is_active']);
            $table->unique(['lab_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
