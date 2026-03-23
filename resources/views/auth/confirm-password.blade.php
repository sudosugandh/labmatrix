<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-slate-900 tracking-tight">{{ __('Confirm password') }}</h2>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            {{ __('This is a secure area. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
            <x-text-input id="password" class="auth-input mt-2 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-auth-primary-button>
                {{ __('Continue') }}
            </x-auth-primary-button>
        </div>
    </form>
</x-guest-layout>
