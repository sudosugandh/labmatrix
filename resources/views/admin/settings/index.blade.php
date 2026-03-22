<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3" role="alert">
                    <p class="font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        Please fix the following:
                    </p>
                    <ul class="mt-2 list-disc list-inside text-sm space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Settings</h2>
                    <p class="text-slate-600 mt-1">Manage your lab configuration and preferences</p>
                </div>
                <button type="submit" form="settings-form" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-lg shadow-violet-500/25 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 focus:ring-4 focus:ring-violet-500/30 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes
                </button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-200/80 overflow-hidden max-w-full">
        <!-- Tabs Navigation -->
        <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-violet-50/30 overflow-x-auto overflow-y-hidden">
            <nav class="flex -mb-px shrink-0 min-w-max px-4" aria-label="Tabs">
                <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" 
                   class="{{ $activeTab === 'general' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    General
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'branding']) }}" 
                   class="{{ $activeTab === 'branding' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    Branding
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'contact']) }}" 
                   class="{{ $activeTab === 'contact' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Contact
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'features']) }}" 
                   class="{{ $activeTab === 'features' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Features
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'printer']) }}" 
                   class="{{ $activeTab === 'printer' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Printer Settings
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'payments']) }}" 
                   class="{{ $activeTab === 'payments' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Payments
                    @if($settings->payment_gateway)
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Configured</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'languages']) }}" 
                   class="{{ $activeTab === 'languages' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Languages
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'security']) }}" 
                   class="{{ $activeTab === 'security' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Security
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'cms']) }}" 
                   class="{{ $activeTab === 'cms' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    Website / CMS
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'alerts']) }}" 
                   class="{{ $activeTab === 'alerts' ? 'border-violet-500 text-violet-600 bg-white/80 shadow-sm' : 'border-transparent text-slate-600 hover:text-violet-600 hover:border-violet-300' }} whitespace-nowrap py-4 px-5 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Alerts & Notifications
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <script>
            (function() {
                var form = document.getElementById('settings-form');
                if (form) {
                    form.addEventListener('submit', function() {
                        var methodInputs = form.querySelectorAll('input[name="_method"]');
                        for (var i = 0; i < methodInputs.length; i++) methodInputs[i].remove();
                        var put = document.createElement('input');
                        put.type = 'hidden';
                        put.name = '_method';
                        put.value = 'PUT';
                        form.appendChild(put);
                    });
                }
            })();
            </script>

            @if($activeTab === 'general')
                @include('admin.settings.tabs.general')
            @elseif($activeTab === 'branding')
                @include('admin.settings.tabs.branding')
            @elseif($activeTab === 'contact')
                @include('admin.settings.tabs.contact')
            @elseif($activeTab === 'features')
                @include('admin.settings.tabs.features')
            @elseif($activeTab === 'printer')
                @include('admin.settings.tabs.printer')
            @elseif($activeTab === 'payments')
                @include('admin.settings.tabs.payments')
            @elseif($activeTab === 'languages')
                @include('admin.settings.tabs.languages')
            @elseif($activeTab === 'security')
                @include('admin.settings.tabs.security')
            @elseif($activeTab === 'cms')
                @include('admin.settings.tabs.cms')
            @elseif($activeTab === 'alerts')
                @include('admin.settings.tabs.alerts')
            @endif
        </form>
    </div>
</x-app-layout>
