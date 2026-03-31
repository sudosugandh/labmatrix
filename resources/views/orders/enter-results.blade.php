@php
    use Illuminate\Support\Str;

    $orderLabel = '#' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
    $initials = Str::of($order->patient->name ?? '')
        ->trim()
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn ($w) => Str::substr($w, 0, 1))
        ->implode('');
    $initials = $initials !== '' ? Str::upper($initials) : '?';
@endphp
<x-app-layout>
    <style>
        .result-entry-shell {
            --re-accent: #059669;
            --re-accent-deep: #064e3b;
            --re-surface: #fafaf9;
        }
        .result-entry-shell .result-input {
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .result-entry-shell .result-input:hover:not(:focus) {
            border-color: rgb(203 213 225 / 0.95);
            background-color: rgb(255 255 255);
        }
        .result-entry-shell .result-input:focus {
            outline: none;
            border-color: rgb(16 185 129 / 0.6);
            box-shadow: 0 0 0 3px rgb(16 185 129 / 0.12);
            background-color: #fff;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0 space-y-1">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-800/80">Results workspace</p>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Result entry</h2>
                <p class="text-sm text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <span class="font-mono text-slate-700 tabular-nums">{{ $orderLabel }}</span>
                    <span class="text-slate-300">·</span>
                    <span class="text-slate-700">{{ $order->test->name }}</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('orders.show', $order) }}"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200/90 bg-white text-slate-700 shadow-sm hover:border-slate-300 hover:shadow-md hover:bg-slate-50/90 transition-all duration-200">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Order details
                </a>
                <a href="{{ route('orders.index') }}"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200/90 bg-white text-slate-700 shadow-sm hover:border-slate-300 hover:shadow-md hover:bg-slate-50/90 transition-all duration-200">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"></path>
                    </svg>
                    All orders
                </a>
            </div>
        </div>
    </x-slot>

    <div
        class="result-entry-shell max-w-5xl mx-auto space-y-5 pb-32 sm:pb-10 bg-gradient-to-b from-stone-50/80 via-white to-emerald-50/20 -mx-4 sm:mx-auto px-4 sm:px-0 sm:rounded-3xl sm:py-1">

        {{-- Patient context --}}
        <div
            class="sticky top-0 z-30 relative overflow-hidden rounded-2xl border border-white/70 bg-white/85 backdrop-blur-md shadow-lg shadow-slate-300/25 ring-1 ring-slate-200/60">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-400/40 to-transparent"></div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5">
                <div class="flex items-start gap-4 min-w-0">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white text-sm font-bold shadow-md shadow-emerald-900/20 ring-2 ring-white">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-lg font-semibold text-slate-900 tracking-tight truncate">{{ $order->patient->name }}</p>
                        <p class="mt-0.5 text-sm text-slate-600">
                            <span class="tabular-nums">{{ $order->patient->phone }}</span>
                            <span class="mx-1.5 text-slate-300">·</span>
                            {{ $order->patient->age ?? '—' }} yrs
                            <span class="mx-1.5 text-slate-300">·</span>
                            {{ $order->patient->gender ?? '—' }}
                        </p>
                    </div>
                </div>
                <div class="sm:ml-auto flex flex-wrap items-center gap-2 sm:justify-end">
                    <span
                        class="inline-flex items-center rounded-full border border-slate-200/80 bg-slate-50/80 px-3 py-1 text-xs font-medium text-slate-600 tabular-nums">
                        Order {{ $orderLabel }}
                    </span>
                    <span class="hidden sm:inline text-slate-200">|</span>
                    <span
                        class="inline-flex max-w-full items-center rounded-full border border-emerald-200/60 bg-emerald-50/50 px-3 py-1 text-xs font-semibold text-emerald-900 truncate">
                        {{ $order->test->name }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Parameters --}}
        <div
            class="rounded-2xl overflow-hidden border border-slate-200/80 bg-white shadow-xl shadow-slate-400/15 ring-1 ring-slate-200/50">
            <div
                class="relative px-5 py-4 sm:px-6 sm:py-5 bg-gradient-to-br from-[#0a3d2e] via-emerald-900 to-[#052e16] text-white">
                <div class="absolute inset-0 opacity-[0.07] pointer-events-none"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                </div>
                <div class="relative flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold tracking-tight">Parameters</h3>
                        <p class="mt-1.5 text-sm text-emerald-100/90 max-w-xl leading-relaxed">
                            Type values and use <kbd class="px-1.5 py-0.5 rounded bg-white/10 font-mono text-xs">Tab</kbd>
                            to move down. Flags follow reference low/high (same as on save).
                        </p>
                    </div>
                    <div class="hidden sm:block text-right text-emerald-200/80 text-xs font-medium uppercase tracking-wider">
                        Live validation
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-amber-400/0 via-amber-300/50 to-amber-400/0"></div>
            </div>

            <form method="POST" action="{{ route('orders.save-results', $order) }}" id="results-form" class="relative">
                @csrf
                <input type="hidden" name="next" id="save-next" value="stay">

                @if ($results->count() > 0)
                    <div class="overflow-x-auto max-h-[min(58vh,520px)] overflow-y-auto [scrollbar-gutter:stable]">
                        <table class="min-w-full text-[13px] leading-snug">
                            <thead
                                class="sticky top-0 z-10 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500 bg-stone-50/95 backdrop-blur-sm border-b border-slate-200/90 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3.5 w-[28%] pl-5">Parameter</th>
                                    <th class="px-4 py-3.5 w-[22%]">Value</th>
                                    <th class="px-4 py-3.5 w-[12%]">Unit</th>
                                    <th class="px-4 py-3.5 w-[22%]">Reference</th>
                                    <th class="px-4 py-3.5 w-[16%] pr-5">Flag</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/90">
                                @foreach ($results as $result)
                                    @php
                                        $tp = $result->testParameter;
                                        $low =
                                            $tp && $tp->reference_range_low !== null
                                                ? (float) $tp->reference_range_low
                                                : null;
                                        $high =
                                            $tp && $tp->reference_range_high !== null
                                                ? (float) $tp->reference_range_high
                                                : null;
                                    @endphp
                                    <tr data-row-index="{{ $loop->index }}"
                                        class="result-row group border-l-[3px] border-l-transparent transition-colors duration-150 hover:bg-slate-50/50 {{ $result->status === 'critical' ? 'bg-red-50/35 border-l-red-500' : ($result->status === 'high' || $result->status === 'low' ? 'bg-amber-50/25 border-l-amber-400' : ($result->result_value !== null && $result->result_value !== '' && $result->status === 'normal' ? 'border-l-emerald-400/70' : '')) }}">
                                        <td class="px-4 py-3 align-middle pl-5">
                                            <span class="font-semibold text-slate-800">{{ $result->parameter_name }}</span>
                                            @if ($tp && $tp->method_name)
                                                <span class="block text-[11px] text-slate-500 mt-0.5 font-normal">{{ $tp->method_name }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-middle">
                                            <input type="text" inputmode="decimal" autocomplete="off"
                                                name="results[{{ $loop->index }}][result_value]"
                                                value="{{ old('results.' . $loop->index . '.result_value', $result->result_value) }}"
                                                data-row="{{ $loop->index }}" data-ref-low="{{ $low !== null ? $low : '' }}"
                                                data-ref-high="{{ $high !== null ? $high : '' }}"
                                                class="result-input block w-full min-w-[7rem] rounded-xl border border-slate-200/90 bg-slate-50/40 px-3 py-2 text-slate-900 font-medium tabular-nums text-sm placeholder:text-slate-400"
                                                placeholder="—"
                                                @if ($loop->first) autofocus @endif>
                                            <input type="hidden" name="results[{{ $loop->index }}][result_id]"
                                                value="{{ $result->id }}">
                                        </td>
                                        <td class="px-4 py-3 text-slate-600 align-middle tabular-nums">{{ $result->unit ?? '—' }}</td>
                                        <td class="px-4 py-3 text-slate-600 align-middle whitespace-nowrap tabular-nums font-medium">
                                            @if ($low !== null || $high !== null)
                                                @if ($low !== null && $high !== null)
                                                    {{ rtrim(rtrim(number_format($low, 2, '.', ''), '0'), '.') }}
                                                    <span class="text-slate-300 mx-0.5">–</span>
                                                    {{ rtrim(rtrim(number_format($high, 2, '.', ''), '0'), '.') }}
                                                @elseif ($low !== null)
                                                    ≥ {{ rtrim(rtrim(number_format($low, 2, '.', ''), '0'), '.') }}
                                                @else
                                                    ≤ {{ rtrim(rtrim(number_format($high, 2, '.', ''), '0'), '.') }}
                                                @endif
                                            @else
                                                {{ $result->reference_range ?? '—' }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-middle pr-5">
                                            <span id="status-{{ $loop->index }}"
                                                class="status-pill inline-flex min-w-[4.5rem] justify-center items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border shadow-sm
                                                @if ($result->status === 'normal') border-emerald-200/80 bg-emerald-50 text-emerald-900
                                                @elseif($result->status === 'critical') border-red-200 bg-red-50 text-red-900
                                                @elseif($result->status === 'high') border-amber-200 bg-amber-50 text-amber-900
                                                @elseif($result->status === 'low') border-sky-200 bg-sky-50 text-sky-900
                                                @else border-slate-200 bg-slate-50 text-slate-400 @endif">
                                                @if ($result->result_value !== null && $result->result_value !== '')
                                                    {{ strtoupper($result->status === 'normal' ? 'ok' : $result->status) }}
                                                    @if ($result->status_flag)
                                                        <span class="opacity-75 font-semibold">({{ $result->status_flag }})</span>
                                                    @endif
                                                @else
                                                    —
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-slate-600 font-medium">No parameters for this test</p>
                        <p class="mt-1 text-sm text-slate-500">Add parameters in test settings, then return here.</p>
                    </div>
                @endif

                @if ($results->count() > 0)
                    <div
                        class="fixed bottom-0 left-0 right-0 z-40 sm:relative sm:z-0 border-t border-slate-200/80 bg-gradient-to-b from-white to-slate-50/80 backdrop-blur-md shadow-[0_-8px_32px_rgba(15,23,42,0.08)] sm:shadow-none">
                        <div class="max-w-5xl mx-auto px-4 py-4 sm:px-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-slate-500 text-center sm:text-left leading-relaxed max-w-md">
                                <span class="font-semibold text-slate-700">Tip:</span> Save keeps you here;
                                @if ($order->report)
                                    the green action opens your existing report.
                                @else
                                    the green action continues to report generation.
                                @endif
                            </p>
                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto sm:shrink-0">
                                <button type="submit" id="btn-save-stay"
                                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl border border-slate-200 bg-white text-slate-800 font-semibold text-sm shadow-sm hover:bg-slate-50 hover:border-slate-300 hover:shadow transition-all active:scale-[0.99]">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H6a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2V9l-6-6H8z"></path>
                                    </svg>
                                    Save
                                </button>
                                <button type="submit" id="btn-save-report"
                                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-semibold text-sm shadow-lg shadow-emerald-900/20 ring-1 ring-white/10 transition-all hover:shadow-xl hover:shadow-emerald-900/25 active:scale-[0.99]">
                                    <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    @if ($order->report)
                                        Save &amp; open report
                                    @else
                                        Save &amp; generate report
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <div
            class="flex flex-wrap items-center justify-center sm:justify-start gap-x-5 gap-y-2 rounded-2xl border border-slate-200/60 bg-white/60 px-4 py-3 text-[11px] font-medium text-slate-600 shadow-sm">
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-600/40"></span> OK / normal
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-sky-500 shadow-sm shadow-sky-600/40"></span> Low (L)
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-500 shadow-sm shadow-amber-600/40"></span> High (H)
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-red-500 shadow-sm shadow-red-600/40"></span> Critical (C)
            </span>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                function extractNumeric(value) {
                    const cleaned = String(value).replace(/[^0-9.]/g, '');
                    if (cleaned === '' || cleaned === '.') return null;
                    const n = parseFloat(cleaned);
                    return Number.isFinite(n) ? n : null;
                }

                function evaluateStatus(value, lowStr, highStr) {
                    if (value === null || value === undefined || String(value).trim() === '') return 'normal';
                    const numericValue = extractNumeric(value);
                    if (numericValue === null) return 'normal';
                    const low = lowStr === '' || lowStr === undefined ? null : parseFloat(lowStr);
                    const high = highStr === '' || highStr === undefined ? null : parseFloat(highStr);
                    if ((low === null || isNaN(low)) && (high === null || isNaN(high))) return 'normal';
                    const lo = low !== null && !isNaN(low) ? low : null;
                    const hi = high !== null && !isNaN(high) ? high : null;
                    if (lo !== null && numericValue < lo) {
                        if (numericValue < lo * 0.8) return 'critical';
                        return 'low';
                    }
                    if (hi !== null && numericValue > hi) {
                        if (numericValue > hi * 1.2) return 'critical';
                        return 'high';
                    }
                    return 'normal';
                }

                const pillClasses = {
                    normal: 'status-pill inline-flex min-w-[4.5rem] justify-center items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border shadow-sm border-emerald-200/80 bg-emerald-50 text-emerald-900',
                    low: 'status-pill inline-flex min-w-[4.5rem] justify-center items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border shadow-sm border-sky-200 bg-sky-50 text-sky-900',
                    high: 'status-pill inline-flex min-w-[4.5rem] justify-center items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border shadow-sm border-amber-200 bg-amber-50 text-amber-900',
                    critical: 'status-pill inline-flex min-w-[4.5rem] justify-center items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border shadow-sm border-red-200 bg-red-50 text-red-900',
                    empty: 'status-pill inline-flex min-w-[4.5rem] justify-center items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border shadow-sm border-slate-200 bg-slate-50 text-slate-400',
                };

                function flagLabel(status) {
                    const map = {
                        normal: 'OK',
                        low: 'LOW (L)',
                        high: 'HIGH (H)',
                        critical: 'CRITICAL (C)',
                    };
                    return map[status] || status;
                }

                function rowBorderClasses(status, hasValue) {
                    const base = 'result-row group border-l-[3px] transition-colors duration-150 hover:bg-slate-50/50';
                    if (!hasValue) {
                        return base + ' border-l-transparent bg-white';
                    }
                    if (status === 'critical') return base + ' border-l-red-500 bg-red-50/35';
                    if (status === 'high' || status === 'low') return base + ' border-l-amber-400 bg-amber-50/25';
                    return base + ' border-l-emerald-400/70 bg-white';
                }

                function updateRow(input) {
                    const idx = input.dataset.row;
                    const pill = document.getElementById('status-' + idx);
                    const row = input.closest('tr');
                    if (!pill || !row) return;
                    const v = input.value;
                    const hasValue = v.trim() !== '';
                    const status = evaluateStatus(v, input.dataset.refLow, input.dataset.refHigh);
                    pill.className = hasValue ? (pillClasses[status] || pillClasses.normal) : pillClasses.empty;
                    pill.textContent = hasValue ? flagLabel(status) : '—';
                    row.className = rowBorderClasses(status, hasValue);
                }

                document.querySelectorAll('.result-input').forEach(function(el) {
                    el.addEventListener('input', function() {
                        updateRow(el);
                    });
                    el.addEventListener('change', function() {
                        updateRow(el);
                    });
                });

                document.getElementById('btn-save-stay')?.addEventListener('click', function() {
                    document.getElementById('save-next').value = 'stay';
                });
                document.getElementById('btn-save-report')?.addEventListener('click', function() {
                    document.getElementById('save-next').value = 'report';
                });

                document.querySelectorAll('.result-input').forEach(updateRow);
            })();
        </script>
    @endpush
</x-app-layout>
