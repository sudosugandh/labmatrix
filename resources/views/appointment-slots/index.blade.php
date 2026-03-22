<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Appointment time slots</h2>
                <p class="text-sm text-slate-500 mt-1">Define weekly windows for online booking (e.g. Mon 9:00 AM). If none are active, patients can pick any time.</p>
            </div>
            <a href="{{ route('appointment-slots.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 shadow-lg shadow-violet-500/25 text-white font-semibold rounded-xl shadow-lg transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add slot
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800" role="alert">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30">
            <h3 class="text-lg font-semibold text-slate-900">All slots</h3>
        </div>
        <div class="overflow-x-auto">
            @if($slots->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Day</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Max / day</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($slots as $slot)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $weekdays[$slot->weekday] ?? $slot->weekday }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $slot->display_time }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $slot->label ?: '—' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $slot->max_bookings }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $slot->sort_order }}</td>
                                <td class="px-6 py-4">
                                    @if($slot->is_active)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Off</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('appointment-slots.edit', $slot) }}" class="text-violet-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <form action="{{ route('appointment-slots.destroy', $slot) }}" method="POST" class="inline" onsubmit="return confirm('Delete this slot?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4">{{ $slots->links() }}</div>
            @else
                <div class="px-6 py-12 text-center text-slate-500">
                    <p>No slots yet. Add weekly time windows to restrict public booking to those times.</p>
                    <a href="{{ route('appointment-slots.create') }}" class="mt-4 inline-block text-violet-600 font-semibold hover:underline">Add your first slot</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
