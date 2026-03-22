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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('doctor_name')->nullable()->after('interpretation');
            $table->string('doctor_qualification')->nullable()->after('doctor_name');
            $table->string('doctor_signature')->nullable()->after('doctor_qualification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['doctor_name', 'doctor_qualification', 'doctor_signature']);
        });
    }
};
