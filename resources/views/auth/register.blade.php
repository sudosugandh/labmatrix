<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-slate-900 tracking-tight">{{ __('Create account') }}</h2>
        <p class="mt-2 text-sm text-slate-600">{{ __('Enter your details to register.') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-slate-700" />
            <x-text-input id="name" class="auth-input mt-2 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="auth-input mt-2 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
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

        <div class="space-y-4 pt-2">
            <x-auth-primary-button>
                {{ __('Register') }}
            </x-auth-primary-button>
            <p class="text-center text-sm text-slate-600">
                <a class="font-medium text-violet-600 transition-colors hover:text-violet-700" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
