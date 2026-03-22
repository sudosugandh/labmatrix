<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lab_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()
            ->where('lab_id', currentLabId())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()
            ->where('lab_id', currentLabId())
            ->where('is_read', false)
            ->latest()
            ->get();
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
