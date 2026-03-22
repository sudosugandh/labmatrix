<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Menus</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage navigation structure. Main Menu = header nav, Footer Menu = footer links.</p>
            </div>
            <button type="button" onclick="createMenu()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Menu
            </button>
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

    @if($menus->count() > 0)
        <div class="space-y-8">
            @foreach($menus as $menu)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-violet-50/30 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-500 text-white shadow-lg shadow-violet-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $menu->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $menu->items->count() }} {{ Str::plural('item', $menu->items->count()) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="menu-items-{{ $menu->id }}" class="space-y-2 mb-6">
                            @foreach($menu->items as $item)
                                <div class="flex items-center justify-between py-3 px-4 bg-slate-50/80 rounded-xl border border-slate-100 hover:border-violet-100 hover:bg-violet-50/30 transition-all group/item cursor-move menu-item" data-item-id="{{ $item->id }}">
                                    <div class="flex items-center gap-4">
                                        <span class="text-slate-400 drag-handle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                        </span>
                                        <span class="font-medium text-slate-900">{{ $item->title }}</span>
                                        <code class="text-sm text-slate-500 font-mono">{{ $item->url }}</code>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="editItem({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->url) }}')" class="p-2 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-100 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.menus.items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Remove this link?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            @if($menu->items->isEmpty())
                                <div class="py-12 text-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50">
                                    <p class="text-slate-500">No items yet. Add one below.</p>
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('admin.menus.items.store', $menu) }}" class="flex flex-wrap items-end gap-3 p-4 bg-slate-50/50 rounded-xl border border-slate-100">
                            @csrf
                            <div class="flex-1 min-w-[140px]">
                                <label for="title-{{ $menu->id }}" class="block text-xs font-medium text-slate-600 mb-1.5">Label</label>
                                <input type="text" name="title" id="title-{{ $menu->id }}" required placeholder="e.g. About"
                                    class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm py-2.5">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label for="url-{{ $menu->id }}" class="block text-xs font-medium text-slate-600 mb-1.5">URL</label>
                                <input type="text" name="url" id="url-{{ $menu->id }}" required placeholder="e.g. /about"
                                    class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 text-sm py-2.5">
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium shadow-lg shadow-violet-500/25 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Item
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="text-center py-16 px-6">
                <div class="inline-flex p-5 rounded-2xl bg-slate-100 text-slate-400 mb-6">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">No menus yet</h3>
                <p class="mt-2 text-slate-500 max-w-md mx-auto">Create a <strong>Main Menu</strong> for the header navigation and optionally a <strong>Footer Menu</strong> for footer links (Privacy, Terms, etc.).</p>
                <button type="button" onclick="createMenu()" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-700 transition-colors shadow-lg shadow-violet-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Menu
                </button>
            </div>
        </div>
    @endif

    <!-- Create Menu Modal -->
    <div id="createMenuModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeMenuModal()"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5 transform transition-all">
                <form method="POST" action="{{ route('admin.menus.store') }}" class="p-6">
                    @csrf
                    <h4 class="text-lg font-semibold text-slate-900 mb-4">Create Menu</h4>
                    <div>
                        <label for="menu_name" class="block text-sm font-medium text-slate-700 mb-2">Menu Name</label>
                        <input type="text" name="name" id="menu_name" required placeholder="e.g. Main Menu or Footer Menu"
                            class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5 px-4">
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeMenuModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-violet-600 text-white font-medium hover:bg-violet-700 shadow-lg shadow-violet-500/25 transition-all">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5 transform transition-all">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-slate-900 mb-4">Edit menu link</h4>
                    <input type="hidden" id="edit_item_id" value="">
                    <form id="editItemForm" method="POST" action="" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                        <div>
                            <label for="edit_title" class="block text-sm font-medium text-slate-700 mb-2">Label</label>
                            <input type="text" name="title" id="edit_title" required class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                        </div>
                        <div>
                            <label for="edit_url" class="block text-sm font-medium text-slate-700 mb-2">URL</label>
                            <input type="text" name="url" id="edit_url" required class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 py-2.5">
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50">Cancel</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-violet-600 text-white font-medium hover:bg-violet-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function createMenu() {
            document.getElementById('createMenuModal').classList.remove('hidden');
            document.getElementById('menu_name').focus();
            document.body.style.overflow = 'hidden';
        }
        function closeMenuModal() {
            document.getElementById('createMenuModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        function editItem(id, title, url) {
            var baseUrl = '{{ url("admin/menus/items") }}';
            document.getElementById('edit_item_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_url').value = url;
            document.getElementById('editItemForm').action = baseUrl + '/' + id;
            document.getElementById('editItemModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeEditModal() {
            document.getElementById('editItemModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('[id^="menu-items-"]').forEach(function(container) {
            if (container.querySelectorAll('.menu-item').length > 0) {
                new Sortable(container, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        var items = [];
                        container.querySelectorAll('.menu-item').forEach(function(el) {
                            items.push(el.getAttribute('data-item-id'));
                        });
                        fetch('{{ route("admin.menus.items.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ items: items })
                        });
                    }
                });
            }
        });
    </script>
</x-app-layout>
