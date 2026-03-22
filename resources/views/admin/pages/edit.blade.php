<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pages.index') }}" class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Page</h2>
                <p class="text-sm text-slate-500">/{{ $page->slug }}</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <form id="page-form" method="POST" action="{{ route('admin.pages.update', $page) }}" class="p-6 md:p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                            class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-3 px-4 text-slate-900 placeholder-slate-400"
                            placeholder="e.g. About Us">
                        @error('title')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}"
                            class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5 px-4 font-mono text-sm"
                            placeholder="url-slug">
                        <p class="mt-1.5 text-xs text-slate-500">Leave empty to auto-generate from title</p>
                        @error('slug')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-slate-700 mb-2">Content</label>
                        <textarea name="content" id="content" rows="12"
                            class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20"
                            placeholder="Page content...">{{ old('content', $page->content) }}</textarea>
                        @error('content')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="p-5 rounded-xl bg-slate-50/80 border border-slate-100">
                        <h4 class="text-sm font-semibold text-slate-900 mb-4">Publish</h4>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Status *</label>
                            <select name="status" id="status" required
                                class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                                <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="p-5 rounded-xl bg-slate-50/80 border border-slate-100 space-y-4">
                        <h4 class="text-sm font-semibold text-slate-900">SEO</h4>
                        <div>
                            <label for="meta_title" class="block text-xs font-medium text-slate-600 mb-1.5">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                                class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2 text-sm"
                                placeholder="Page title for search engines">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="meta_description" class="block text-xs font-medium text-slate-600 mb-1.5">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2 text-sm"
                                placeholder="Short description for search results">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <a href="{{ url($page->slug) }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        View Page
                    </a>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Update Page
                </button>
            </div>
        </form>
    </div>
<x-tinymce-editor selector="#content" :height="420" form-id="page-form" />
</x-app-layout>
