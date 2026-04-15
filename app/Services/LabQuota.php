<?php

namespace App\Services;

use App\Models\Lab;
use Illuminate\Validation\ValidationException;

class LabQuota
{
    public static function assertCanAddUser(Lab $lab): void
    {
        $max = $lab->effectiveMaxUsers();
        $count = $lab->users()->count();
        if ($count >= $max) {
            throw ValidationException::withMessages([
                'email' => 'This lab has reached the maximum number of user accounts for its plan. Ask the platform administrator to raise the limit or upgrade the lab level.',
            ]);
        }
    }

    public static function assertCanUseStorageBytes(Lab $lab, int $additionalBytes, string $errorKey = 'report_file'): void
    {
        if ($additionalBytes <= 0) {
            return;
        }

        $maxMb = $lab->effectiveMaxStorageMb();
        $maxBytes = $maxMb * 1024 * 1024;
        $current = $lab->estimatedStorageUsageBytes();

        if ($current + $additionalBytes > $maxBytes) {
            throw ValidationException::withMessages([
                $errorKey => 'Storage limit reached for this lab. Remove older report files or ask the platform administrator to increase the storage allowance.',
            ]);
        }
    }
}
