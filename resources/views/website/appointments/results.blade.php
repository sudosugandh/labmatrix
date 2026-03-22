@extends('website.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-sm rounded-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Your Appointments</h1>

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($patient) && isset($appointments))
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600">Patient:</p>
                <p class="text-lg font-semibold text-gray-900">{{ $patient->name }}</p>
                <p class="text-sm text-gray-600">{{ $patient->phone }}</p>
            </div>

            @if($appointments->count() > 0)
                @php
                    $now = now();
                    $upcoming = $appointments->filter(function($apt) use ($now) {
                        // Combine date and time properly - appointment_time is now a string (H:i:s or H:i)
                        $timeStr = strlen($apt->appointment_time) == 5 ? $apt->appointment_time . ':00' : $apt->appointment_time;
                        $aptDateTime = \Carbon\Carbon::parse($apt->appointment_date->format('Y-m-d') . ' ' . $timeStr);
                        return $aptDateTime->isFuture() && !in_array($apt->status, ['cancelled', 'completed', 'no_show']);
                    });
                    $past = $appointments->filter(function($apt) use ($now) {
                        // Combine date and time properly - appointment_time is now a string (H:i:s or H:i)
                        $timeStr = strlen($apt->appointment_time) == 5 ? $apt->appointment_time . ':00' : $apt->appointment_time;
                        $aptDateTime = \Carbon\Carbon::parse($apt->appointment_date->format('Y-m-d') . ' ' . $timeStr);
                        return $aptDateTime->isPast() || in_array($apt->status, ['cancelled', 'completed', 'no_show']);
                    });
                @endphp

                @if($upcoming->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Upcoming Appointments</h2>
                        <div class="space-y-4">
                            @foreach($upcoming as $appointment)
                                <div class="border border-indigo-200 rounded-lg p-6 hover:shadow-md transition-shadow bg-indigo-50">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        Appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                                    </h3>
                                                    <div class="mt-2 space-y-1">
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Date:</span> 
                                                            {{ $appointment->appointment_date->format('l, F d, Y') }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Time:</span> 
                                                            @php
                                                                $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                                                                echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                                                            @endphp
                                                        </p>
                                                        @if($appointment->notes)
                                                            <p class="text-sm text-gray-600">
                                                                <span class="font-medium">Notes:</span> {{ $appointment->notes }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 md:ml-4 flex flex-col items-end space-y-2">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $appointment->status_color }}">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('public.appointments.show', $appointment) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                                                    View Details
                                                </a>
                                                @if(in_array($appointment->status, ['scheduled', 'confirmed']) && $appointment->booking_source == 'public')
                                                    <span class="text-gray-300">|</span>
                                                    <form method="POST" action="{{ route('public.appointments.cancel', $appointment) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-semibold">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($past->count() > 0)
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Past Appointments</h2>
                        <div class="space-y-4">
                            @foreach($past as $appointment)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow bg-gray-50">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        Appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                                    </h3>
                                                    <div class="mt-2 space-y-1">
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Date:</span> 
                                                            {{ $appointment->appointment_date->format('l, F d, Y') }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium">Time:</span> 
                                                            @php
                                                                $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                                                                echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                                                            @endphp
                                                        </p>
                                                        @if($appointment->notes)
                                                            <p class="text-sm text-gray-600">
                                                                <span class="font-medium">Notes:</span> {{ $appointment->notes }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 md:ml-4 flex flex-col items-end space-y-2">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $appointment->status_color }}">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                            <a href="{{ route('public.appointments.show', $appointment) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments found</h3>
                    <p class="mt-1 text-sm text-gray-500">You don't have any appointments yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Book an Appointment
                        </a>
                    </div>
                </div>
            @endif
        @endif

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('public.appointments.search') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Search Again
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
