@extends('website.layout')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 min-h-0">
    <div class="bg-white shadow-lg rounded-xl p-6 lg:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">
            <!-- Left: Success message + Appointment Details -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center justify-center h-14 w-14 shrink-0 rounded-full bg-green-100">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Appointment Booked Successfully!</h1>
                        <p class="text-gray-600 mt-1">Your appointment has been confirmed. Please note the details below.</p>
                    </div>
                </div>

                <!-- Appointment Details Card -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 lg:p-5 text-left">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Appointment Details</h2>
                    <div class="space-y-2 text-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Patient Name:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->patient->name }}</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Phone:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->patient->phone }}</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Date:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $appointment->appointment_date->format('l, F d, Y') }}</span>
                    </div>
                </div>
                @if($appointment->package)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-indigo-600 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <div>
                                <span class="text-gray-600">Package:</span>
                                <span class="ml-1 font-semibold text-gray-900">{{ $appointment->package->name }}</span>
                                <span class="ml-1 text-indigo-600">(₹{{ number_format($appointment->package->final_price, 2) }})</span>
                            </div>
                        </div>
                        <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-xs text-green-800"><strong>✓ Test orders created automatically!</strong> Your package includes {{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }}. You can view and download reports once ready.</p>
                        </div>
                        @endif

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

                @if($appointment->doctor)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Referred by:</span>
                        <span class="ml-2 font-semibold text-gray-900">Dr. {{ $appointment->doctor->name }}</span>
                        @if($appointment->doctor->specialization)
                            <span class="ml-2 text-sm text-gray-500">({{ $appointment->doctor->specialization }})</span>
                        @endif
                        @if($appointment->doctor->clinic_name)
                            <span class="ml-2 text-sm text-gray-500">- {{ $appointment->doctor->clinic_name }}</span>
                        @endif
                        @if(!$appointment->doctor->is_active)
                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Approval</span>
                        @endif
                    </div>
                </div>
                @endif

                @if($appointment->referring_doctor_name || $appointment->prescription_file)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
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
                @endif

                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>

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
            </div>

            <!-- Right: Appointment ID + Actions + Notice -->
            <div class="lg:col-span-1 space-y-4 flex flex-col">
                <div class="bg-gray-50 rounded-lg p-4 shrink-0">
                    <p class="text-sm text-gray-600">Appointment ID:</p>
                    <p class="text-xl font-bold text-indigo-600">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('public.appointments.show', $appointment) }}" class="flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all text-sm">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span class="text-center leading-tight">View Details</span>
                    </a>
                    <a href="{{ route('public.appointments.print', $appointment) }}" target="_blank" class="flex flex-col items-center justify-center bg-gradient-to-br from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all text-sm">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span class="text-center leading-tight">Print Confirmation</span>
                    </a>
                    <a href="{{ route('public.appointments.search') }}" class="flex flex-col items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-xl border border-gray-300 transition-all text-sm">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="text-center leading-tight">My Appointments</span>
                    </a>
                    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-xl border border-gray-300 transition-all text-sm">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="text-center leading-tight">Back to Home</span>
                    </a>
                </div>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded flex gap-2">
                    <svg class="h-5 w-5 text-yellow-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p class="text-xs text-yellow-800"><strong>Important:</strong> Please arrive 10 minutes before your scheduled time. To cancel or reschedule, contact us at least 24 hours in advance.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
