<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Lab extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'level',
        'status',
        'subscription_plan',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'monthly_fee',
        'billing_notes',
        'max_users',
        'max_storage_mb',
        'stripe_customer_id',
        'stripe_subscription_id',
    ];

    protected $casts = [
        'level' => 'string',
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'max_users' => 'integer',
        'max_storage_mb' => 'integer',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function testOrders(): HasMany
    {
        return $this->hasMany(TestOrder::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function supportNotes(): HasMany
    {
        return $this->hasMany(LabSupportNote::class);
    }

    /**
     * Whether this lab may use the tenant app and public site (SaaS access control).
     */
    public function isTenantAccessAllowed(): bool
    {
        if (($this->status ?? 'active') === 'suspended') {
            return false;
        }

        $sub = (string) ($this->subscription_status ?? '');

        if (in_array($sub, ['expired', 'cancelled'], true)) {
            return false;
        }

        if ($sub === 'suspended') {
            return false;
        }

        if ($this->subscription_end_date instanceof Carbon) {
            $end = $this->subscription_end_date->copy()->endOfDay();
            if ($end->isPast() && in_array($sub, ['active', 'trial', ''], true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return 'suspended'|'subscription'|null null if access allowed
     */
    public function getTenantAccessBlockReason(): ?string
    {
        if (($this->status ?? 'active') === 'suspended') {
            return 'suspended';
        }

        $sub = (string) ($this->subscription_status ?? '');

        if (in_array($sub, ['expired', 'cancelled', 'suspended'], true)) {
            return 'subscription';
        }

        if ($this->subscription_end_date instanceof Carbon) {
            $end = $this->subscription_end_date->copy()->endOfDay();
            if ($end->isPast() && in_array($sub, ['active', 'trial', ''], true)) {
                return 'subscription';
            }
        }

        return null;
    }

    public static function defaultMaxUsersForLevel(?string $level): int
    {
        return $level === 'large' ? 50 : 5;
    }

    public static function defaultMaxStorageMbForLevel(?string $level): int
    {
        return $level === 'large' ? 5000 : 500;
    }

    /** Max user accounts (staff + portal users) for this lab. */
    public function effectiveMaxUsers(): int
    {
        if ($this->max_users !== null) {
            return max(1, (int) $this->max_users);
        }

        return self::defaultMaxUsersForLevel($this->level);
    }

    /** Storage cap in MB (reports, signatures, website assets counted for this lab). */
    public function effectiveMaxStorageMb(): int
    {
        if ($this->max_storage_mb !== null) {
            return max(1, (int) $this->max_storage_mb);
        }

        return self::defaultMaxStorageMbForLevel($this->level);
    }

    public function estimatedStorageUsageBytes(): int
    {
        $total = $this->sumDiskTree('local', 'reports/'.$this->id);
        $total += $this->sumDiskTree('public', 'doctor-signatures/'.$this->id);
        $total += $this->sumWebsiteSettingAssetBytes();

        return $total;
    }

    private function sumDiskTree(string $disk, string $path): int
    {
        if (! Storage::disk($disk)->exists($path)) {
            return 0;
        }

        $sum = 0;
        foreach (Storage::disk($disk)->allFiles($path) as $file) {
            try {
                $sum += Storage::disk($disk)->size($file);
            } catch (\Throwable) {
            }
        }

        return $sum;
    }

    private function sumWebsiteSettingAssetBytes(): int
    {
        $settings = WebsiteSetting::where('lab_id', $this->id)->first();
        if (! $settings) {
            return 0;
        }

        $sum = 0;
        foreach (['logo', 'favicon', 'hero_image', 'spotlight_image', 'og_image'] as $field) {
            $p = $settings->{$field} ?? null;
            $sum += $this->safePublicFileSize($p);
        }

        $banners = $settings->banner_images ?? null;
        if (is_array($banners)) {
            foreach ($banners as $p) {
                $sum += $this->safePublicFileSize($p);
            }
        }

        $trust = $settings->trust_strip_items ?? null;
        if (is_array($trust)) {
            foreach ($trust as $row) {
                if (is_array($row) && ! empty($row['image'])) {
                    $sum += $this->safePublicFileSize($row['image']);
                }
            }
        }

        return $sum;
    }

    private function safePublicFileSize(mixed $path): int
    {
        if (! is_string($path) || $path === '') {
            return 0;
        }
        if (! Storage::disk('public')->exists($path)) {
            return 0;
        }
        try {
            return Storage::disk('public')->size($path);
        } catch (\Throwable) {
            return 0;
        }
    }
}
