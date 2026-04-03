<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            if (! Schema::hasColumn('promos', 'valid_from')) {
                $table->date('valid_from')->nullable()->after('link');
            }
        });

        Schema::table('website_settings', function (Blueprint $table) {
            $cols = [
                'trust_strip_enabled' => ['boolean', false],
                'trust_strip_items' => ['json', null],
                'hero_cta_primary_label' => ['string', null],
                'hero_cta_primary_url' => ['string', null],
                'hero_cta_secondary_label' => ['string', null],
                'hero_cta_secondary_url' => ['string', null],
                'hero_cta_tertiary_label' => ['string', null],
                'hero_cta_tertiary_url' => ['string', null],
                'sticky_bar_enabled' => ['boolean', false],
                'sticky_bar_items' => ['json', null],
                'promos_today_strip_enabled' => ['boolean', false],
                'promos_today_heading' => ['string', null],
                'promos_today_subtitle' => ['string', null],
                'promos_subtitle' => ['string', null],
                'spotlight_enabled' => ['boolean', false],
                'spotlight_name' => ['string', null],
                'spotlight_credentials' => ['string', null],
                'spotlight_tagline' => ['string', null],
                'spotlight_image' => ['string', null],
            ];
            foreach ($cols as $name => [$type, $default]) {
                if (! Schema::hasColumn('website_settings', $name)) {
                    if ($type === 'boolean') {
                        $table->boolean($name)->default($default);
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
        Schema::table('promos', function (Blueprint $table) {
            if (Schema::hasColumn('promos', 'valid_from')) {
                $table->dropColumn('valid_from');
            }
        });

        Schema::table('website_settings', function (Blueprint $table) {
            $names = [
                'trust_strip_enabled', 'trust_strip_items',
                'hero_cta_primary_label', 'hero_cta_primary_url',
                'hero_cta_secondary_label', 'hero_cta_secondary_url',
                'hero_cta_tertiary_label', 'hero_cta_tertiary_url',
                'sticky_bar_enabled', 'sticky_bar_items',
                'promos_today_strip_enabled', 'promos_today_heading', 'promos_today_subtitle',
                'promos_subtitle',
                'spotlight_enabled', 'spotlight_name', 'spotlight_credentials', 'spotlight_tagline', 'spotlight_image',
            ];
            foreach ($names as $name) {
                if (Schema::hasColumn('website_settings', $name)) {
                    $table->dropColumn($name);
                }
            }
        });
    }
};
