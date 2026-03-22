@extends('website.layout')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-xl p-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Appointment Details</h1>
                <p class="text-gray-600">Appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('public.appointments.print', $appointment) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Appointment Information -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Appointment Information</h2>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Date:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->appointment_date->format('l, F d, Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Time:</span>
                        <span class="ml-2 font-semibold text-gray-900">
                            @php
                                $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                                echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                            @endphp
                        </span>
                    </div>
                </div>

                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $appointment->status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </div>
                </div>

                @if($appointment->package)
                <div class="flex items-start pt-2">
                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <div class="flex-1">
                        <span class="text-sm text-gray-600">Package:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->package->name }}</span>
                        <span class="ml-2 text-sm text-indigo-600">(₹{{ number_format($appointment->package->final_price, 2) }})</span>
                        <p class="text-xs text-gray-500 mt-1">{{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }} included</p>
                        @if($appointment->status === 'confirmed' || $appointment->status === 'completed')
                            <p class="text-xs text-green-600 mt-2">
                                ✓ Test orders created automatically
                            </p>
                        @endif
                    </div>
                </div>
                @endif

                @if($appointment->referring_doctor_name || $appointment->prescription_file)
                <div class="flex items-start pt-2">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex-1 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-blue-900 mb-2">Referring Doctor Information</h3>
                        @if($appointment->referring_doctor_name)
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">Doctor:</span> {{ $appointment->referring_doctor_name }}
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
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Prescription
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($appointment->notes)
                <div class="flex items-start pt-2">
                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Notes:</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $appointment->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Patient Information -->
        <div class="bg-gray-50 rounded-xl p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Patient Information</h2>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Name:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->patient->name }}</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Phone:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->patient->phone }}</span>
                    </div>
                </div>

                @if($appointment->patient->email)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Email:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->patient->email }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if(in_array($appointment->status, ['scheduled', 'confirmed']) && $appointment->booking_source == 'public')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-yellow-700">
                        <strong>Need to cancel?</strong> You can cancel this appointment if your plans have changed. Please cancel at least 24 hours in advance.
                    </p>
                    <div class="mt-3">
                        <form method="POST" action="{{ route('public.appointments.cancel', $appointment) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                Cancel Appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Navigation -->
        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4 pt-4 border-t">
            <a href="{{ route('public.appointments.search') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                ← Back to My Appointments
            </a>
            <span class="text-gray-300 hidden sm:inline">|</span>
            <a href="{{ route('public.appointments.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Book New Appointment
            </a>
            <span class="text-gray-300 hidden sm:inline">|</span>
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
