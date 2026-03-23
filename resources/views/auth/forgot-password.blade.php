<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-slate-900 tracking-tight">{{ __('Reset password') }}</h2>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            {{ __('Forgot your password? No problem. Enter your email and we’ll send a link to choose a new one.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="auth-input mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-auth-primary-button>
                {{ __('Email password reset link') }}
            </x-auth-primary-button>
        </div>

        <p class="text-center text-sm text-slate-600">
            <a href="{{ route('login') }}" class="font-medium text-violet-600 hover:text-violet-700">{{ __('Back to sign in') }}</a>
        </p>
    </form>
</x-guest-layout>
