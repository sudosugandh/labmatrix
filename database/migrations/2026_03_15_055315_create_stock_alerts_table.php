<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->enum('alert_type', ['low_stock', 'out_of_stock', 'expiring_soon', 'expired']); // low_stock = below minimum, out_of_stock = 0, expiring_soon = within 30 days, expired = past expiry
            $table->decimal('current_stock', 10, 2);
            $table->decimal('threshold', 10, 2)->nullable(); // For low_stock alerts
            $table->date('expiry_date')->nullable(); // For expiry alerts
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->index(['lab_id', 'is_resolved']);
            $table->index(['lab_id', 'alert_type']);
            $table->index('inventory_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
