{{-- CMS Tab: Modern next-level UI/UX --}}
<div class="space-y-8">
    {{-- Quick links bar --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white text-sm font-medium shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Pages
        </a>
        <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-medium hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            Menus
        </a>
        <a href="{{ route('testimonials.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-medium hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Testimonials
        </a>
        <a href="{{ route('faqs.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-medium hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            FAQs
        </a>
        <a href="{{ route('promos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-medium hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Offers
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-semibold text-slate-900">Homepage hero</h3>
            <p class="text-sm text-slate-500">Text over the banner. Banner carousel is configured below (Homepage Banner).</p>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="hero_title" class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                <input type="text" name="hero_title" id="hero_title" value="{{ old('hero_title', $settings->hero_title ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
            </div>
            <div>
                <label for="hero_subtitle" class="block text-sm font-medium text-slate-700 mb-2">Subtitle</label>
                <textarea name="hero_subtitle" id="hero_subtitle" rows="2" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}</textarea>
            </div>
            <div>
                <label for="hero_image" class="block text-sm font-medium text-slate-700 mb-2">Hero image (optional fallback)</label>
                <input type="file" name="hero_image" id="hero_image" accept="image/*" class="block w-full text-sm text-slate-600">
                @if($settings->hero_image)
                    <p class="mt-2 text-xs text-slate-500">Current: <a href="{{ asset('storage/' . $settings->hero_image) }}" class="text-violet-600 hover:underline" target="_blank">view</a></p>
                @endif
            </div>
            <div>
                <label for="footer_text" class="block text-sm font-medium text-slate-700 mb-2">Footer line (plain text)</label>
                <textarea name="footer_text" id="footer_text" rows="2" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="Short tagline or copyright">{{ old('footer_text', $settings->footer_text ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Header content --}}
        <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Header</h3>
                        <p class="text-sm text-slate-500">Optional content above main nav (e.g. announcement bar)</p>
                    </div>
                </div>
                <textarea name="header_content" id="header_content" rows="3" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm transition-colors" placeholder="Leave empty for default">{{ old('header_content', $settings->header_content ?? '') }}</textarea>
            </div>
        </div>

        {{-- Footer content --}}
        <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Footer</h3>
                        <p class="text-sm text-slate-500">Rich content below contact info (supports HTML)</p>
                    </div>
                </div>
                <textarea name="footer_content" id="footer_content" rows="6" class="tinymce-editor block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm transition-colors">{{ old('footer_content', $settings->footer_content ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Navigation Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-violet-50/30 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-500 text-white shadow-lg shadow-violet-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Navigation Bar</h3>
                        <p class="text-sm text-slate-600">Manage main nav links. Reorder on <a href="{{ route('admin.menus.index') }}" class="text-violet-600 hover:text-violet-700 font-medium">Menus</a> page.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($mainMenu)
                <ul class="space-y-2 mb-5">
                    @foreach($mainMenu->items as $item)
                        <li class="flex items-center justify-between py-3 px-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-violet-100 hover:bg-violet-50/30 transition-all group/item" data-item-id="{{ $item->id }}">
                            <div class="flex items-center gap-4">
                                <span class="text-slate-400 cursor-move group-hover/item:text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                </span>
                                <span class="font-medium text-slate-900">{{ $item->title }}</span>
                                <span class="text-sm text-slate-500 font-mono">{{ $item->url }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="event.preventDefault(); editMenuItem({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->url) }}')" class="p-2 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button type="button" class="cms-delete-menu-item p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete" data-url="{{ route('admin.menus.items.destroy', $item) }}" data-redirect="{{ route('admin.settings.index', ['tab' => 'cms']) }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if($mainMenu->items->isEmpty())
                    <p class="text-sm text-slate-500 mb-5">No links yet. Add one below.</p>
                @endif
                <div class="flex flex-wrap items-end gap-3 p-4 bg-slate-50/50 rounded-xl border border-slate-100" id="cms-add-nav-form">
                    <input type="hidden" id="cms_add_nav_csrf" value="{{ csrf_token() }}">
                    <input type="hidden" id="cms_add_nav_url" value="{{ route('admin.menus.items.store', $mainMenu) }}">
                    <input type="hidden" id="cms_add_nav_redirect" value="{{ route('admin.settings.index', ['tab' => 'cms']) }}">
                    <div class="flex-1 min-w-[140px]">
                        <label for="nav_title" class="block text-xs font-medium text-slate-600 mb-1.5">Label</label>
                        <input type="text" id="nav_title" placeholder="e.g. About" class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm py-2.5" aria-label="New link label">
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <label for="nav_url" class="block text-xs font-medium text-slate-600 mb-1.5">URL</label>
                        <input type="text" id="nav_url" placeholder="e.g. /about" class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm py-2.5" aria-label="New link URL">
                    </div>
                    <button type="button" id="cms-add-nav-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium shadow-lg shadow-violet-500/25 transition-all hover:shadow-violet-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Link
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Homepage Banner --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Homepage Banner</h3>
                    <p class="text-sm text-slate-500">First image shown on homepage. Upload in General or here.</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <label for="cms_banner_upload" class="flex flex-col items-center justify-center w-full min-h-[140px] rounded-xl border-2 border-dashed border-slate-200 hover:border-violet-300 hover:bg-violet-50/30 cursor-pointer transition-all group">
                <input type="file" name="banner_images[]" multiple accept="image/*" class="hidden" id="cms_banner_upload" onchange="this.form.submit()">
                <div class="p-4 rounded-full bg-slate-100 group-hover:bg-violet-100 transition-colors mb-3">
                    <svg class="w-8 h-8 text-slate-400 group-hover:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                </div>
                <p class="text-sm font-medium text-slate-600 group-hover:text-violet-600">Click to upload banner images</p>
                <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 10MB</p>
            </label>
            @if($settings->banner_images && count($settings->banner_images) > 0)
                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($settings->banner_images as $index => $banner)
                        <div class="relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                            <img src="{{ asset('storage/' . $banner) }}" alt="Banner {{ $index + 1 }}" class="w-full h-32 object-cover">
                            <span class="absolute top-2 left-2 bg-black/70 text-white text-xs font-medium px-2.5 py-1 rounded-lg">{{ $index === 0 ? 'Homepage' : $index + 1 }}</span>
                            <button type="button" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 cms-delete-banner" data-url="{{ route('admin.settings.delete-banner', $index) }}" data-redirect="{{ route('admin.settings.index', ['tab' => 'cms']) }}" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Content After Banner --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Content After Banner</h3>
                    <p class="text-sm text-slate-500">Rich content below homepage banner. Supports HTML, images, tables.</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <textarea name="content_after_banner" id="content_after_banner" rows="8" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20">{{ old('content_after_banner', $settings->content_after_banner ?? '') }}</textarea>
        </div>
    </div>

    {{-- Homepage Sections (consolidated) --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-violet-50/20">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-500 text-white shadow-lg shadow-violet-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Homepage Sections</h3>
                    <p class="text-sm text-slate-500">Toggle sections and customize headings</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-8">
            {{-- Our Services --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/80">
                    <label for="show_services_section" class="text-sm font-medium text-slate-700 cursor-pointer">Show "Our Services" section</label>
                    <input type="hidden" name="show_services_section" value="0">
                    <input type="checkbox" name="show_services_section" id="show_services_section" value="1" {{ old('show_services_section', $settings->show_services_section ?? true) ? 'checked' : '' }} class="w-11 h-6 rounded-full border-slate-200 text-violet-600 focus:ring-violet-500/30 cursor-pointer">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="services_heading" class="block text-sm font-medium text-slate-700 mb-2">Heading</label>
                        <input type="text" name="services_heading" id="services_heading" value="{{ old('services_heading', $settings->services_heading ?? 'Our Services') }}" placeholder="Our Services" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                    </div>
                    <div>
                        <label for="services_subtitle" class="block text-sm font-medium text-slate-700 mb-2">Subtitle</label>
                        <input type="text" name="services_subtitle" id="services_subtitle" value="{{ old('services_subtitle', $settings->services_subtitle ?? 'Comprehensive diagnostic tests with accurate results and fast turnaround.') }}" placeholder="Subtitle..." class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                    </div>
                </div>
            </div>

            {{-- Testimonials --}}
            <div class="pt-6 border-t border-slate-200">
                <p class="text-sm text-slate-600 mb-4">Manage content in <a href="{{ route('testimonials.index') }}" class="text-violet-600 hover:text-violet-700 font-medium">Testimonials</a>.</p>
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/80 mb-4">
                    <label for="show_testimonials_section" class="text-sm font-medium text-slate-700 cursor-pointer">Show "Testimonials" section</label>
                    <input type="hidden" name="show_testimonials_section" value="0">
                    <input type="checkbox" name="show_testimonials_section" id="show_testimonials_section" value="1" {{ old('show_testimonials_section', $settings->show_testimonials_section ?? true) ? 'checked' : '' }} class="w-11 h-6 rounded-full border-slate-200 text-violet-600 focus:ring-violet-500/30 cursor-pointer">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="testimonials_heading" class="block text-sm font-medium text-slate-700 mb-2">Heading</label>
                        <input type="text" name="testimonials_heading" id="testimonials_heading" value="{{ old('testimonials_heading', $settings->testimonials_heading ?? 'What Our Patients Say') }}" placeholder="What Our Patients Say" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                    </div>
                    <div>
                        <label for="testimonials_subtitle" class="block text-sm font-medium text-slate-700 mb-2">Subtitle</label>
                        <input type="text" name="testimonials_subtitle" id="testimonials_subtitle" value="{{ old('testimonials_subtitle', $settings->testimonials_subtitle ?? 'Real feedback from people who trust us.') }}" placeholder="Subtitle..." class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                    </div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="pt-6 border-t border-slate-200">
                <p class="text-sm text-slate-600 mb-4">Manage content in <a href="{{ route('faqs.index') }}" class="text-violet-600 hover:text-violet-700 font-medium">FAQ</a>.</p>
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/80 mb-4">
                    <label for="show_faq_section" class="text-sm font-medium text-slate-700 cursor-pointer">Show "FAQ" section</label>
                    <input type="hidden" name="show_faq_section" value="0">
                    <input type="checkbox" name="show_faq_section" id="show_faq_section" value="1" {{ old('show_faq_section', $settings->show_faq_section ?? true) ? 'checked' : '' }} class="w-11 h-6 rounded-full border-slate-200 text-violet-600 focus:ring-violet-500/30 cursor-pointer">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="faq_heading" class="block text-sm font-medium text-slate-700 mb-2">Heading</label>
                        <input type="text" name="faq_heading" id="faq_heading" value="{{ old('faq_heading', $settings->faq_heading ?? 'Frequently Asked Questions') }}" placeholder="Frequently Asked Questions" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                    </div>
                    <div>
                        <label for="faq_subtitle" class="block text-sm font-medium text-slate-700 mb-2">Subtitle</label>
                        <input type="text" name="faq_subtitle" id="faq_subtitle" value="{{ old('faq_subtitle', $settings->faq_subtitle ?? 'Quick answers to common questions.') }}" placeholder="Subtitle..." class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                    </div>
                </div>
            </div>

            {{-- Offers --}}
            <div class="pt-6 border-t border-slate-200">
                <p class="text-sm text-slate-600 mb-4">Manage content in <a href="{{ route('promos.index') }}" class="text-violet-600 hover:text-violet-700 font-medium">Offers</a>.</p>
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/80 mb-4">
                    <label for="show_promos_section" class="text-sm font-medium text-slate-700 cursor-pointer">Show "Offers" section</label>
                    <input type="hidden" name="show_promos_section" value="0">
                    <input type="checkbox" name="show_promos_section" id="show_promos_section" value="1" {{ old('show_promos_section', $settings->show_promos_section ?? true) ? 'checked' : '' }} class="w-11 h-6 rounded-full border-slate-200 text-violet-600 focus:ring-violet-500/30 cursor-pointer">
                </div>
                <div>
                    <label for="promos_heading" class="block text-sm font-medium text-slate-700 mb-2">Heading</label>
                    <input type="text" name="promos_heading" id="promos_heading" value="{{ old('promos_heading', $settings->promos_heading ?? 'Special Offers') }}" placeholder="Special Offers" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                </div>
            </div>
        </div>
    </div>

    {{-- Popup, Announcement, SEO, Social - 2 col grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Home Collection Popup --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </span>
                    <h3 class="font-semibold text-slate-900">Home Collection Popup</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <label for="home_collection_popup_enabled" class="text-sm font-medium text-slate-700 cursor-pointer">Show popup</label>
                    <input type="hidden" name="home_collection_popup_enabled" value="0">
                    <input type="checkbox" name="home_collection_popup_enabled" id="home_collection_popup_enabled" value="1" {{ old('home_collection_popup_enabled', $settings->home_collection_popup_enabled ?? true) ? 'checked' : '' }} class="w-11 h-6 rounded-full border-slate-200 text-violet-600 focus:ring-violet-500/30 cursor-pointer">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="home_collection_popup_delay_seconds" class="block text-xs font-medium text-slate-600 mb-1">Delay (sec)</label>
                        <input type="number" name="home_collection_popup_delay_seconds" id="home_collection_popup_delay_seconds" min="0" max="60" value="{{ old('home_collection_popup_delay_seconds', $settings->home_collection_popup_delay_seconds ?? 2) }}" class="block w-full rounded-lg border-slate-200 text-sm py-2">
                    </div>
                    <div>
                        <label for="home_collection_popup_frequency" class="block text-xs font-medium text-slate-600 mb-1">Frequency</label>
                        <select name="home_collection_popup_frequency" id="home_collection_popup_frequency" class="block w-full rounded-lg border-slate-200 text-sm py-2">
                            <option value="session" {{ old('home_collection_popup_frequency', $settings->home_collection_popup_frequency ?? 'session') == 'session' ? 'selected' : '' }}>Per session</option>
                            <option value="daily" {{ old('home_collection_popup_frequency', $settings->home_collection_popup_frequency ?? 'session') == 'daily' ? 'selected' : '' }}>Per day</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Announcement Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </span>
                    <h3 class="font-semibold text-slate-900">Announcement Bar</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <label for="announcement_enabled" class="text-sm font-medium text-slate-700 cursor-pointer">Enable</label>
                    <input type="hidden" name="announcement_enabled" value="0">
                    <input type="checkbox" name="announcement_enabled" id="announcement_enabled" value="1" {{ old('announcement_enabled', $settings->announcement_enabled ?? false) ? 'checked' : '' }} class="w-11 h-6 rounded-full border-slate-200 text-violet-600 focus:ring-violet-500/30 cursor-pointer">
                </div>
                <div>
                    <label for="announcement_text" class="block text-xs font-medium text-slate-600 mb-1">Text</label>
                    <input type="text" name="announcement_text" id="announcement_text" value="{{ old('announcement_text', $settings->announcement_text ?? '') }}" placeholder="e.g. Holiday timings: Open 9 AM - 2 PM" class="block w-full rounded-lg border-slate-200 text-sm py-2">
                </div>
                <div>
                    <label for="announcement_link" class="block text-xs font-medium text-slate-600 mb-1">Link URL (optional)</label>
                    <input type="text" name="announcement_link" id="announcement_link" value="{{ old('announcement_link', $settings->announcement_link ?? '') }}" placeholder="/contact or https://..." class="block w-full rounded-lg border-slate-200 text-sm py-2">
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-800 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <h3 class="font-semibold text-slate-900">SEO (Site-wide)</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-2">Meta title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $settings->meta_title ?? '') }}" placeholder="{{ currentLab()?->name ?? 'HealthMatrix' }} - Diagnostic Lab" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-2">Meta description</label>
                    <textarea name="meta_description" id="meta_description" rows="2" placeholder="Short description for search results." class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">{{ old('meta_description', $settings->meta_description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="og_image" class="block text-sm font-medium text-slate-700 mb-2">OG image (optional)</label>
                    <input type="file" name="og_image" id="og_image" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 file:transition-colors">
                    @if($settings->og_image ?? null)
                        <p class="mt-2 text-xs text-slate-500">Current: <img src="{{ \Illuminate\Support\Facades\Storage::url($settings->og_image) }}" alt="OG" class="inline h-8 w-auto rounded border border-slate-200"> Upload to replace.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Social media --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </span>
                    <h3 class="font-semibold text-slate-900">Footer – Social media</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(['facebook_url' => ['Facebook', 'https://facebook.com/...'], 'twitter_url' => ['X (Twitter)', 'https://x.com/...'], 'instagram_url' => ['Instagram', 'https://instagram.com/...'], 'linkedin_url' => ['LinkedIn', 'https://linkedin.com/...'], 'youtube_url' => ['YouTube', 'https://youtube.com/...']] as $key => $label)
                        <div>
                            <label for="{{ $key }}" class="block text-xs font-medium text-slate-600 mb-1.5">{{ $label[0] }}</label>
                            <input type="url" name="{{ $key }}" id="{{ $key }}" value="{{ old($key, $settings->$key ?? '') }}" placeholder="{{ $label[1] }}" class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm py-2.5">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Landing Pages --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-violet-500 to-purple-600">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Landing Pages</h3>
                        <p class="text-sm text-white/80">Create and edit dynamic pages</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-violet-600 font-medium shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Page
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 text-white font-medium backdrop-blur hover:bg-white/30 transition-all">
                        Manage All
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($cmsPages->isNotEmpty())
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Slug</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($cmsPages->take(10) as $p)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="font-medium text-slate-900">{{ $p->title }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <code class="text-sm text-slate-500 font-mono">/{{ $p->slug }}</code>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $p->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($p->status) }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ url($p->slug) }}" target="_blank" rel="noopener" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.pages.edit', $p) }}" class="p-2 rounded-lg text-violet-600 hover:bg-violet-50 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($cmsPages->count() > 10)
                    <p class="mt-3 text-sm text-slate-500">Showing latest 10. <a href="{{ route('admin.pages.index') }}" class="text-violet-600 hover:text-violet-700 font-medium">View all</a></p>
                @endif
            @else
                <div class="text-center py-12 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50">
                    <div class="inline-flex p-4 rounded-full bg-slate-200/80 text-slate-500 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-slate-600 font-medium">No pages yet</p>
                    <p class="text-sm text-slate-500 mt-1 mb-4">Create your first page to get started</p>
                    <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 text-white font-medium hover:bg-violet-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Page
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Edit menu item modal --}}
<div id="editMenuItemModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5 transform transition-all">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Edit menu link</h4>
                <input type="hidden" id="edit_menu_item_id" value="">
                <div class="space-y-4">
                    <div>
                        <label for="edit_nav_title" class="block text-sm font-medium text-slate-700 mb-2">Label</label>
                        <input type="text" id="edit_nav_title" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5" aria-label="Edit link label">
                    </div>
                    <div>
                        <label for="edit_nav_url" class="block text-sm font-medium text-slate-700 mb-2">URL</label>
                        <input type="text" id="edit_nav_url" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5" aria-label="Edit link URL">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="button" id="editMenuItemSubmitBtn" class="px-5 py-2.5 rounded-xl bg-violet-600 text-white font-medium hover:bg-violet-700 shadow-lg shadow-violet-500/25 transition-all">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<x-tinymce-editor selector="#header_content, #footer_content, #content_after_banner" :height="300" form-id="settings-form" />

<script>
var editMenuBaseUrl = '{{ url("admin/menus/items") }}';
var editMenuRedirect = '{{ route("admin.settings.index", ["tab" => "cms"]) }}';
function editMenuItem(id, title, url) {
    document.getElementById('edit_menu_item_id').value = id;
    document.getElementById('edit_nav_title').value = title;
    document.getElementById('edit_nav_url').value = url;
    document.getElementById('editMenuItemModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditModal() {
    document.getElementById('editMenuItemModal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.getElementById('editMenuItemSubmitBtn').addEventListener('click', function() {
    var id = document.getElementById('edit_menu_item_id').value;
    var title = document.getElementById('edit_nav_title').value.trim();
    var url = document.getElementById('edit_nav_url').value.trim();
    if (!id || !title || !url) return;
    var fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('_method', 'PUT');
    fd.append('title', title);
    fd.append('url', url);
    fd.append('redirect', editMenuRedirect);
    fetch(editMenuBaseUrl + '/' + id, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function() { window.location.href = editMenuRedirect; });
});

document.querySelectorAll('.cms-delete-menu-item').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (!confirm('Remove this link?')) return;
        var url = this.getAttribute('data-url');
        var redirect = this.getAttribute('data-redirect');
        var fd = new FormData();
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        fd.append('_method', 'DELETE');
        fd.append('redirect', redirect);
        fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function() { window.location.href = redirect; });
    });
});
document.querySelectorAll('.cms-delete-banner').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (!confirm('Delete this banner?')) return;
        var url = this.getAttribute('data-url');
        var redirect = this.getAttribute('data-redirect');
        var fd = new FormData();
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        fd.append('_method', 'DELETE');
        fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function() { window.location.href = redirect; });
    });
});
var addNavBtn = document.getElementById('cms-add-nav-btn');
if (addNavBtn) {
    addNavBtn.addEventListener('click', function() {
        var title = document.getElementById('nav_title').value.trim();
        var url = document.getElementById('nav_url').value.trim();
        if (!title || !url) return;
        var postUrl = document.getElementById('cms_add_nav_url').value;
        var redirect = document.getElementById('cms_add_nav_redirect').value;
        var fd = new FormData();
        fd.append('_token', document.getElementById('cms_add_nav_csrf').value);
        fd.append('title', title);
        fd.append('url', url);
        fd.append('redirect', redirect);
        fetch(postUrl, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function() { window.location.href = redirect; });
    });
}
</script>
