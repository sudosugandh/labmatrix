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
        Schema::table('test_results', function (Blueprint $table) {
            $table->foreignId('test_parameter_id')->nullable()->after('test_order_id')->constrained('test_parameters')->onDelete('set null');
            $table->string('method_name')->nullable()->after('parameter_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->dropForeign(['test_parameter_id']);
            $table->dropColumn(['test_parameter_id', 'method_name']);
        });
    }
};
