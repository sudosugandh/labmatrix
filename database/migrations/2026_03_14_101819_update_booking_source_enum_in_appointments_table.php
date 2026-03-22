<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't support modifying ENUM directly, so we need to use raw SQL
        DB::statement("ALTER TABLE appointments MODIFY COLUMN booking_source ENUM('admin', 'public', 'doctor') DEFAULT 'admin'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE appointments MODIFY COLUMN booking_source ENUM('admin', 'public') DEFAULT 'admin'");
    }
};
