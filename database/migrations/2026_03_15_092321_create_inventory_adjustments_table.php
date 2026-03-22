<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->enum('adjustment_type', ['add', 'remove', 'damage', 'correction'])->default('add');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['lab_id', 'item_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
    }
};
