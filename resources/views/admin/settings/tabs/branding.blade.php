{{-- Branding Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Primary Color</h3>
                    <p class="text-sm text-slate-500">Brand accent used across the website</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#7C3AED') }}" class="h-12 w-20 rounded-xl border border-slate-200 cursor-pointer">
                <input type="text" id="primary_color_hex" value="{{ old('primary_color', $settings->primary_color ?? '#7C3AED') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5 font-mono text-sm" readonly>
            </div>
            <p class="mt-2 text-xs text-slate-500">Click the color swatch to pick a new color. Hex value syncs automatically.</p>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Homepage Banner Text</h3>
                    <p class="text-sm text-slate-500">Overlay text shown on homepage hero banner</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label for="hero_title" class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                    <input type="text" name="hero_title" id="hero_title" value="{{ old('hero_title', $settings->hero_title ?? '') }}" placeholder="Shown over the banner" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                </div>
                <div>
                    <label for="hero_subtitle" class="block text-sm font-medium text-slate-700 mb-2">Subtitle</label>
                    <textarea name="hero_subtitle" id="hero_subtitle" rows="2" placeholder="Optional supporting text" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Banner image is set under Settings → Website / CMS → Homepage Banner.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Footer Text</h3>
                    <p class="text-sm text-slate-500">Content shown in the website footer</p>
                </div>
            </div>
            <textarea name="footer_text" id="footer_text" rows="3" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="Copyright, tagline, or additional info">{{ old('footer_text', $settings->footer_text ?? '') }}</textarea>
        </div>
    </div>
</div>

<script>
(function() {
    var colorInput = document.getElementById('primary_color');
    var hexInput = document.getElementById('primary_color_hex');
    if (colorInput && hexInput) {
        colorInput.addEventListener('input', function() {
            hexInput.value = this.value;
        });
    }
})();
</script>
