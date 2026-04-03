@extends('super-admin.layout')

@section('title', 'Create lab — Super Admin')
@section('page-title', 'Create lab')
@section('page-subtitle', 'Add a new diagnostic center to the platform.')

@section('content')
@php
    $input = 'block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20';
    $label = 'mb-1.5 block text-sm font-medium text-slate-700';
@endphp
<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-6 py-4">
        <h2 class="sa-display text-base font-semibold text-slate-900">Lab details</h2>
        <p class="mt-0.5 text-xs text-slate-500">A default admin user is created with the lab email and password <code class="rounded bg-slate-100 px-1 py-0.5 font-mono text-xs">password</code> — change it after first login.</p>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('super-admin.labs.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="{{ $label }}">Lab name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="{{ $input }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="email" class="{{ $label }}">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="{{ $input }}">
                        <p class="mt-1 text-xs text-slate-500">Used for the default admin login.</p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="{{ $label }}">Phone *</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="{{ $input }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="{{ $label }}">Address *</label>
                    <textarea name="address" id="address" rows="3" required class="{{ $input }}">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="level" class="{{ $label }}">Lab level *</label>
                        <select name="level" id="level" required class="{{ $input }}">
                            <option value="small" {{ old('level') === 'small' ? 'selected' : '' }}>Small</option>
                            <option value="large" {{ old('level') === 'large' ? 'selected' : '' }}>Large</option>
                        </select>
                        <p class="mt-2 text-xs text-slate-500"><span class="font-semibold text-slate-700">Small:</span> basic features. <span class="font-semibold text-slate-700">Large:</span> full features including technician management.</p>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="{{ $label }}">Status</label>
                        <select name="status" id="status" class="{{ $input }}">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="domain" class="{{ $label }}">Domain (optional)</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain') }}" placeholder="e.g. lab.example.com" class="{{ $input }}">
                    <p class="mt-1 text-xs text-slate-500">Custom hostname for this lab; you can add more later.</p>
                    @error('domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('super-admin.labs.index') }}" class="sa-icon-btn border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50" title="Cancel" aria-label="Cancel">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                <button type="submit" class="sa-icon-btn border border-violet-600 bg-violet-600 text-white shadow-sm hover:bg-violet-700" title="Create lab" aria-label="Create lab">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
