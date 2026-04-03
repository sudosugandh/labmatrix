<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $cols = [
                'about_intro' => 'text',
                'about_why_cards' => 'json',
                'about_stat_tests' => 'string',
                'about_stat_patients' => 'string',
                'about_stat_years' => 'string',
                'about_working_hours' => 'text',
                'spotlight_experience' => 'string',
                'about_team_trust_line' => 'string',
            ];
            foreach ($cols as $name => $type) {
                if (! Schema::hasColumn('website_settings', $name)) {
                    if ($type === 'text') {
                        $table->text($name)->nullable();
                    } elseif ($type === 'json') {
                        $table->json($name)->nullable();
                    } else {
                        $table->string($name)->nullable();
                    }
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            foreach ([
                'about_intro', 'about_why_cards', 'about_stat_tests', 'about_stat_patients',
                'about_stat_years', 'about_working_hours', 'spotlight_experience', 'about_team_trust_line',
            ] as $name) {
                if (Schema::hasColumn('website_settings', $name)) {
                    $table->dropColumn($name);
                }
            }
        });
    }
};
