<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Appointments</h2>
                <p class="text-sm text-slate-500 mt-1">Schedule and manage patient appointments</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 shadow-lg shadow-violet-500/25 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Appointment
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Today</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['today']) }}</p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Upcoming</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['upcoming']) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Completed (Month)</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['completed']) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Cancelled (Month)</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['cancelled']) }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
        <form method="GET" action="{{ route('appointments.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="patient_id" class="block text-sm font-medium text-slate-700 mb-2">Patient</label>
                <select name="patient_id" id="patient_id" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                    <option value="">All Patients</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name }} - {{ $patient->phone }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="booking_source" class="block text-sm font-medium text-slate-700 mb-2">Source</label>
                <select name="booking_source" id="booking_source" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
                    <option value="">All Sources</option>
                    <option value="public" {{ request('booking_source') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="admin" {{ request('booking_source') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="doctor" {{ request('booking_source') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">
            </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 shadow-lg shadow-violet-500/25 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['patient_id', 'status', 'booking_source', 'date']))
                        <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
                <div class="text-sm text-slate-500">
                    Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments
                </div>
            </div>
        </form>
    </div>

    <!-- Appointments List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Appointments ({{ $appointments->total() }})
            </h3>
        </div>
        <div class="p-6">
            @if($appointments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Referred Doctor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Created By</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($appointments as $appointment)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-slate-900">{{ $appointment->patient->name }}</div>
                                                <div class="text-sm text-slate-500">{{ $appointment->patient->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900 font-medium">
                                            {{ $appointment->appointment_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            @php
                                                $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                                                echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                                            @endphp
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->doctor)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900">Dr. {{ $appointment->doctor->name }}</div>
                                                    @if($appointment->doctor->specialization)
                                                        <div class="text-xs text-slate-500">{{ $appointment->doctor->specialization }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($appointment->referring_doctor_name)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900">{{ $appointment->referring_doctor_name }}</div>
                                                    @if($appointment->referring_doctor_phone)
                                                        <div class="text-xs text-slate-500">{{ $appointment->referring_doctor_phone }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $appointment->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $sourceColors = [
                                                'public' => 'bg-green-100 text-green-800',
                                                'doctor' => 'bg-purple-100 text-purple-800',
                                                'admin' => 'bg-blue-100 text-blue-800'
                                            ];
                                            $sourceLabels = [
                                                'public' => 'Public',
                                                'doctor' => 'Doctor',
                                                'admin' => 'Admin'
                                            ];
                                            $color = $sourceColors[$appointment->booking_source] ?? 'bg-slate-100 text-slate-800';
                                            $label = $sourceLabels[$appointment->booking_source] ?? ucfirst($appointment->booking_source);
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $appointment->createdBy->name ?? ($appointment->booking_source == 'public' ? 'Patient' : 'N/A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('appointments.show', $appointment) }}" 
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                title="View Details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" 
                                                class="p-2 text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" 
                                                title="Edit Appointment">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No appointments</h3>
                    <p class="mt-1 text-sm text-slate-500">Get started by creating a new appointment.</p>
                    <div class="mt-6">
                        <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-violet-600 hover:bg-violet-700 shadow-lg shadow-violet-500/25">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Appointment
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
