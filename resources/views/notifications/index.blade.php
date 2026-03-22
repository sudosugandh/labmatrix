<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your notifications</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mark All Read
                    </button>
                </form>
                <form method="POST" action="{{ route('notifications.delete-all-read') }}" class="inline" onsubmit="return confirm('Are you sure you want to delete all read notifications?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Read
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('notifications.index') }}" 
               class="px-4 py-2 rounded-lg font-medium transition-colors {{ !request('filter') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('filter') === 'unread' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Unread
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('filter') === 'read' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Read
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow {{ !$notification->is_read ? 'border-l-4 border-l-indigo-600' : '' }}">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="p-3 rounded-lg 
                                @if($notification->color === 'blue') bg-blue-100 @endif
                                @if($notification->color === 'green') bg-green-100 @endif
                                @if($notification->color === 'yellow') bg-yellow-100 @endif
                                @if($notification->color === 'red') bg-red-100 @endif
                                @if($notification->color === 'purple') bg-purple-100 @endif
                                @if($notification->color === 'indigo') bg-indigo-100 @endif
                                @if(!in_array($notification->color, ['blue', 'green', 'yellow', 'red', 'purple', 'indigo'])) bg-gray-100 @endif
                            ">
                                <svg class="w-6 h-6 
                                    @if($notification->color === 'blue') text-blue-600 @endif
                                    @if($notification->color === 'green') text-green-600 @endif
                                    @if($notification->color === 'yellow') text-yellow-600 @endif
                                    @if($notification->color === 'red') text-red-600 @endif
                                    @if($notification->color === 'purple') text-purple-600 @endif
                                    @if($notification->color === 'indigo') text-indigo-600 @endif
                                    @if(!in_array($notification->color, ['blue', 'green', 'yellow', 'red', 'purple', 'indigo'])) text-gray-600 @endif
                                " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                        {{ $notification->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if($notification->type)
                                            • <span class="capitalize">{{ str_replace('_', ' ', $notification->type) }}</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(!$notification->is_read)
                                        <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Mark as read">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">No notifications</h3>
                <p class="mt-2 text-sm text-gray-500">
                    @if(request('filter') === 'unread')
                        You have no unread notifications.
                    @elseif(request('filter') === 'read')
                        You have no read notifications.
                    @else
                        You don't have any notifications yet.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</x-app-layout>
