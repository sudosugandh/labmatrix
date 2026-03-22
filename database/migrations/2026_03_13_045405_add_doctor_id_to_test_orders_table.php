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
        Schema::table('test_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('test_orders', 'doctor_id')) {
                $table->foreignId('doctor_id')->nullable()->after('patient_id')->constrained('doctors')->onDelete('set null');
            }
            if (!Schema::hasColumn('test_orders', 'referral_number')) {
                $table->string('referral_number')->nullable()->after('doctor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn(['doctor_id', 'referral_number']);
        });
    }
};
