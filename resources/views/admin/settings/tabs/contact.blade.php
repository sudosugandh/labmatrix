{{-- Contact Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Contact Information</h3>
                    <p class="text-sm text-slate-500">Primary contact details for your lab</p>
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
            </div>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Inquiry Contact</h3>
                    <p class="text-sm text-slate-500">Used for contact forms and patient inquiries</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-2">Contact Email</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings->contact_email ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="inquiries@example.com">
                </div>
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-2">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings->contact_phone ?? '') }}" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="+1 234 567 8900">
                </div>
            </div>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Address</h3>
                    <p class="text-sm text-slate-500">Physical address of your lab</p>
                </div>
            </div>
            <textarea name="address" id="address" rows="3" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="Street, City, State, ZIP">{{ old('address', $settings->address ?? '') }}</textarea>
        </div>
    </div>

    {{-- SMTP Configurations --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-violet-50/30 border-b border-slate-100">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">SMTP Configurations</h3>
                        <p class="text-sm text-slate-600">Manage email providers for this lab</p>
                    </div>
                </div>
                <button type="button" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 text-white text-sm font-medium shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Configuration
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-6 text-center">
                <div class="inline-flex p-4 rounded-full bg-slate-200/80 text-slate-500 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-sm text-slate-600 font-medium">No SMTP configurations added yet</p>
                <p class="text-xs text-slate-500 mt-1">Click "Add Configuration" to set up an email provider for sending reports and notifications.</p>
            </div>
        </div>
    </div>
</div>
