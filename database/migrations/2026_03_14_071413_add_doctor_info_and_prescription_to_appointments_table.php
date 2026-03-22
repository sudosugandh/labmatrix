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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('referring_doctor_name')->nullable()->after('doctor_id');
            $table->string('referring_doctor_phone')->nullable()->after('referring_doctor_name');
            $table->string('prescription_file')->nullable()->after('referring_doctor_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['referring_doctor_name', 'referring_doctor_phone', 'prescription_file']);
        });
    }
};
