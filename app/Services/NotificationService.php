<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public function create(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        ?string $color = null,
        ?array $data = null
    ): Notification {
        $labId = currentLabId();
        if (!$labId) {
            throw new \Exception('Lab ID not found. Domain detection may have failed.');
        }

        return Notification::create([
            'user_id' => $userId,
            'lab_id' => $labId,
            'notifiable_type' => User::class,
            'notifiable_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon ?? $this->getDefaultIcon($type),
            'color' => $color ?? $this->getDefaultColor($type),
            'action_url' => $actionUrl,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Create notification for all users in the lab.
     */
    public function createForAllUsers(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        ?string $color = null,
        ?array $data = null
    ): void {
        $labId = currentLabId();
        if (!$labId) {
            throw new \Exception('Lab ID not found. Domain detection may have failed.');
        }

        $users = User::where('lab_id', $labId)
            ->where('role', '!=', 'super_admin')
            ->get();

        foreach ($users as $user) {
            $this->create(
                $user->id,
                $type,
                $title,
                $message,
                $actionUrl,
                $icon,
                $color,
                $data
            );
        }
    }

    /**
     * Create notification for specific users.
     */
    public function createForUsers(
        array $userIds,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $icon = null,
        ?string $color = null,
        ?array $data = null
    ): void {
        foreach ($userIds as $userId) {
            $this->create(
                $userId,
                $type,
                $title,
                $message,
                $actionUrl,
                $icon,
                $color,
                $data
            );
        }
    }

    /**
     * Get default icon for notification type.
     */
    protected function getDefaultIcon(string $type): string
    {
        return match($type) {
            'appointment' => 'calendar',
            'report' => 'document-text',
            'order' => 'clipboard-document-list',
            'payment' => 'currency-dollar',
            'bill' => 'receipt-percent',
            'patient' => 'user-group',
            'test' => 'beaker',
            'doctor' => 'user',
            'system' => 'bell',
            default => 'information-circle',
        };
    }

    /**
     * Get default color for notification type.
     */
    protected function getDefaultColor(string $type): string
    {
        return match($type) {
            'appointment' => 'blue',
            'report' => 'green',
            'order' => 'yellow',
            'payment' => 'emerald',
            'bill' => 'purple',
            'patient' => 'indigo',
            'test' => 'cyan',
            'doctor' => 'pink',
            'system' => 'gray',
            default => 'blue',
        };
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('lab_id', currentLabId())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Delete notification.
     */
    public function delete(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->delete();
            return true;
        }

        return false;
    }

    /**
     * Delete all read notifications for a user.
     */
    public function deleteAllRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('lab_id', currentLabId())
            ->where('is_read', true)
            ->delete();
    }

    /**
     * Static helper to notify all users in the lab.
     */
    public static function notifyAll(
        string $type,
        string $title,
        string $message,
        $relatedModel = null,
        ?string $color = null,
        ?array $options = []
    ): void {
        $service = app(NotificationService::class);
        $actionUrl = $options['action_url'] ?? null;
        $icon = $options['icon'] ?? null;
        $data = $options['data'] ?? null;

        $service->createForAllUsers(
            $type,
            $title,
            $message,
            $actionUrl,
            $icon,
            $color,
            $data
        );
    }

    /**
     * Static helper to notify specific user.
     */
    public static function notify(
        int $userId,
        string $type,
        string $title,
        string $message,
        $relatedModel = null,
        ?string $color = null,
        ?array $options = []
    ): Notification {
        $service = app(NotificationService::class);
        $actionUrl = $options['action_url'] ?? null;
        $icon = $options['icon'] ?? null;
        $data = $options['data'] ?? null;

        return $service->create(
            $userId,
            $type,
            $title,
            $message,
            $actionUrl,
            $icon,
            $color,
            $data
        );
    }
}
