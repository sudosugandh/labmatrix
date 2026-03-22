{{-- Printer Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Printer Configuration</h3>
                    <p class="text-sm text-slate-500">Network printer settings for receipts and reports</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="printer_name" class="block text-sm font-medium text-slate-700 mb-2">Printer Name</label>
                    <input type="text" name="printer_name" id="printer_name" value="{{ old('printer_name', $settings->printer_name ?? '') }}" placeholder="e.g., Thermal Printer 1" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                </div>
                <div>
                    <label for="printer_ip" class="block text-sm font-medium text-slate-700 mb-2">Printer IP Address</label>
                    <input type="text" name="printer_ip" id="printer_ip" value="{{ old('printer_ip', $settings->printer_ip ?? '') }}" placeholder="192.168.1.100" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                </div>
                <div>
                    <label for="printer_port" class="block text-sm font-medium text-slate-700 mb-2">Printer Port</label>
                    <input type="number" name="printer_port" id="printer_port" value="{{ old('printer_port', $settings->printer_port ?? '') }}" placeholder="9100" min="1" max="65535" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                </div>
                <div>
                    <label for="printer_type" class="block text-sm font-medium text-slate-700 mb-2">Printer Type</label>
                    <select name="printer_type" id="printer_type" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                        <option value="thermal" {{ old('printer_type', $settings->printer_type ?? 'thermal') === 'thermal' ? 'selected' : '' }}>Thermal</option>
                        <option value="inkjet" {{ old('printer_type', $settings->printer_type) === 'inkjet' ? 'selected' : '' }}>Inkjet</option>
                        <option value="laser" {{ old('printer_type', $settings->printer_type) === 'laser' ? 'selected' : '' }}>Laser</option>
                    </select>
                </div>
                <div>
                    <label for="paper_size" class="block text-sm font-medium text-slate-700 mb-2">Paper Size</label>
                    <select name="paper_size" id="paper_size" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                        <option value="A4" {{ old('paper_size', $settings->paper_size ?? 'A4') === 'A4' ? 'selected' : '' }}>A4</option>
                        <option value="A5" {{ old('paper_size', $settings->paper_size) === 'A5' ? 'selected' : '' }}>A5</option>
                        <option value="Letter" {{ old('paper_size', $settings->paper_size) === 'Letter' ? 'selected' : '' }}>Letter</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="report_template_style" class="block text-sm font-medium text-slate-700 mb-2">Report Template Style</label>
                    <select name="report_template_style" id="report_template_style" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                        <option value="lal_pathlabs" {{ old('report_template_style', $settings->report_template_style ?? 'lal_pathlabs') === 'lal_pathlabs' ? 'selected' : '' }}>Dr Lal PathLabs Style (Professional)</option>
                        <option value="default" {{ old('report_template_style', $settings->report_template_style) === 'default' ? 'selected' : '' }}>Default (Classic)</option>
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Choose the layout and fonts for printed/PDF lab reports.</p>
                </div>
            </div>
        </div>
    </div>
</div>
