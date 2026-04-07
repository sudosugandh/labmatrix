<?php

namespace App\Support;

use App\Models\Promo;
use Illuminate\Support\Str;

class PromoCardPresenter
{
    /**
     * @return array{title: string, discount_percent: int|null, ending_soon: bool, short_text: string, is_placeholder_description: bool}
     */
    public static function for(Promo $promo): array
    {
        $raw = (string) ($promo->title ?? '');
        $discount = null;
        if (preg_match('/(\d+)\s*%?\s*off\b/iu', $raw, $m)) {
            $discount = min(100, max(0, (int) $m[1]));
        }
        $endingSoon = (bool) preg_match('/ending\s+soon/iu', $raw);
        $titleClean = trim((string) preg_replace([
            '/\s*[—–\-]\s*\d+\s*%?\s*off\s*$/iu',
            '/\s*[—–\-]\s*ending\s+soon\s*$/iu',
            '/\s*\(\s*\d+\s*%?\s*off\s*\)\s*$/iu',
        ], '', $raw));
        if ($titleClean === '') {
            $titleClean = $raw;
        }

        $rawDesc = trim(strip_tags((string) ($promo->short_text ?? '')));
        $shortText = $rawDesc !== '' ? Str::limit($rawDesc, 120) : '';
        $isPlaceholder = $shortText !== '' && (
            strlen($shortText) < 18
            || preg_match('/^[a-z\s;]+$/i', $shortText)
            || preg_match('/hjkl|asdf|test@test|lorem/i', $shortText)
        );

        return [
            'title' => $titleClean,
            'discount_percent' => $discount,
            'ending_soon' => $endingSoon && $discount === null,
            'short_text' => $shortText,
            'is_placeholder_description' => $isPlaceholder,
        ];
    }
}
