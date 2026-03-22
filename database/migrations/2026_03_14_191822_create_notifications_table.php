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
        if (Schema::hasTable('notifications')) {
            return;
        }
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('type'); // appointment, report, order, payment, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable(); // icon class or name
            $table->string('color')->default('blue'); // blue, green, red, yellow, etc.
            $table->morphs('notifiable'); // polymorphic relation to related model
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable(); // additional data
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index(['lab_id', 'is_read']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
