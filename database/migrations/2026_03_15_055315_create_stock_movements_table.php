<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer']); // in = stock received, out = stock used, adjustment = manual correction
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->string('reference_number')->nullable(); // PO number, invoice number, etc.
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('movement_date');
            $table->decimal('stock_before', 10, 2);
            $table->decimal('stock_after', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['lab_id', 'inventory_item_id']);
            $table->index(['lab_id', 'type']);
            $table->index('movement_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
