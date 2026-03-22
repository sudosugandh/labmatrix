<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('restrict');
            $table->decimal('quantity_ordered', 10, 2);
            $table->decimal('quantity_received', 10, 2)->default(0);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('line_total', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('purchase_order_id');
            $table->index('inventory_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
