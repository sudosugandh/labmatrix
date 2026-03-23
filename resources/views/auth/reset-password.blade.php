<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-slate-900 tracking-tight">{{ __('New password') }}</h2>
        <p class="mt-2 text-sm text-slate-600">{{ __('Choose a strong password for your account.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="auth-input mt-2 block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
            <x-text-input id="password" class="auth-input mt-2 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" class="text-slate-700" />
            <x-text-input id="password_confirmation" class="auth-input mt-2 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-auth-primary-button>
                {{ __('Reset password') }}
            </x-auth-primary-button>
        </div>
    </form>
</x-guest-layout>
