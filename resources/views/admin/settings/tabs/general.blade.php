{{-- General Tab: CMS-style UI --}}
<div class="space-y-8">
    {{-- Server info --}}
    <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 p-5">
        <p class="text-sm font-medium text-slate-700 mb-1">Server upload limits (this request)</p>
        <p class="text-xs text-slate-600">upload_max_filesize = <strong>{{ $phpUploadMax ?? '?' }}</strong>, post_max_size = <strong>{{ $phpPostMax ?? '?' }}</strong></p>
        @if(($phpPostMax !== '64M' && $phpPostMax !== '128M' && $phpPostMax !== '256M') || ($phpUploadMax !== '64M' && $phpUploadMax !== '128M' && $phpUploadMax !== '256M'))
            <p class="text-xs text-amber-700 mt-2">If uploads fail, set both to at least 64M in PHP (cli + fpm) and restart PHP-FPM. If using Nginx, add <code class="bg-white px-1.5 py-0.5 rounded text-slate-700">client_max_body_size 64M;</code> in your server block.</p>
        @endif
    </div>

    {{-- Basic Info --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Basic Information</h3>
                    <p class="text-sm text-slate-500">Lab identity (read-only)</p>
                </div>
            </div>
            <div class="space-y-4">
                <p class="text-sm text-slate-600 mb-4">Lab name, logo, colors, and contact details are under <a href="{{ route('admin.settings.index', ['tab' => 'branding']) }}" class="text-violet-600 font-medium hover:underline">Settings → Branding</a>.</p>
                <div>
                    <label for="domain" class="block text-sm font-medium text-slate-700 mb-2">Domain</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain', $settings->domain ?? request()->getHost()) }}" readonly class="block w-full rounded-xl border-slate-200 bg-slate-50 shadow-sm px-4 py-2.5 text-slate-600">
                    <p class="mt-1 text-xs text-slate-500">Domain cannot be changed after setup</p>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" readonly class="block w-full rounded-xl border-slate-200 bg-slate-50 shadow-sm px-4 py-2.5 text-slate-600">{{ old('description', $settings->description ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Description cannot be changed</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner Images --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Banner Images</h3>
                    <p class="text-sm text-slate-500">Upload banner images and videos for homepage carousel</p>
                </div>
            </div>
            <label for="banner_images" class="flex flex-col items-center justify-center w-full min-h-[140px] rounded-xl border-2 border-dashed border-slate-200 hover:border-violet-300 hover:bg-violet-50/30 cursor-pointer transition-all group/upload">
                <input type="file" name="banner_images[]" id="banner_images" multiple accept="image/*,video/*" class="hidden" onchange="previewBanners(this)">
                <div class="p-4 rounded-full bg-slate-100 group-hover/upload:bg-violet-100 transition-colors mb-3">
                    <svg class="w-8 h-8 text-slate-400 group-hover/upload:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <p class="text-sm font-medium text-slate-600 group-hover/upload:text-violet-600">Drag and drop or click to browse</p>
                <p class="text-xs text-slate-400 mt-1">Max 50MB per file, up to 5 files. Recommended: 1920×400px</p>
            </label>
            <div id="banner-new-preview" class="mt-5 hidden">
                <p class="text-sm font-medium text-slate-700 mb-2">New selection (click Save to upload)</p>
                <div id="banner-new-preview-list" class="grid grid-cols-2 md:grid-cols-5 gap-3"></div>
                <p id="banner-size-error" class="mt-2 text-sm text-red-600 hidden"></p>
            </div>
            @if($settings->banner_images && count($settings->banner_images) > 0)
                <div class="mt-5">
                    <p class="text-sm text-slate-600 mb-3">{{ count($settings->banner_images) }} of 5 items uploaded</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($settings->banner_images as $index => $banner)
                            @php $isVideo = preg_match('/\.(mp4|webm|ogg|mov|avi)(\?|$)/i', $banner); @endphp
                            <div class="relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                @if($isVideo)
                                    <video src="{{ asset('storage/' . $banner) }}" class="w-full h-32 object-cover" muted preload="metadata" playsinline></video>
                                @else
                                    <img src="{{ asset('storage/' . $banner) }}" alt="Banner {{ $index + 1 }}" class="w-full h-32 object-cover">
                                @endif
                                <span class="absolute top-2 left-2 bg-black/70 text-white text-xs font-medium px-2.5 py-1 rounded-lg">{{ $index + 1 }}</span>
                                <button type="button" onclick="deleteBanner(this)" data-delete-url="{{ route('admin.settings.delete-banner', ['index' => $index]) }}" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity p-2 bg-red-500 text-white rounded-lg hover:bg-red-600" title="Delete this banner">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="mt-3 text-sm text-slate-500">0 of 5 items uploaded</p>
            @endif
        </div>
    </div>

</div>

<script>
var MAX_BANNER_SIZE = 50 * 1024 * 1024;
var MAX_BANNER_COUNT = 5;

function previewBanners(input) {
    var container = document.getElementById('banner-new-preview');
    var list = document.getElementById('banner-new-preview-list');
    var errEl = document.getElementById('banner-size-error');
    list.innerHTML = '';
    errEl.classList.add('hidden');
    errEl.textContent = '';
    if (!input.files || input.files.length === 0) {
        container.classList.add('hidden');
        return;
    }
    var files = Array.from(input.files);
    if (files.length > MAX_BANNER_COUNT) {
        errEl.textContent = 'Maximum ' + MAX_BANNER_COUNT + ' files. Please select fewer.';
        errEl.classList.remove('hidden');
        input.value = '';
        return;
    }
    for (var i = 0; i < files.length; i++) {
        if (files[i].size > MAX_BANNER_SIZE) {
            errEl.textContent = '"' + files[i].name + '" is too large (max 50MB per file). Please choose a smaller file.';
            errEl.classList.remove('hidden');
            input.value = '';
            return;
        }
    }
    container.classList.remove('hidden');
    files.forEach(function(file, idx) {
        var isVideo = file.type.indexOf('video/') === 0;
        var cell = document.createElement('div');
        cell.className = 'relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50';
        if (isVideo) {
            cell.innerHTML = '<div class="aspect-video flex items-center justify-center text-slate-500"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></div><p class="text-xs p-2 truncate" title="' + file.name + '">' + file.name + '</p>';
        } else {
            var img = document.createElement('img');
            img.className = 'w-full h-24 object-cover';
            img.alt = file.name;
            cell.appendChild(img);
            var reader = new FileReader();
            reader.onload = (function(el) { return function(e) { el.src = e.target.result; }; })(img);
            reader.readAsDataURL(file);
        }
        var badge = document.createElement('div');
        badge.className = 'absolute top-1 left-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded';
        badge.textContent = 'New ' + (idx + 1);
        cell.insertBefore(badge, cell.firstChild);
        list.appendChild(cell);
    });
}

function deleteBanner(btn) {
    if (!confirm('Are you sure you want to delete this banner?')) return;
    var url = btn.getAttribute('data-delete-url');
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var formData = new FormData();
    formData.append('_token', token);
    formData.append('_method', 'DELETE');
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
    }).then(function(r) {
        if (r.redirected) window.location.href = r.url;
        else if (r.ok) window.location.reload();
        else btn.disabled = false;
    }).catch(function() { btn.disabled = false; });
}
</script>
