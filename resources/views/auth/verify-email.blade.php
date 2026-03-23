<x-guest-layout>
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-slate-900 tracking-tight">{{ __('Verify your email') }}</h2>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            {{ __('Thanks for signing up! Before you continue, please verify your email using the link we sent. If you didn’t receive it, we can send another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-auth-primary-button type="submit">
                {{ __('Resend verification email') }}
            </x-auth-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-600 underline-offset-4 transition-colors hover:text-slate-900 hover:underline">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
