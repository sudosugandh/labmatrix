@extends('website.layout')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden">
        <div class="px-8 pt-8 pb-6 border-b border-slate-100">
            <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Change booking type
            </a>
            <div class="flex justify-center mb-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold text-white" style="background: var(--primary-color);">1</span>
                    <span class="w-8 h-0.5 bg-slate-200"></span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold text-white" style="background: var(--primary-color);">2</span>
                </div>
            </div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-slate-900 mb-2 text-center">Book an Appointment</h1>
            <p class="text-slate-600 text-center">Fill in your details to schedule an appointment with us.</p>
        </div>
        <div class="p-8">

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('public.appointments.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="booking_type" value="{{ $bookingType ?? 'patient' }}">

            @if(isset($bookingType) && $bookingType === 'doctor')
            <!-- Doctor Information -->
            <div class="bg-gradient-to-r from-emerald-50/80 to-green-50/80 rounded-2xl p-6 mb-6 border border-emerald-200/80">
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
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_selection') border-red-500 @enderror"
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
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_id') border-red-500 @enderror">
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
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('referral_number') border-red-500 @enderror">
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
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_name') border-red-500 @enderror">
                            @error('doctor_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_phone" class="block text-sm font-medium text-gray-700 mb-2">Your Phone *</label>
                            <input type="text" name="doctor_phone" id="doctor_phone" value="{{ old('doctor_phone') }}"
                                placeholder="Enter your phone number"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_phone') border-red-500 @enderror">
                            @error('doctor_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_email" class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                            <input type="email" name="doctor_email" id="doctor_email" value="{{ old('doctor_email') }}"
                                placeholder="Enter your email"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_email') border-red-500 @enderror">
                            @error('doctor_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                            <input type="text" name="doctor_specialization" id="doctor_specialization" value="{{ old('doctor_specialization') }}"
                                placeholder="e.g., Cardiologist, General Physician"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_specialization') border-red-500 @enderror">
                            @error('doctor_specialization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="doctor_clinic" class="block text-sm font-medium text-gray-700 mb-2">Clinic Name</label>
                            <input type="text" name="doctor_clinic" id="doctor_clinic" value="{{ old('doctor_clinic') }}"
                                placeholder="Enter clinic name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('doctor_clinic') border-red-500 @enderror">
                            @error('doctor_clinic')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="referral_number_manual" class="block text-sm font-medium text-gray-700 mb-2">Referral Number (Optional)</label>
                            <input type="text" name="referral_number_manual" id="referral_number_manual" value="{{ old('referral_number_manual') }}"
                                placeholder="Enter referral number if any"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('referral_number_manual') border-red-500 @enderror">
                            @error('referral_number_manual')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Patient Information -->
            <div class="bg-slate-50/80 rounded-2xl p-6 mb-6 border border-slate-200/60">
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
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            placeholder="Enter your phone number"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email (Optional)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="Enter your email"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age (Optional)</label>
                        <input type="number" name="age" id="age" value="{{ old('age') }}" min="1" max="150"
                            placeholder="Enter your age"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('age') border-red-500 @enderror">
                        @error('age')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender (Optional)</label>
                        <select name="gender" id="gender"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('gender') border-red-500 @enderror">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if(!isset($bookingType) || $bookingType === 'patient')
            <!-- Referring Doctor Information (Optional for Patients) -->
            <div class="bg-blue-50/80 rounded-2xl p-6 mb-6 border border-blue-200/80">
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Referring Doctor Information (Optional)
                </h2>
                <p class="text-sm text-gray-600 mb-4">If you have a referral from a doctor, please provide their details below.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="referring_doctor_name" class="block text-sm font-medium text-gray-700 mb-2">Doctor's Name</label>
                        <input type="text" name="referring_doctor_name" id="referring_doctor_name" value="{{ old('referring_doctor_name') }}"
                            placeholder="Enter doctor's name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 @error('referring_doctor_name') border-red-500 @enderror">
                        @error('referring_doctor_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="referring_doctor_phone" class="block text-sm font-medium text-gray-700 mb-2">Doctor's Phone</label>
                        <input type="text" name="referring_doctor_phone" id="referring_doctor_phone" value="{{ old('referring_doctor_phone') }}"
                            placeholder="Enter doctor's phone number"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 @error('referring_doctor_phone') border-red-500 @enderror">
                        @error('referring_doctor_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Prescription Upload (Optional for Patients) -->
            <div class="rounded-2xl p-6 mb-6 border border-slate-200/80" style="background: color-mix(in srgb, var(--primary-color) 6%, white);">
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary-color);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Prescription (Optional)
                </h2>
                <p class="text-sm text-gray-600 mb-4">Upload your prescription or referral letter. Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                
                <div>
                    <label for="prescription_file" class="block text-sm font-medium text-gray-700 mb-2">Prescription File</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
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
            <div class="rounded-2xl p-6 mb-6 border border-slate-200/80" style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 8%, white) 0%, color-mix(in srgb, var(--primary-color) 4%, white) 100%);">
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
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('package_id') border-red-500 @enderror">
                        <option value="">No Package (General Appointment)</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->final_price }}" data-tests="{{ $package->tests_count }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
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

            <!-- Appointment Details -->
            <div class="bg-slate-50/80 rounded-2xl p-6 mb-6 border border-slate-200/60">
                <h2 class="font-display text-lg font-semibold text-slate-900 mb-4">Appointment Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date" 
                            value="{{ old('appointment_date') }}" 
                            min="{{ date('Y-m-d') }}"
                            required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('appointment_date') border-red-500 @enderror">
                        @error('appointment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Please select a date from today onwards</p>
                    </div>

                    <div>
                        @if(!empty($labUsesAppointmentSlots))
                            <label for="appointment_slot_id" class="block text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                            <select name="appointment_slot_id" id="appointment_slot_id" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('appointment_slot_id') border-red-500 @enderror">
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
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('appointment_time') border-red-500 @enderror">
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
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 @error('notes') border-red-500 @enderror"
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
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-xl mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> If your selected time slot is already booked, you'll be notified and can choose another time. We'll confirm your appointment shortly after booking.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center pt-6">
                <button type="submit" class="w-full md:w-auto py-4 px-10 rounded-xl text-lg font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%); box-shadow: 0 4px 20px color-mix(in srgb, var(--primary-color) 40%, transparent);">
                    Book Appointment
                </button>
            </div>
        </form>
    </div>
</div>

@if(isset($packages) && $packages->count() > 0)
<script>
    document.getElementById('package_id')?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const infoDiv = document.getElementById('package-info');
        
        if (this.value && selected.getAttribute('data-price')) {
            const testsCount = selected.getAttribute('data-tests');
            const price = selected.getAttribute('data-price');
            document.getElementById('package-tests-count').textContent = testsCount;
            document.getElementById('package-price').textContent = parseFloat(price).toFixed(2);
            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
    });
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
