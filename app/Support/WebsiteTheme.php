<?php

namespace App\Support;

/**
 * Preset themes for public lab websites: typography + default accent + page tint.
 * Primary color remains per-lab via WebsiteSetting::primary_color.
 */
class WebsiteTheme
{
    public const PRESET_KEYS = [
        'violet',
        'emerald',
        'rose',
        'amber',
        'sky',
        'slate',
        'ocean',
        'crimson',
    ];

    /**
     * @return array{fonts_href: string, font_display: string, font_body: string, secondary_default: string, page_bg_start: string, page_bg_end: string}
     */
    public static function preset(string $key): array
    {
        $presets = [
            'violet' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=outfit:400,500,600,700|sora:600,700&display=swap',
                'font_display' => "'Sora', ui-sans-serif, system-ui, sans-serif",
                'font_body' => "'Outfit', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#8b5cf6',
                'page_bg_start' => '#fafbff',
                'page_bg_end' => '#f0f4ff',
            ],
            'emerald' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|fraunces:600,700&display=swap',
                'font_display' => "'Fraunces', ui-serif, Georgia, serif",
                'font_body' => "'DM Sans', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#34d399',
                'page_bg_start' => '#f8fdf9',
                'page_bg_end' => '#ecfdf5',
            ],
            'rose' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=nunito:400,500,600,700|playfair-display:600,700&display=swap',
                'font_display' => "'Playfair Display', ui-serif, Georgia, serif",
                'font_body' => "'Nunito', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#fb7185',
                'page_bg_start' => '#fffafb',
                'page_bg_end' => '#fff1f2',
            ],
            'amber' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=source-sans-3:400,500,600,700|libre-baskerville:700&display=swap',
                'font_display' => "'Libre Baskerville', ui-serif, Georgia, serif",
                'font_body' => "'Source Sans 3', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#fbbf24',
                'page_bg_start' => '#fffdf7',
                'page_bg_end' => '#fffbeb',
            ],
            'sky' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|syne:600,700&display=swap',
                'font_display' => "'Syne', ui-sans-serif, system-ui, sans-serif",
                'font_body' => "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#38bdf8',
                'page_bg_start' => '#f8fbff',
                'page_bg_end' => '#e0f2fe',
            ],
            'slate' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=manrope:400,500,600,700|work-sans:600,700&display=swap',
                'font_display' => "'Work Sans', ui-sans-serif, system-ui, sans-serif",
                'font_body' => "'Manrope', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#94a3b8',
                'page_bg_start' => '#f8fafc',
                'page_bg_end' => '#f1f5f9',
            ],
            'ocean' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=lexend:400,500,600,700|manrope:600,700&display=swap',
                'font_display' => "'Lexend', ui-sans-serif, system-ui, sans-serif",
                'font_body' => "'Manrope', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#22d3ee',
                'page_bg_start' => '#f5fbff',
                'page_bg_end' => '#e0f7fa',
            ],
            'crimson' => [
                'fonts_href' => 'https://fonts.bunny.net/css?family=lato:400,700|merriweather:700&display=swap',
                'font_display' => "'Merriweather', ui-serif, Georgia, serif",
                'font_body' => "'Lato', ui-sans-serif, system-ui, sans-serif",
                'secondary_default' => '#f87171',
                'page_bg_start' => '#fffafa',
                'page_bg_end' => '#fef2f2',
            ],
        ];

        return $presets[$key] ?? $presets['violet'];
    }
}
