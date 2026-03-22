<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('package_tests')) {
            return;
        }
        Schema::create('package_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('test_packages')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['package_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_tests');
    }
};
