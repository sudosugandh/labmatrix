<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('payment_terms')->nullable(); // e.g., "Net 30", "COD"
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['lab_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
