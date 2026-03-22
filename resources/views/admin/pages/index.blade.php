<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">CMS Pages</h2>
                <p class="text-sm text-slate-500 mt-0.5">Create and manage dynamic website pages</p>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Page
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800" role="alert">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($pages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Updated</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach($pages as $page)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600 font-semibold text-sm">
                                            {{ substr($page->title, 0, 1) }}
                                        </span>
                                        <span class="font-medium text-slate-900">{{ $page->title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-sm text-slate-500 font-mono bg-slate-100 px-2 py-1 rounded-lg">/{{ $page->slug }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $page->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $page->updated_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ url($page->slug) }}" target="_blank" rel="noopener" class="p-2.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="p-2.5 rounded-lg text-violet-600 hover:bg-violet-50 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $pages->links() }}
            </div>
        @else
            <div class="text-center py-16 px-6">
                <div class="inline-flex p-5 rounded-2xl bg-slate-100 text-slate-400 mb-6">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">No pages yet</h3>
                <p class="mt-2 text-slate-500 max-w-sm mx-auto">Create your first page to add dynamic content to your website. Use the rich text editor for formatting, images, and more.</p>
                <a href="{{ route('admin.pages.create') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-700 transition-colors shadow-lg shadow-violet-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Your First Page
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
