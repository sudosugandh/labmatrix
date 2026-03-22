@extends('website.layout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-sm rounded-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">View Your Appointments</h1>
        <p class="text-gray-600 mb-8 text-center">Enter your phone number to view and manage your appointments.</p>

        <form method="POST" action="{{ route('public.appointments.results') }}" class="space-y-6">
            @csrf

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                    placeholder="Enter your registered phone number"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-lg">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition-colors">
                    Search Appointments
                </button>
            </div>
        </form>

        @if(session('error'))
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="mt-8 text-center">
            <p class="text-gray-600">Don't have an appointment yet?</p>
            <a href="{{ route('public.appointments.create') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-800 font-semibold">
                Book an Appointment →
            </a>
        </div>
    </div>
</div>
@endsection
