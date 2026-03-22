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
        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->foreignId('lab_id')->after('id')->constrained('labs')->onDelete('cascade');
            $table->unsignedTinyInteger('weekday')->after('lab_id')->comment('0=Sun ... 6=Sat');
            $table->time('start_time')->after('weekday');
            $table->unsignedInteger('max_bookings')->default(1)->after('start_time');
            $table->string('label')->nullable()->after('max_bookings');
            $table->unsignedInteger('sort_order')->default(0)->after('label');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->index(['lab_id', 'weekday', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->dropIndex(['lab_id', 'weekday', 'is_active']);
            $table->dropForeign(['lab_id']);
            $table->dropColumn([
                'lab_id', 'weekday', 'start_time', 'max_bookings',
                'label', 'sort_order', 'is_active',
            ]);
        });
    }
};
