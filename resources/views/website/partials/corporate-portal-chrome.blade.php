{{--
  Employer / corporate portal shell.
  @param string $title
  @param string|null $subtitle
  @param string $active  dashboard|camp
  @param array|null $back  e.g. ['url' => route(...), 'label' => 'All camps']
--}}
@php
    $back = $back ?? [];
    $tabs = [
        'dashboard' => ['label' => 'Camps', 'route' => route('corporate.portal.dashboard')],
    ];
@endphp
<div class="border-b border-slate-200/90 bg-gradient-to-b from-slate-50 via-white to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-0 sm:pt-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500 mb-2 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-[color-mix(in_srgb,var(--primary-color)_12%,transparent)] text-[color:var(--primary-color)]" aria-hidden="true">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </span>
                    Employer portal
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 font-display tracking-tight">{{ $title }}</h1>
                @if(! empty($subtitle))
                    <p class="mt-2 text-sm text-slate-600 max-w-2xl leading-relaxed">{{ $subtitle }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('corporate.portal.logout') }}" class="shrink-0 w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-color)] focus:ring-offset-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign out
                </button>
            </form>
        </div>

        @if(! empty($back['url']))
            <div class="mt-6 pb-2">
                <a href="{{ $back['url'] }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[color:var(--primary-color)] hover:underline">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    {{ $back['label'] ?? 'Back' }}
                </a>
            </div>
            <div class="h-px bg-slate-200/90 mt-2" aria-hidden="true"></div>
        @elseif(($active ?? '') === 'dashboard')
            <nav class="mt-8 flex gap-1 overflow-x-auto pb-px -mb-px" aria-label="Employer portal">
                <span class="shrink-0 whitespace-nowrap rounded-t-lg px-4 py-3 text-sm font-semibold border-b-2 border-[color:var(--primary-color)] text-[color:var(--primary-color)] bg-white shadow-[0_-1px_0_0_white]">
                    {{ $tabs['dashboard']['label'] }}
                </span>
            </nav>
        @endif
    </div>
</div>
