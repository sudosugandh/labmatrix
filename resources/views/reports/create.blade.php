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
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0 space-y-1">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-800/80">Report workflow</p>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Finalize report</h2>
                <p class="text-sm text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <span class="font-mono text-slate-700 tabular-nums">{{ $orderLabel }}</span>
                    <span class="text-slate-300">·</span>
                    <span class="text-slate-700">{{ $order->test->name }}</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('orders.enter-results', $order) }}"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200/90 bg-white text-slate-700 shadow-sm hover:border-emerald-300 hover:bg-emerald-50/50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Back to results
                </a>
                <a href="{{ route('orders.show', $order) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200/90 bg-white text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Order details
                </a>
            </div>
        </div>
    </x-slot>

    <div
        class="max-w-3xl mx-auto space-y-6 -mx-4 sm:mx-auto px-4 sm:px-0 pb-8 bg-gradient-to-b from-stone-50/80 via-white to-emerald-50/15 sm:rounded-3xl sm:py-1">

        {{-- Order context --}}
        <div
            class="relative overflow-hidden rounded-2xl border border-white/70 bg-white/90 backdrop-blur-md shadow-lg shadow-slate-300/20 ring-1 ring-slate-200/60">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-400/40 to-transparent"></div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5">
                <div class="flex items-start gap-4 min-w-0">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white text-sm font-bold shadow-md shadow-emerald-900/20 ring-2 ring-white">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-lg font-semibold text-slate-900 tracking-tight">{{ $order->patient->name }}</p>
                        <p class="mt-0.5 text-sm text-slate-600">
                            <span class="tabular-nums">{{ $order->patient->phone ?? '—' }}</span>
                            @if ($order->patient->age || $order->patient->gender)
                                <span class="mx-1.5 text-slate-300">·</span>
                                {{ $order->patient->age ?? '—' }} yrs
                                <span class="mx-1.5 text-slate-300">·</span>
                                {{ $order->patient->gender ?? '—' }}
                            @endif
                        </p>
                    </div>
                </div>
                <dl class="sm:ml-auto grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm sm:text-right">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Order</dt>
                        <dd class="font-mono font-semibold text-slate-800 tabular-nums">{{ $orderLabel }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Ordered</dt>
                        <dd class="text-slate-700 tabular-nums">{{ $order->created_at->format('M j, Y · g:i A') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 sm:hidden">Test</dt>
                        <dd class="font-medium text-emerald-900 sm:truncate">{{ $order->test->name }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xl shadow-slate-400/10 ring-1 ring-slate-200/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50/90 to-white">
                <h3 class="text-base font-semibold text-slate-900">Report options</h3>
                <p class="mt-1 text-sm text-slate-500">Generate a PDF from lab data or upload your own file. Status controls patient-facing readiness.</p>
            </div>

            <div class="p-5 sm:p-6">
                <form method="POST" action="{{ route('reports.store', $order) }}" enctype="multipart/form-data" id="report-form">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50/80 px-4 py-3 text-sm text-red-900" role="alert">
                            <p class="font-semibold">Please fix the following:</p>
                            <ul class="mt-2 list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div>
                            <label for="report_name" class="block text-sm font-semibold text-slate-700">Report name</label>
                            <input type="text" name="report_name" id="report_name"
                                value="{{ old('report_name', $order->test->name . ' Report') }}"
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-slate-900 shadow-sm transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            @error('report_name')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div
                            class="relative overflow-hidden rounded-2xl border border-sky-200/60 bg-gradient-to-br from-sky-50/80 via-white to-indigo-50/40 p-5 shadow-inner">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="generate_pdf" id="generate_pdf" value="1"
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    {{ old('generate_pdf', '1') == '1' ? 'checked' : '' }}
                                    onchange="toggleFileUpload()">
                                <div class="min-w-0 flex-1">
                                    <label for="generate_pdf" class="text-sm font-semibold text-slate-900 cursor-pointer">
                                        Generate professional PDF
                                    </label>
                                    <p class="mt-1 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                        Builds a branded laboratory PDF with patient details, results, and lab identity—same layout as preview.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('reports.preview', $order) }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition hover:border-emerald-300 hover:text-emerald-800 hover:shadow-md">
                                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Preview PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="file-upload-section">
                            <label for="report_file" class="block text-sm font-semibold text-slate-700">Or upload PDF</label>
                            <p class="mt-0.5 text-xs text-slate-500">Use when you already have a finalized file (turn off generate above).</p>
                            <input type="file" name="report_file" id="report_file" accept=".pdf,application/pdf"
                                class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-emerald-800 hover:file:bg-emerald-100">
                            @error('report_file')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ready" {{ old('status', 'pending') == 'ready' ? 'selected' : '' }}>Ready</option>
                                </select>
                                @error('status')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="remarks" class="block text-sm font-semibold text-slate-700">Remarks <span class="font-normal text-slate-400">(optional)</span></label>
                            <textarea name="remarks" id="remarks" rows="4" placeholder="Internal notes or release comments…"
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('orders.show', $order) }}"
                            class="inline-flex justify-center items-center px-4 py-3 text-sm font-semibold text-slate-600 hover:text-slate-900">
                            Cancel
                        </a>
                        <button type="submit"
                            class="button-toast-submit inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 ring-1 ring-white/10 transition hover:from-emerald-500 hover:to-emerald-600 hover:shadow-xl active:scale-[0.99]">
                            <svg class="h-5 w-5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="button-text">Save report</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFileUpload() {
            const generatePdf = document.getElementById('generate_pdf').checked;
            const fileUploadSection = document.getElementById('file-upload-section');
            const fileInput = document.getElementById('report_file');

            if (generatePdf) {
                fileUploadSection.classList.add('opacity-50', 'pointer-events-none');
                fileInput.removeAttribute('required');
                fileInput.value = '';
            } else {
                fileUploadSection.classList.remove('opacity-50', 'pointer-events-none');
                fileInput.setAttribute('required', 'required');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleFileUpload();
        });
    </script>
</x-app-layout>
