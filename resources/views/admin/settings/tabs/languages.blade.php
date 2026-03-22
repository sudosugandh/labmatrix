{{-- Languages Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-violet-50/30 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Language Settings</h3>
                    <p class="text-sm text-slate-600">Set default and available languages for your lab</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label for="default_language" class="block text-sm font-medium text-slate-700 mb-2">Default Language</label>
                <select name="default_language" id="default_language" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                    <option value="en" {{ old('default_language', $settings->default_language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                    <option value="hi" {{ old('default_language', $settings->default_language) === 'hi' ? 'selected' : '' }}>Hindi</option>
                    <option value="gu" {{ old('default_language', $settings->default_language) === 'gu' ? 'selected' : '' }}>Gujarati</option>
                    <option value="es" {{ old('default_language', $settings->default_language) === 'es' ? 'selected' : '' }}>Spanish</option>
                    <option value="fr" {{ old('default_language', $settings->default_language) === 'fr' ? 'selected' : '' }}>French</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-3">Available Languages</label>
                <p class="text-sm text-slate-500 mb-4">Select languages that will be available for your users</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(['en' => 'English', 'hi' => 'Hindi', 'gu' => 'Gujarati', 'es' => 'Spanish', 'fr' => 'French'] as $code => $name)
                        <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-violet-200 hover:bg-violet-50/30 cursor-pointer transition-all">
                            <input type="checkbox" name="available_languages[]" value="{{ $code }}" {{ in_array($code, old('available_languages', $settings->available_languages ?? ['en'])) ? 'checked' : '' }} class="rounded border-slate-300 text-violet-600 focus:ring-violet-500/30 w-4 h-4">
                            <span class="text-sm font-medium text-slate-700">{{ $name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
