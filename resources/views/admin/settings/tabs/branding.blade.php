{{-- Single Branding page: logo, colors, lab identity, public contact --}}
@php
    $defaultSecondary = $settings->secondary_color ?? '#8b5cf6';
@endphp
<div class="space-y-8">
    <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 p-5">
        <p class="text-sm font-medium text-slate-700 mb-1">Server upload limits</p>
        <p class="text-xs text-slate-600">upload_max_filesize = <strong>{{ $phpUploadMax ?? '?' }}</strong>, post_max_size = <strong>{{ $phpPostMax ?? '?' }}</strong></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Logo</h3>
                        <p class="text-sm text-slate-500">Shown on website header and reports</p>
                    </div>
                </div>
                <label for="logo" class="flex flex-col items-center justify-center w-full min-h-[120px] rounded-xl border-2 border-dashed border-slate-200 hover:border-violet-300 hover:bg-violet-50/30 cursor-pointer transition-all group/logo">
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                    @if($settings->logo)
                        <div class="relative inline-block" id="logo-preview-wrap">
                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-20 w-20 object-contain rounded-xl border border-slate-200">
                            <button type="button" onclick="removeLogo()" class="absolute -top-2 -right-2 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition-colors shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @else
                        <svg class="w-12 h-12 text-slate-400 group-hover/logo:text-violet-500 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm text-slate-600">Click to upload logo</p>
                    @endif
                </label>
            </div>
        </div>

        <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Favicon</h3>
                        <p class="text-sm text-slate-500">Browser tab icon</p>
                    </div>
                </div>
                <label for="favicon" class="flex flex-col items-center justify-center w-full min-h-[120px] rounded-xl border-2 border-dashed border-slate-200 hover:border-violet-300 hover:bg-violet-50/30 cursor-pointer transition-all group/favicon">
                    <input type="file" name="favicon" id="favicon" accept="image/*" class="hidden" onchange="previewFavicon(this)">
                    @if($settings->favicon)
                        <div class="relative inline-block" id="favicon-preview-wrap">
                            <img src="{{ asset('storage/' . $settings->favicon) }}" alt="Favicon" class="w-16 h-16 rounded border border-slate-200">
                            <button type="button" onclick="removeFavicon()" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors shadow-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @else
                        <svg class="w-12 h-12 text-slate-400 group-hover/favicon:text-violet-500 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm text-slate-600">Click to upload favicon</p>
                    @endif
                </label>
            </div>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Lab name</h3>
                    <p class="text-sm text-slate-500">Shown on website, reports, and this admin panel</p>
                </div>
            </div>
            <label for="brand_name" class="block text-sm font-medium text-slate-700 mb-2">Display name</label>
            <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name', $settings->brand_name ?? $lab->name ?? '') }}" class="block w-full max-w-xl rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="relative p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Primary color</h3>
                <div class="flex items-center gap-4">
                    <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#6366f1') }}" class="h-12 w-20 rounded-xl border border-slate-200 cursor-pointer">
                    <input type="text" id="primary_color_hex" value="{{ old('primary_color', $settings->primary_color ?? '#6366f1') }}" class="block w-full rounded-xl border-slate-200 shadow-sm px-4 py-2.5 font-mono text-sm" readonly>
                </div>
                <p class="mt-2 text-xs text-slate-500">Main brand color (buttons, links, sidebar)</p>
            </div>
        </div>
        <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="relative p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Secondary color</h3>
                <div class="flex items-center gap-4">
                    <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', $defaultSecondary) }}" class="h-12 w-20 rounded-xl border border-slate-200 cursor-pointer">
                    <input type="text" id="secondary_color_hex" value="{{ old('secondary_color', $defaultSecondary) }}" class="block w-full rounded-xl border-slate-200 shadow-sm px-4 py-2.5 font-mono text-sm" readonly>
                </div>
                <p class="mt-2 text-xs text-slate-500">Accents and gradients</p>
            </div>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Public contact</h3>
                    <p class="text-sm text-slate-500">Website footer, reports, and patient-facing content</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $settings->email ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="lab@example.com">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $settings->phone ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="+1 234 567 8900">
                </div>
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                    <textarea name="address" id="address" rows="3" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">{{ old('address', $settings->address ?? '') }}</textarea>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-sm font-medium text-slate-800 mb-3">Form inquiries (optional)</p>
                <p class="text-xs text-slate-500 mb-4">Separate addresses for contact forms if different from above.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-2">Inquiry email</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings->contact_email ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-2">Inquiry phone</label>
                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings->contact_phone ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var colorInput = document.getElementById('primary_color');
    var hexInput = document.getElementById('primary_color_hex');
    if (colorInput && hexInput) {
        colorInput.addEventListener('input', function() { hexInput.value = this.value; });
    }
    var secColor = document.getElementById('secondary_color');
    var secHex = document.getElementById('secondary_color_hex');
    if (secColor && secHex) {
        secColor.addEventListener('input', function() { secHex.value = this.value; });
    }
})();

function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var wrap = document.getElementById('logo-preview-wrap');
            var label = document.querySelector('label[for="logo"]');
            if (wrap && wrap.querySelector('img')) {
                wrap.querySelector('img').src = e.target.result;
            } else if (label) {
                var div = document.createElement('div');
                div.id = 'logo-preview-wrap';
                div.className = 'relative inline-block';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Logo';
                img.className = 'h-20 w-20 object-contain rounded-xl border border-slate-200';
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.onclick = removeLogo;
                btn.className = 'absolute -top-2 -right-2 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 shadow-lg';
                btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                div.appendChild(img);
                div.appendChild(btn);
                label.innerHTML = '';
                label.appendChild(div);
                var inp = document.createElement('input');
                inp.type = 'file';
                inp.name = 'logo';
                inp.id = 'logo';
                inp.accept = 'image/*';
                inp.className = 'hidden';
                inp.onchange = function() { previewLogo(this); };
                label.insertBefore(inp, label.firstChild);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFavicon(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var wrap = document.getElementById('favicon-preview-wrap');
            var label = document.querySelector('label[for="favicon"]');
            if (wrap && wrap.querySelector('img')) {
                wrap.querySelector('img').src = e.target.result;
            } else if (label) {
                var div = document.createElement('div');
                div.id = 'favicon-preview-wrap';
                div.className = 'relative inline-block';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Favicon';
                img.className = 'w-16 h-16 rounded border border-slate-200';
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.onclick = removeFavicon;
                btn.className = 'absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 shadow-lg';
                btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                div.appendChild(img);
                div.appendChild(btn);
                label.innerHTML = '';
                label.appendChild(div);
                var inp = document.createElement('input');
                inp.type = 'file';
                inp.name = 'favicon';
                inp.id = 'favicon';
                inp.accept = 'image/*';
                inp.className = 'hidden';
                inp.onchange = function() { previewFavicon(this); };
                label.insertBefore(inp, label.firstChild);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    if (confirm('Remove the logo?')) {
        document.getElementById('logo').value = '';
        var label = document.querySelector('label[for="logo"]');
        if (label) {
            label.innerHTML = '<input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(this)"><svg class="w-12 h-12 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg><p class="text-sm text-slate-600">Click to upload logo</p>';
        }
    }
}

function removeFavicon() {
    document.getElementById('favicon').value = '';
    var label = document.querySelector('label[for="favicon"]');
    if (label) {
        label.innerHTML = '<input type="file" name="favicon" id="favicon" accept="image/*" class="hidden" onchange="previewFavicon(this)"><svg class="w-12 h-12 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg><p class="text-sm text-slate-600">Click to upload favicon</p>';
    }
}
</script>
