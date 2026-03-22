@extends('website.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        @if($testPackage->discount > 0)
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-center py-3">
                <p class="text-lg font-bold">Special Offer: {{ $testPackage->discount }}% OFF</p>
            </div>
        @endif

        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $testPackage->name }}</h1>
                @if($testPackage->description)
                    <p class="text-lg text-gray-600">{{ $testPackage->description }}</p>
                @endif
            </div>

            <!-- Pricing -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-8 text-center">
                @if($testPackage->discount > 0)
                    <p class="text-gray-500 line-through text-xl mb-2">₹{{ number_format($testPackage->price, 2) }}</p>
                @endif
                <p class="text-5xl font-bold text-indigo-600 mb-2">₹{{ number_format($testPackage->final_price, 2) }}</p>
                <p class="text-gray-600">Includes {{ $testPackage->tests->count() }} {{ Str::plural('test', $testPackage->tests->count()) }}</p>
            </div>

            <!-- Tests Included -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Tests Included in This Package</h2>
                <div class="space-y-3">
                    @foreach($testPackage->tests as $test)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $test->name }}</h3>
                                    @if($test->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $test->description }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Individual Price</p>
                                    <p class="font-semibold text-indigo-600">₹{{ number_format($test->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Savings Calculation -->
            @php
                $individualTotal = $testPackage->tests->sum('price');
                $savings = $individualTotal - $testPackage->final_price;
            @endphp
            @if($savings > 0)
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-8">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-800">You Save ₹{{ number_format($savings, 2) }}</p>
                            <p class="text-sm text-green-700">Individual tests would cost ₹{{ number_format($individualTotal, 2) }}, but with this package you pay only ₹{{ number_format($testPackage->final_price, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Call to Action -->
            <div class="text-center space-y-4">
                <a href="{{ route('public.appointments.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 px-8 rounded-lg transition-colors text-lg">
                    Book Appointment for This Package
                </a>
                <div>
                    <a href="{{ route('public.packages.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        ← View All Packages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
