<?php

namespace App\Support;

/**
 * Canonical phone digits for login and storage (India-oriented: +91 / leading 0).
 */
final class PhoneDigits
{
    public static function normalize(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === '') {
            return null;
        }

        if (strlen($digits) >= 12 && str_starts_with($digits, '91')) {
            return substr($digits, -10);
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return substr($digits, 1);
        }

        if (strlen($digits) > 10) {
            return substr($digits, -10);
        }

        return $digits;
    }
}
