<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity_required', 10, 2)->default(1);
            $table->timestamps();

            $table->unique(['lab_id', 'test_id', 'item_id']);
            $table->index(['lab_id', 'test_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_consumables');
    }
};
