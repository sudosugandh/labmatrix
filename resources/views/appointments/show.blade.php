<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Appointment Details</h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $appointment->appointment_date->format('M d, Y') }} at 
                    @php
                        $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                        echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                    @endphp
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('appointments.edit', $appointment) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Edit
                </a>
                <a href="{{ route('appointments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment Information -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Appointment Information
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $appointment->appointment_date->format('l, F d, Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Time</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                @php
                                    $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                                    echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                                @endphp
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $appointment->status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Booking Source</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $appointment->booking_source == 'public' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $appointment->booking_source == 'public' ? 'Public Booking' : 'Admin Created' }}
                                </span>
                            </dd>
                        </div>
                        @if($appointment->package)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Test Package</dt>
                            <dd class="mt-1">
                                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                    <p class="font-semibold text-indigo-900">{{ $appointment->package->name }}</p>
                                    <p class="text-sm text-indigo-700 mt-1">
                                        ₹{{ number_format($appointment->package->final_price, 2) }} 
                                        ({{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }})
                                    </p>
                                    @if($appointment->status === 'confirmed' || $appointment->status === 'completed')
                                        <p class="text-xs text-green-600 mt-2">
                                            ✓ Test orders created automatically
                                        </p>
                                    @endif
                                </div>
                            </dd>
                        </div>
                        @endif
                        @if($appointment->referring_doctor_name || $appointment->prescription_file)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Referring Doctor Information</dt>
                            <dd class="mt-1">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    @if($appointment->referring_doctor_name)
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold">Doctor Name:</span> {{ $appointment->referring_doctor_name }}
                                        </p>
                                    @endif
                                    @if($appointment->referring_doctor_phone)
                                        <p class="text-sm text-gray-700 mt-1">
                                            <span class="font-semibold">Phone:</span> {{ $appointment->referring_doctor_phone }}
                                        </p>
                                    @endif
                                    @if($appointment->prescription_file)
                                        <div class="mt-3">
                                            <a href="{{ asset('storage/' . $appointment->prescription_file) }}" target="_blank" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View Prescription
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $appointment->createdBy->name ?? ($appointment->booking_source == 'public' ? 'Patient (Public Booking)' : 'N/A') }}
                            </dd>
                        </div>
                        @if($appointment->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                                {{ $appointment->notes }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Patient Information
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $appointment->patient->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->patient->phone }}</dd>
                        </div>
                        @if($appointment->patient->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->patient->email }}</dd>
                        </div>
                        @endif
                        @if($appointment->patient->age)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Age</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->patient->age }} years</dd>
                        </div>
                        @endif
                        @if($appointment->patient->gender)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($appointment->patient->gender) }}</dd>
                        </div>
                        @endif
                    </dl>
                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('patients.show', $appointment->patient) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            View Full Patient Profile →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-xl font-bold text-white">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Status Update Form -->
                    <form method="POST" action="{{ route('appointments.update-status', $appointment) }}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <select name="status" id="status" onchange="this.form.submit()"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ $appointment->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                        </div>
                    </form>

                    <div class="pt-4 border-t space-y-2">
                        <a href="{{ route('orders.create', ['patient_id' => $appointment->patient_id, 'appointment_id' => $appointment->id]) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                            Create Test Order
                        </a>
                        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                Delete Appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Appointment Timeline -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-xl font-bold text-white">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Created</div>
                            <div class="text-sm text-gray-500">{{ $appointment->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @if($appointment->updated_at != $appointment->created_at)
                        <div>
                            <div class="text-sm font-medium text-gray-900">Last Updated</div>
                            <div class="text-sm text-gray-500">{{ $appointment->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
