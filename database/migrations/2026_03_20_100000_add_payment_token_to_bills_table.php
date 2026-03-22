<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->string('payment_token', 64)->nullable()->unique()->after('bill_number');
        });

        // Generate tokens for existing bills
        $ids = \Illuminate\Support\Facades\DB::table('bills')->whereNull('payment_token')->pluck('id');
        foreach ($ids as $id) {
            \Illuminate\Support\Facades\DB::table('bills')->where('id', $id)->update(['payment_token' => Str::random(48)]);
        }
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('payment_token');
        });
    }
};
