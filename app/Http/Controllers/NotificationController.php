<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = auth()->user();
        $labId = currentLabId();

        $query = Notification::where('user_id', $user->id)
            ->where('lab_id', $labId)
            ->latest();

        // Filter by read status
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'unread_count' => $user->unreadNotificationsCount(),
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count (for AJAX polling).
     */
    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();
        return response()->json([
            'count' => $user->unreadNotificationsCount(),
        ]);
    }

    /**
     * Get recent unread notifications (for notification dropdown).
     */
    public function recent(): JsonResponse
    {
        $user = auth()->user();
        $labId = currentLabId();
        
        $notifications = Notification::where('user_id', $user->id)
            ->where('lab_id', $labId)
            ->where('is_read', false)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'action_url' => $notification->action_url,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            }),
            'count' => $user->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $success = $this->notificationService->markAsRead($id, $user->id);

        if ($success) {
            return response()->json([
                'success' => true,
                'unread_count' => $user->fresh()->unreadNotificationsCount(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found',
        ], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = auth()->user();
        $count = $this->notificationService->markAllAsRead($user->id);

        return response()->json([
            'success' => true,
            'count' => $count,
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();
        $success = $this->notificationService->delete($id, $user->id);

        if ($success) {
            return response()->json([
                'success' => true,
                'unread_count' => $user->fresh()->unreadNotificationsCount(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found',
        ], 404);
    }

    /**
     * Delete all read notifications.
     */
    public function deleteAllRead(): JsonResponse
    {
        $user = auth()->user();
        $count = $this->notificationService->deleteAllRead($user->id);

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
