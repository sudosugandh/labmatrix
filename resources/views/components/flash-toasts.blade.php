@php
    $flashToasts = [];
    $typeMap = ['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'];
    foreach ($typeMap as $sessionKey => $type) {
        if (session()->has($sessionKey)) {
            $flashToasts[] = [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => $type,
                'message' => session($sessionKey),
            ];
        }
    }
@endphp
@if (! empty($flashToasts))
    <div
        x-cloak
        x-data="createFlashToasts(@js($flashToasts))"
        class="fixed bottom-6 right-4 z-[100] flex w-[calc(100%-2rem)] max-w-md flex-col gap-3 pointer-events-none sm:right-8"
        aria-live="polite"
    >
        <template x-for="item in items" :key="item.id">
            <div
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-3 sm:translate-x-6"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 translate-x-3"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3.5 shadow-2xl ring-1 ring-black/5"
                :class="{
                    'border-emerald-200/90 bg-emerald-50/95 text-emerald-950': item.type === 'success',
                    'border-red-200/90 bg-red-50/95 text-red-950': item.type === 'error',
                    'border-amber-200/90 bg-amber-50/95 text-amber-950': item.type === 'warning',
                    'border-sky-200/90 bg-sky-50/95 text-sky-950': item.type === 'info',
                }"
            >
                <span class="relative mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/80 shadow-inner">
                    <svg x-show="item.type === 'success'" class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg x-show="item.type === 'error'" class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <svg x-show="item.type === 'warning'" class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <svg x-show="item.type === 'info'" class="h-5 w-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <p class="min-w-0 flex-1 text-sm font-medium leading-snug pt-0.5" x-text="item.message"></p>
                <button
                    type="button"
                    x-on:click="dismiss(item.id)"
                    class="shrink-0 rounded-lg p-1 text-current opacity-60 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-current/30"
                    aria-label="Dismiss"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>
@endif
