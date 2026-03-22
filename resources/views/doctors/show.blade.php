<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <x-avatar :name="$doctor->name" size="lg" />
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $doctor->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($doctor->specialization)
                            {{ $doctor->specialization }}
                        @endif
                        @if($doctor->qualification)
                            • {{ $doctor->qualification }}
                        @endif
                        @if($doctor->created_at)
                            • Added {{ $doctor->created_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctors.commissions', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    View Commissions
                </a>
                <a href="{{ route('doctors.edit', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
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

    <div class="space-y-6">
        <!-- Doctor Information -->
        <x-detail-card 
            title="Doctor Information" 
            gradient="from-indigo-500 to-purple-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </x-slot>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-detail-field 
                    label="Full Name" 
                    :value="$doctor->name"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Status" 
                >
                    <x-status-badge :status="$doctor->is_active ? 'active' : 'inactive'" />
                </x-detail-field>
                <x-detail-field 
                    label="Specialization" 
                    :value="$doctor->specialization ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Qualification" 
                    :value="$doctor->qualification ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Registration Number" 
                    :value="$doctor->registration_number ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Clinic Name" 
                    :value="$doctor->clinic_name ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Phone Number" 
                    :value="$doctor->phone ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Email Address" 
                    :value="$doctor->email ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
                <x-detail-field 
                    label="Address" 
                    :value="$doctor->address ?? 'N/A'"
                >
                    <x-slot name="icon">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </x-slot>
                </x-detail-field>
            </dl>
        </x-detail-card>

        <!-- Commission Information -->
        <x-detail-card 
            title="Commission Information" 
            gradient="from-green-500 to-emerald-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Commission Type</p>
                    <p class="text-2xl font-bold text-indigo-700">
                        {{ ucfirst($doctor->commission_type ?? 'N/A') }}
                    </p>
                    @if($doctor->commission_type)
                        <p class="text-sm text-gray-500 mt-1">
                            @if($doctor->commission_type === 'percentage')
                                {{ number_format($doctor->commission_rate, 2) }}%
                            @else
                                ₹{{ number_format($doctor->commission_rate, 2) }}
                            @endif
                        </p>
                    @endif
                </div>
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Total Earned</p>
                    <p class="text-2xl font-bold text-green-700">
                        ₹{{ number_format($commissionStats['total_earned'] ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Total Paid</p>
                    <p class="text-2xl font-bold text-purple-700">
                        ₹{{ number_format($commissionStats['total_paid'] ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Pending</p>
                    <p class="text-2xl font-bold text-yellow-700">
                        ₹{{ number_format($commissionStats['pending'] ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Awaiting payment</p>
                </div>
            </div>
        </x-detail-card>
    </div>
</x-app-layout>
