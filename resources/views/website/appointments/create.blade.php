@extends('website.layout')

@push('styles')
<style>
.booking-input {
    border-radius: 0.75rem;
    border: 1px solid rgb(226 232 240 / 0.9);
    padding: 0.75rem 1rem;
    min-height: 3rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
@media (max-width: 640px) {
    .booking-input { font-size: 1rem; }
}
.booking-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 18%, transparent);
}
</style>
@endpush

@section('content')
{{-- Premium Hero Section --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Change booking type
        </a>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-4" aria-label="Booking progress">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-emerald-200 ring-1 ring-emerald-400/30">
                <svg class="w-4 h-4 text-emerald-300 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Type chosen
            </span>
            <span class="hidden sm:inline text-white/30" aria-hidden="true">→</span>
            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-white ring-2 ring-white/40" style="background: color-mix(in srgb, var(--primary-color) 55%, transparent);">Step 2 · Details</span>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-3 [text-wrap:balance]">Book an Appointment</h1>
        <p class="text-base sm:text-lg md:text-xl text-slate-300 max-w-2xl leading-relaxed">Fill in your details below — it usually takes about 2 minutes. Your information is used only for this booking and your care.</p>
    </div>
</section>

{{-- Main Content --}}
<section class="relative bg-gradient-to-b from-slate-50 to-white py-16 md:py-24">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-100/50 via-transparent to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">
            {{-- Form Column --}}
            <div class="lg:col-span-2">

        @if(session('success'))
            <div class="mb-8 flex items-center gap-3 p-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 shadow-sm" role="alert">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(!empty($referralDoctor ?? null))
            <div class="mb-8 flex items-start gap-3 p-5 rounded-2xl bg-violet-50 border border-violet-200/80 text-violet-950 shadow-sm" role="status">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    @php
                        $refName = trim($referralDoctor->name);
                        $refByLine = preg_match('/^dr\.?\s/i', $refName) ? $refName : 'Dr. '.$refName;
                    @endphp
                    <p class="font-semibold text-violet-900">Referred by {{ $refByLine }}</p>
                    <p class="mt-1 text-sm text-violet-800/90">Your visit will be linked to this doctor for reporting and commission. You do not need to enter referring doctor details below.</p>
                </div>
            </div>
        @endif

        @if(!empty($payAtBookingSetupHint ?? ''))
            <div class="mb-8 flex items-start gap-3 p-5 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-950 shadow-sm" role="alert">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-amber-900">Payment at booking is not active yet</p>
                    <p class="mt-1 text-sm text-amber-900/90 leading-relaxed">{{ $payAtBookingSetupHint }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 flex items-start gap-3 p-5 rounded-2xl bg-red-50 border border-red-200/80 text-red-800 shadow-sm" role="alert">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="font-semibold">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('public.appointments.store') }}" class="space-y-8" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <input type="hidden" name="booking_type" value="{{ $bookingType ?? 'patient' }}">
            @if(request()->filled('ref'))
                <input type="hidden" name="ref" value="{{ old('ref', request('ref')) }}">
            @endif

            @if(isset($bookingType) && $bookingType === 'doctor')
            <!-- Doctor Information -->
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-emerald-100/60 to-transparent rounded-bl-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Doctor Information
                </h2>
                
                <!-- Doctor Selection -->
                <div class="mb-6">
                    <label for="doctor_selection" class="block text-sm font-medium text-gray-700 mb-2">Are you registered with us? *</label>
                    <select name="doctor_selection" id="doctor_selection" required
                        class="block w-full booking-input @error('doctor_selection') border-red-500 @enderror"
                        onchange="toggleDoctorFields()">
                        <option value="">Select an option</option>
                        <option value="registered" {{ old('doctor_selection') == 'registered' ? 'selected' : '' }}>Yes, I'm registered</option>
                        <option value="not_registered" {{ old('doctor_selection') == 'not_registered' ? 'selected' : '' }}>No, I'm not registered yet</option>
                    </select>
                    @error('doctor_selection')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Registered Doctor Selection -->
                <div id="registered_doctor_fields">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Select Your Name *</label>
                            <select name="doctor_id" id="doctor_id"
                                class="block w-full booking-input @error('doctor_id') border-red-500 @enderror">
                                <option value="">Select Your Name</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} 
                                        @if($doctor->specialization)
                                            - {{ $doctor->specialization }}
                                        @endif
                                        @if($doctor->clinic_name)
                                            ({{ $doctor->clinic_name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="referral_number" class="block text-sm font-medium text-gray-700 mb-2">Referral Number (Optional)</label>
                            <input type="text" name="referral_number" id="referral_number" value="{{ old('referral_number') }}"
                                placeholder="Enter referral number if any"
                                class="block w-full booking-input @error('referral_number') border-red-500 @enderror">
                            @error('referral_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Not Registered Doctor - Manual Entry -->
                <div id="not_registered_doctor_fields" style="display: none;">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Note:</strong> Your details will be submitted for review. You'll be registered after admin approval. Commission will be calculated once you're activated.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="doctor_name" class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                            <input type="text" name="doctor_name" id="doctor_name" value="{{ old('doctor_name') }}"
                                placeholder="Enter your full name"
                                class="block w-full booking-input @error('doctor_name') border-red-500 @enderror">
                            @error('doctor_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_phone" class="block text-sm font-medium text-gray-700 mb-2">Your Phone *</label>
                            <input type="text" name="doctor_phone" id="doctor_phone" value="{{ old('doctor_phone') }}"
                                placeholder="Enter your phone number"
                                class="block w-full booking-input @error('doctor_phone') border-red-500 @enderror">
                            @error('doctor_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_email" class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                            <input type="email" name="doctor_email" id="doctor_email" value="{{ old('doctor_email') }}"
                                placeholder="Enter your email"
                                class="block w-full booking-input @error('doctor_email') border-red-500 @enderror">
                            @error('doctor_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                            <input type="text" name="doctor_specialization" id="doctor_specialization" value="{{ old('doctor_specialization') }}"
                                placeholder="e.g., Cardiologist, General Physician"
                                class="block w-full booking-input @error('doctor_specialization') border-red-500 @enderror">
                            @error('doctor_specialization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_clinic" class="block text-sm font-medium text-gray-700 mb-2">Clinic Name</label>
                            <input type="text" name="doctor_clinic" id="doctor_clinic" value="{{ old('doctor_clinic') }}"
                                placeholder="Enter clinic name"
                                class="block w-full booking-input @error('doctor_clinic') border-red-500 @enderror">
                            @error('doctor_clinic')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="referral_number_manual" class="block text-sm font-medium text-gray-700 mb-2">Referral Number (Optional)</label>
                            <input type="text" name="referral_number_manual" id="referral_number_manual" value="{{ old('referral_number_manual') }}"
                                placeholder="Enter referral number if any"
                                class="block w-full booking-input @error('referral_number_manual') border-red-500 @enderror">
                            @error('referral_number_manual')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Patient Information -->
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-gradient-to-bl from-slate-100/80 to-transparent rounded-full pointer-events-none"></div>
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4">
                    @if(isset($bookingType) && $bookingType === 'doctor')
                        Patient Information
                    @else
                        Your Information
                    @endif
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Enter your full name"
                            class="block w-full booking-input @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            placeholder="Enter your phone number"
                            class="block w-full booking-input @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email (Optional)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="Enter your email"
                            class="block w-full booking-input @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age <span class="text-red-600">*</span></label>
                        <input type="number" name="age" id="age" value="{{ old('age') }}" min="1" max="150" required
                            placeholder="Enter your age"
                            class="block w-full booking-input @error('age') border-red-500 @enderror">
                        @error('age')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-600">*</span></label>
                        <select name="gender" id="gender" required
                            class="block w-full booking-input @error('gender') border-red-500 @enderror">
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-600">*</span></label>
                        <textarea name="address" id="address" rows="3" maxlength="2000" required
                            placeholder="e.g. 42, MG Road, Indiranagar, Bengaluru, Karnataka 560038"
                            class="block w-full booking-input py-3 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Used for home collection coordination and printed on PDF reports.</p>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if(!isset($bookingType) || $bookingType === 'patient')
            @if(!empty($referralDoctor ?? null))
            <!-- Referring doctor set via ?ref= link: show read-only details, no manual fields -->
            <div class="relative bg-white rounded-2xl p-6 sm:p-8 shadow-xl shadow-slate-200/40 border border-violet-100 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-violet-50/90 to-transparent rounded-bl-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-violet-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Referring doctor
                </h2>
                <p class="text-sm text-slate-600 mb-5">Pulled from your referral link — already linked to your booking.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 px-4 py-3">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Doctor</p>
                        <p class="font-semibold text-slate-900">{{ $referralDoctor->name }}</p>
                    </div>
                    @if(!empty($referralDoctor->phone))
                    <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 px-4 py-3">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Phone</p>
                        <p class="font-semibold text-slate-900 tabular-nums">{{ $referralDoctor->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <!-- Referring Doctor Information (Optional for Patients) — only when not using a referral link -->
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-bl from-blue-50/80 to-transparent rounded-bl-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Referring Doctor Information (Optional)
                </h2>
                <p class="text-sm text-gray-600 mb-4">Choose your referring doctor from the list, or pick <strong class="font-medium text-slate-800">Other doctor</strong> to enter a name. New doctors you add are saved for the lab to review and appear in the list once approved.</p>

                <div class="space-y-6"
                    x-data="{
                        refChoice: @js((string) old('referring_doctor_select', '')),
                        refPhone: @js((string) old('referring_doctor_phone', '')),
                        hintListed: @js(__('Choosing a doctor from the list fills in their number; you can edit it. For other doctor, add a phone if you know it.')),
                        hintOther: @js(__('Add a phone if you have it — it helps the lab reach the doctor.')),
                        onRefChange(ev) {
                            const v = this.refChoice;
                            if (this.$refs.phoneHint) {
                                this.$refs.phoneHint.textContent = (v === 'other') ? this.hintOther : this.hintListed;
                            }
                            const target = ev && ev.target ? ev.target : this.$refs.refSel;
                            if (v && v !== 'other' && target && target.selectedOptions && target.selectedOptions[0]) {
                                this.refPhone = target.selectedOptions[0].getAttribute('data-phone') || '';
                            } else if (v === '') {
                                this.refPhone = '';
                            }
                        },
                        initReferringDoctor() {
                            const sel = this.$refs.refSel;
                            if (! sel) return;
                            if (this.refChoice && this.refChoice !== 'other' && ! String(this.refPhone || '').trim()) {
                                const opt = sel.selectedOptions[0];
                                if (opt) this.refPhone = opt.getAttribute('data-phone') || '';
                            }
                            if (this.$refs.phoneHint) {
                                this.$refs.phoneHint.textContent = (this.refChoice === 'other') ? this.hintOther : this.hintListed;
                            }
                        }
                    }"
                    x-init="$nextTick(() => initReferringDoctor())">
                    <div>
                        <label for="referring_doctor_select" class="block text-sm font-medium text-gray-700 mb-2">Referring doctor</label>
                        <select name="referring_doctor_select" id="referring_doctor_select" x-ref="refSel"
                            x-model="refChoice"
                            @change="onRefChange($event)"
                            class="block w-full booking-input @error('referring_doctor_select') border-red-500 @enderror">
                            <option value="">{{ __('No referring doctor') }}</option>
                            @foreach($referringDoctors ?? [] as $refDoc)
                                <option value="{{ $refDoc->id }}" data-phone="{{ e($refDoc->phone ?? '') }}"
                                    {{ (string) old('referring_doctor_select') === (string) $refDoc->id ? 'selected' : '' }}>
                                    {{ $refDoc->name }}@if($refDoc->phone) — {{ $refDoc->phone }}@endif
                                </option>
                            @endforeach
                            <option value="other" {{ old('referring_doctor_select') === 'other' ? 'selected' : '' }}>{{ __('Other doctor (enter name below)') }}</option>
                        </select>
                        @error('referring_doctor_select')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="referring_doctor_other_wrap" x-show="refChoice === 'other'" x-cloak class="space-y-2">
                        <label for="referring_doctor_custom_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __("Other doctor's name") }} <span class="text-red-600">*</span></label>
                        <input type="text" name="referring_doctor_custom_name" id="referring_doctor_custom_name" value="{{ old('referring_doctor_custom_name') }}"
                            placeholder="{{ __('Enter the doctor\'s full name') }}"
                            class="block w-full booking-input @error('referring_doctor_custom_name') border-red-500 @enderror"
                            autocomplete="name">
                        @error('referring_doctor_custom_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="referring_doctor_phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __("Doctor's phone") }} <span class="text-slate-400 font-normal">({{ __('optional') }})</span></label>
                        <input type="text" name="referring_doctor_phone" id="referring_doctor_phone"
                            x-model="refPhone"
                            placeholder="{{ __('Phone number') }}"
                            class="block w-full booking-input @error('referring_doctor_phone') border-red-500 @enderror"
                            inputmode="tel" autocomplete="tel">
                        @error('referring_doctor_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" x-ref="phoneHint">{{ __('Choosing a doctor from the list fills in their number; you can edit it. For other doctor, add a phone if you know it.') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Prescription Upload (Optional for Patients) -->
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden" style="background: linear-gradient(135deg, white 0%, color-mix(in srgb, var(--primary-color) 5%, white) 100%);">
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary-color);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Prescription (Optional)
                </h2>
                <p class="text-sm text-gray-600 mb-4">Upload your prescription or referral letter. Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                
                <div>
                    <label for="prescription_file" class="block text-sm font-medium text-gray-700 mb-2">Prescription File</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200/80 border-dashed rounded-xl hover:border-[var(--primary-color)]/50 hover:bg-slate-50/50 transition-all duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="prescription_file" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                    <span>Upload a file</span>
                                    <input type="file" name="prescription_file" id="prescription_file" accept=".pdf,.jpg,.jpeg,.png"
                                        class="sr-only" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, PNG, JPG up to 5MB</p>
                            <p id="file-name" class="text-sm text-gray-600 mt-2"></p>
                        </div>
                    </div>
                    @error('prescription_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Package Selection (Optional) -->
            @if(isset($packages) && $packages->count() > 0)
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden" style="background: linear-gradient(135deg, white 0%, color-mix(in srgb, var(--primary-color) 6%, white) 50%, color-mix(in srgb, var(--primary-color) 3%, white) 100%);">
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary-color);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Select Test Package (Optional)
                </h2>
                <p class="text-sm text-gray-600 mb-4">Choose a health checkup package for this appointment. Test orders will be created automatically.</p>
                
                <div>
                    <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Package</label>
                    <select name="package_id" id="package_id"
                        class="block w-full booking-input @error('package_id') border-red-500 @enderror">
                        <option value="">No Package (General Appointment)</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->final_price }}" data-tests="{{ $package->tests_count }}" {{ old('package_id', request('package')) == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - ₹{{ number_format($package->final_price, 2) }} ({{ $package->tests_count }} tests)
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="package-info" class="mt-3 p-3 bg-white rounded-lg border border-indigo-200" style="display: none;">
                        <p class="text-sm text-gray-700">
                            <strong>Package includes:</strong> <span id="package-tests-count"></span> tests
                            <br>
                            <strong>Total Price:</strong> ₹<span id="package-price"></span>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($tests) && $tests->count() > 0)
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden" id="individual-tests-section">
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-2 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary-color);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Individual tests (optional)
                </h2>
                <p class="text-sm text-slate-600 mb-4">Select one or more tests, <strong>or</strong> choose a package above — not both. Leave empty for a general visit without pre-selected tests.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-72 overflow-y-auto pr-1 rounded-xl border border-slate-100 bg-slate-50/50 p-3">
                    @foreach($tests as $test)
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-slate-300 transition-colors has-[:checked]:border-violet-500 has-[:checked]:ring-1 has-[:checked]:ring-violet-200">
                            <input type="checkbox" name="test_ids[]" value="{{ $test->id }}" data-price="{{ $test->price }}"
                                class="test-select-cb mt-1 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                                {{ is_array(old('test_ids')) && in_array($test->id, old('test_ids', []), true) ? 'checked' : '' }}>
                            <span class="text-sm text-slate-800"><span class="font-medium">{{ $test->name }}</span><br><span class="text-slate-500 tabular-nums">₹{{ number_format($test->price, 2) }}</span></span>
                        </label>
                    @endforeach
                </div>
                <p id="booking-estimated-line" class="mt-3 text-sm font-semibold text-slate-900" style="display: none;"></p>
                @if(!empty($payAtBookingEnabled))
                    <p class="mt-3 text-xs text-amber-900 bg-amber-50 border border-amber-200/80 rounded-xl px-3 py-2 leading-relaxed">
                        <strong>Pay online:</strong> If you select a package or tests, you may be redirected to complete payment (card/UPI) before your confirmation page, when your lab has enabled this option.
                    </p>
                @endif
                @error('test_ids')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- Appointment Details -->
            <div class="relative bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/40 border border-slate-200/60 overflow-hidden">
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-slate-50/90 to-transparent rounded-tr-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4">Appointment Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date" 
                            value="{{ old('appointment_date') }}" 
                            min="{{ date('Y-m-d') }}"
                            required
                            class="block w-full booking-input @error('appointment_date') border-red-500 @enderror">
                        @error('appointment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Please select a date from today onwards</p>
                    </div>

                    <div>
                        @if(!empty($labUsesAppointmentSlots))
                            <label for="appointment_slot_id" class="block text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                            <select name="appointment_slot_id" id="appointment_slot_id" required
                                class="block w-full booking-input @error('appointment_slot_id') border-red-500 @enderror">
                                <option value="">{{ old('appointment_date') ? __('Loading…') : __('Select a date first') }}</option>
                            </select>
                            @error('appointment_slot_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Times are set by the lab. Pick a date to see availability.</p>
                        @else
                            <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                            <input type="time" name="appointment_time" id="appointment_time" 
                                value="{{ old('appointment_time') }}" 
                                required
                                class="block w-full booking-input @error('appointment_time') border-red-500 @enderror">
                            @error('appointment_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($errors->has('appointment_time') && !$errors->has('appointment_time.required'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('appointment_time') }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500">Recommended: 9:00 AM - 6:00 PM</p>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="block w-full booking-input @error('notes') border-red-500 @enderror"
                        placeholder="Any special requirements or notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Helpful Information -->
            <div class="bg-slate-50/90 border border-slate-200/80 p-5 rounded-2xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-slate-700">
                            <strong>Note:</strong> If your selected time slot is already booked, you'll be notified and can choose another time. We'll confirm your appointment shortly after booking.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center pt-4 gap-2">
                <button type="submit" class="w-full md:w-auto min-h-[52px] py-4 px-12 rounded-2xl text-base sm:text-lg font-semibold text-white shadow-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:pointer-events-none disabled:translate-y-0 touch-manipulation" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%); box-shadow: 0 4px 24px color-mix(in srgb, var(--primary-color) 45%, transparent);" :disabled="submitting">
                    <span x-show="!submitting" class="inline-flex items-center justify-center gap-2">Book appointment</span>
                    <span x-show="submitting" x-cloak class="inline-flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Submitting…
                    </span>
                </button>
                <p class="text-xs text-slate-500 text-center max-w-md" x-show="!submitting">Please wait on this page after tapping submit — do not refresh.</p>
            </div>
        </form>
            </div>

            {{-- Sidebar: Trust & Benefits --}}
            <aside class="lg:col-span-1 space-y-6">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/40 border border-slate-200/60">
                        <h3 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold" style="background: var(--primary-color);">✓</span>
                            Why Book With Us
                        </h3>
                        <ul class="space-y-3 text-slate-600 text-sm">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Accurate, NABL-accredited lab results</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Fast turnaround with digital reports</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Home collection available (where offered)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Secure, confidential handling</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white">
                        <h3 class="font-display text-lg font-semibold mb-3">Need Help?</h3>
                        <p class="text-slate-300 text-sm mb-4">Our team is here to assist you with bookings and reports.</p>
                        <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-white/90 hover:text-white transition-colors">
                            Contact Us
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@if(isset($packages) && $packages->count() > 0)
<script>
    (function () {
        const sel = document.getElementById('package_id');
        if (!sel) return;
        function updatePackageInfo() {
            const selected = sel.options[sel.selectedIndex];
            const infoDiv = document.getElementById('package-info');
            if (!infoDiv) return;
            if (sel.value && selected.getAttribute('data-price')) {
                const testsCount = selected.getAttribute('data-tests');
                const price = selected.getAttribute('data-price');
                document.getElementById('package-tests-count').textContent = testsCount;
                document.getElementById('package-price').textContent = parseFloat(price).toFixed(2);
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
        }
        sel.addEventListener('change', updatePackageInfo);
        updatePackageInfo();
    })();
</script>
@endif

@if(isset($tests) && $tests->count() > 0)
<script>
(function () {
    const pkg = document.getElementById('package_id');
    const cbs = document.querySelectorAll('.test-select-cb');
    const line = document.getElementById('booking-estimated-line');

    function sumTests() {
        let t = 0;
        cbs.forEach(function (cb) {
            if (cb.checked) t += parseFloat(cb.getAttribute('data-price') || '0', 10) || 0;
        });
        return t;
    }

    function refresh() {
        const pkgPrice = pkg && pkg.value && pkg.selectedOptions[0]
            ? parseFloat(pkg.selectedOptions[0].getAttribute('data-price') || '0', 10)
            : 0;
        const anyTest = Array.from(cbs).some(function (cb) { return cb.checked; });

        if (pkg && pkg.value) {
            cbs.forEach(function (cb) { cb.checked = false; });
            if (line) {
                line.style.display = 'block';
                line.textContent = 'Estimated (package): ₹' + pkgPrice.toFixed(2);
            }
            return;
        }
        const s = sumTests();
        if (line) {
            if (s > 0) {
                line.style.display = 'block';
                line.textContent = 'Estimated (selected tests): ₹' + s.toFixed(2);
            } else {
                line.style.display = 'none';
                line.textContent = '';
            }
        }
    }

    if (pkg) {
        pkg.addEventListener('change', function () {
            if (this.value) {
                cbs.forEach(function (cb) { cb.checked = false; });
            }
            refresh();
        });
    }
    cbs.forEach(function (cb) {
        cb.addEventListener('change', function () {
            if (cb.checked && pkg) {
                pkg.value = '';
                const info = document.getElementById('package-info');
                if (info) info.style.display = 'none';
            }
            refresh();
        });
    });
    refresh();
})();
</script>
@endif

<script>
    function updateFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('file-name').textContent = fileName ? 'Selected: ' + fileName : '';
    }
</script>

@if(isset($bookingType) && $bookingType === 'doctor')
<script>
    function toggleDoctorFields() {
        const selection = document.getElementById('doctor_selection').value;
        const registeredFields = document.getElementById('registered_doctor_fields');
        const notRegisteredFields = document.getElementById('not_registered_doctor_fields');
        const doctorIdField = document.getElementById('doctor_id');
        const doctorNameField = document.getElementById('doctor_name');
        const doctorPhoneField = document.getElementById('doctor_phone');
        
        if (selection === 'registered') {
            registeredFields.style.display = 'block';
            notRegisteredFields.style.display = 'none';
            if (doctorIdField) doctorIdField.required = true;
            if (doctorNameField) doctorNameField.required = false;
            if (doctorPhoneField) doctorPhoneField.required = false;
        } else if (selection === 'not_registered') {
            registeredFields.style.display = 'none';
            notRegisteredFields.style.display = 'block';
            if (doctorIdField) doctorIdField.required = false;
            if (doctorNameField) doctorNameField.required = true;
            if (doctorPhoneField) doctorPhoneField.required = true;
        } else {
            registeredFields.style.display = 'none';
            notRegisteredFields.style.display = 'none';
            if (doctorIdField) doctorIdField.required = false;
            if (doctorNameField) doctorNameField.required = false;
            if (doctorPhoneField) doctorPhoneField.required = false;
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleDoctorFields();
    });
</script>
@endif

@if(!empty($labUsesAppointmentSlots))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointment_date');
    const slotSelect = document.getElementById('appointment_slot_id');
    if (!dateInput || !slotSelect) return;
    const url = @json(route('public.appointments.available-slots'));
    const oldSlotId = @json(old('appointment_slot_id'));
    function loadSlots() {
        const d = dateInput.value;
        if (!d) {
            slotSelect.innerHTML = '<option value="">Select a date first</option>';
            return;
        }
        slotSelect.innerHTML = '<option value="">Loading…</option>';
        slotSelect.disabled = true;
        fetch(url + '?date=' + encodeURIComponent(d), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                slotSelect.innerHTML = '<option value="">Select a time</option>';
                (data.slots || []).forEach(function(s) {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.label + (s.full ? ' (full)' : '');
                    if (s.full) opt.disabled = true;
                    slotSelect.appendChild(opt);
                });
                if (oldSlotId) {
                    slotSelect.value = String(oldSlotId);
                }
                slotSelect.disabled = false;
            })
            .catch(function() {
                slotSelect.innerHTML = '<option value="">Could not load times</option>';
                slotSelect.disabled = false;
            });
    }
    dateInput.addEventListener('change', loadSlots);
    if (dateInput.value) loadSlots();
});
</script>
@endif
@endsection
