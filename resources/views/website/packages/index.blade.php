@extends('website.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Test Packages</h1>
        <p class="text-xl text-gray-600">Comprehensive health checkup packages at special prices</p>
    </div>

    @if($packages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $package)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    @if($package->discount > 0)
                        <div class="bg-red-500 text-white text-center py-2 text-sm font-semibold">
                            {{ $package->discount }}% OFF
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                        
                        @if($package->description)
                            <p class="text-gray-600 mb-4">{{ Str::limit($package->description, 120) }}</p>
                        @endif

                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-1">Includes {{ $package->tests_count }} {{ Str::plural('test', $package->tests_count) }}</p>
                            @if($package->discount > 0)
                                <p class="text-sm text-gray-400 line-through">₹{{ number_format($package->price, 2) }}</p>
                            @endif
                            <p class="text-3xl font-bold text-indigo-600">₹{{ number_format($package->final_price, 2) }}</p>
                        </div>

                        <div class="mb-4">
                            <a href="{{ route('public.packages.show', $package) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No packages available at the moment.</p>
        </div>
    @endif
</div>
@endsection
