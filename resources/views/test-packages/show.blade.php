<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $testPackage->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Package Details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('test-packages.edit', $testPackage) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    Edit Package
                </a>
                <a href="{{ route('test-packages.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                    ← Back to Packages
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Package Information</h3>
                
                @if($testPackage->description)
                    <div class="mb-4">
                        <p class="text-gray-700">{{ $testPackage->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Base Price</span>
                        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($testPackage->price, 2) }}</p>
                    </div>
                    @if($testPackage->discount > 0)
                        <div>
                            <span class="text-sm text-gray-500">Discount</span>
                            <p class="text-lg font-semibold text-red-600">{{ $testPackage->discount }}%</p>
                        </div>
                    @endif
                    <div class="col-span-2">
                        <span class="text-sm text-gray-500">Final Price</span>
                        <p class="text-2xl font-bold text-indigo-600">₹{{ number_format($testPackage->final_price, 2) }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $testPackage->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $testPackage->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Tests in Package ({{ $testPackage->tests->count() }})</h3>
                
                @if($testPackage->tests->count() > 0)
                    <div class="space-y-3">
                        @foreach($testPackage->tests as $test)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $test->name }}</h4>
                                        @if($test->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $test->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <span class="text-sm font-semibold text-indigo-600">₹{{ number_format($test->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No tests added to this package yet.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Package Statistics</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Total Tests</span>
                        <p class="text-2xl font-bold text-indigo-600">{{ $testPackage->tests->count() }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Individual Price Total</span>
                        <p class="text-xl font-semibold text-gray-900">
                            ₹{{ number_format($testPackage->tests->sum('price'), 2) }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Savings</span>
                        <p class="text-xl font-semibold text-green-600">
                            ₹{{ number_format($testPackage->tests->sum('price') - $testPackage->final_price, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('test-packages.edit', $testPackage) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Edit Package
                    </a>
                    <form method="POST" action="{{ route('test-packages.destroy', $testPackage) }}" onsubmit="return confirm('Are you sure you want to delete this package?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Delete Package
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
