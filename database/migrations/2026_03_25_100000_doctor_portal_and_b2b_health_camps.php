<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (! Schema::hasColumn('doctors', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (! Schema::hasColumn('doctors', 'remember_token')) {
                $table->rememberToken();
            }
            if (! Schema::hasColumn('doctors', 'referral_code')) {
                $table->string('referral_code', 32)->nullable()->after('remember_token');
            }
        });

        Schema::create('corporate_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['lab_id', 'phone']);
        });

        Schema::create('health_camps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
            $table->foreignId('corporate_account_id')->nullable()->constrained('corporate_accounts')->nullOnDelete();
            $table->string('name');
            $table->date('camp_date')->nullable();
            $table->string('venue')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 32)->default('scheduled'); // scheduled, completed, cancelled
            $table->timestamps();
            $table->index(['lab_id', 'camp_date']);
        });

        Schema::table('test_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('test_orders', 'health_camp_id')) {
                $table->foreignId('health_camp_id')->nullable()->after('appointment_id')->constrained('health_camps')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('test_orders', function (Blueprint $table) {
            if (Schema::hasColumn('test_orders', 'health_camp_id')) {
                $table->dropForeign(['health_camp_id']);
                $table->dropColumn('health_camp_id');
            }
        });

        Schema::dropIfExists('health_camps');
        Schema::dropIfExists('corporate_accounts');

        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'password')) {
                $table->dropColumn('password');
            }
            if (Schema::hasColumn('doctors', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
            if (Schema::hasColumn('doctors', 'referral_code')) {
                $table->dropColumn('referral_code');
            }
        });
    }
};
