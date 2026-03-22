@props(['unreadCount' => 0])

<div x-data="{ 
    open: false, 
    unreadCount: {{ $unreadCount }},
    notifications: [],
    loading: false,
    polling: null
}" 
x-init="
    // Load initial notifications
    loadNotifications();
    
    // Poll for new notifications every 30 seconds
    polling = setInterval(() => {
        if (!open) {
            checkUnreadCount();
        }
    }, 30000);
    
    // Cleanup on unmount
    $watch('open', (value) => {
        if (value) {
            loadNotifications();
        }
    });
    
    async function loadNotifications() {
        loading = true;
        try {
            const response = await fetch('{{ route('notifications.recent') }}');
            const data = await response.json();
            notifications = data.notifications;
            unreadCount = data.count;
        } catch (error) {
            console.error('Error loading notifications:', error);
        } finally {
            loading = false;
        }
    }
    
    async function checkUnreadCount() {
        try {
            const response = await fetch('{{ route('notifications.unread-count') }}');
            const data = await response.json();
            unreadCount = data.count;
        } catch (error) {
            console.error('Error checking unread count:', error);
        }
    }
    
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                }
            });
            const data = await response.json();
            if (data.success) {
                unreadCount = data.unread_count;
                // Update notification in list
                const index = notifications.findIndex(n => n.id === notificationId);
                if (index !== -1) {
                    notifications[index].is_read = true;
                }
            }
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }
    
    async function markAllAsRead() {
        try {
            const response = await fetch('{{ route('notifications.mark-all-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                }
            });
            const data = await response.json();
            if (data.success) {
                unreadCount = 0;
                notifications.forEach(n => n.is_read = true);
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }
" 
class="relative">
    <!-- Notification Bell Button -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[1.25rem] h-5"></span>
    </button>

    <!-- Notification Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-[32rem] overflow-hidden flex flex-col"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
            <div class="flex items-center space-x-2">
                <button @click="markAllAsRead()" 
                        x-show="unreadCount > 0"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                    Mark all read
                </button>
                <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                    View all
                </a>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1">
            <template x-if="loading">
                <div class="p-8 text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <p class="mt-2 text-sm text-gray-500">Loading notifications...</p>
                </div>
            </template>
            
            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No notifications</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div @click="if (notification.action_url) window.location.href = notification.action_url; markAsRead(notification.id)"
                     :class="{
                         'bg-indigo-50': !notification.is_read,
                         'bg-white': notification.is_read
                     }"
                     class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-start space-x-3">
                        <!-- Icon -->
                        <div :class="{
                            'bg-blue-100': notification.color === 'blue',
                            'bg-green-100': notification.color === 'green',
                            'bg-yellow-100': notification.color === 'yellow',
                            'bg-red-100': notification.color === 'red',
                            'bg-purple-100': notification.color === 'purple',
                            'bg-indigo-100': notification.color === 'indigo',
                            'bg-gray-100': !notification.color
                        }"
                        class="flex-shrink-0 p-2 rounded-lg">
                            <svg class="w-5 h-5" :class="{
                                'text-blue-600': notification.color === 'blue',
                                'text-green-600': notification.color === 'green',
                                'text-yellow-600': notification.color === 'yellow',
                                'text-red-600': notification.color === 'red',
                                'text-purple-600': notification.color === 'purple',
                                'text-indigo-600': notification.color === 'indigo',
                                'text-gray-600': !notification.color
                            }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900" x-text="notification.title"></p>
                            <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="new Date(notification.created_at).toLocaleString()"></p>
                        </div>
                        
                        <!-- Unread Indicator -->
                        <div x-show="!notification.is_read" class="flex-shrink-0">
                            <div class="w-2 h-2 bg-indigo-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                View all notifications →
            </a>
        </div>
    </div>
</div>
