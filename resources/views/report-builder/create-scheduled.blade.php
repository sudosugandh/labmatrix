<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Schedule Report</h2>
                <p class="text-sm text-gray-500 mt-1">Set up automated report generation and email delivery</p>
            </div>
            <a href="{{ route('report-builder.scheduled') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                ← Back to Scheduled Reports
            </a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('report-builder.scheduled.store') }}" class="max-w-4xl">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Template *</label>
                <select name="report_template_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Template</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" {{ ($templateId ?? null) == $template->id ? 'selected' : '' }}>
                            {{ $template->name }} ({{ ucfirst($template->report_type) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Name *</label>
                    <input type="text" name="name" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Monthly Patient Report">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frequency *</label>
                    <select name="frequency" id="frequency" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional description"></textarea>
            </div>

            <div id="schedule-config" class="space-y-4">
                <!-- Schedule config will be shown based on frequency -->
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Send Time *</label>
                    <input type="time" name="send_time" value="09:00" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format *</label>
                    <select name="format" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="pdf" selected>PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recipients (Email Addresses) *</label>
                <div id="recipients-container" class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="email" name="recipients[]" required class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="email@example.com">
                        <button type="button" onclick="addRecipient()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">+ Add</button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('report-builder.scheduled') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                    Create Schedule
                </button>
            </div>
        </div>
    </form>

    <script>
    let recipientCount = 1;
    
    function addRecipient() {
        const container = document.getElementById('recipients-container');
        const html = `
            <div class="flex items-center gap-2" id="recipient-${recipientCount}">
                <input type="email" name="recipients[]" required class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="email@example.com">
                <button type="button" onclick="removeRecipient(${recipientCount})" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        recipientCount++;
    }
    
    function removeRecipient(id) {
        document.getElementById('recipient-' + id)?.remove();
    }
    
    document.getElementById('frequency')?.addEventListener('change', function() {
        const config = document.getElementById('schedule-config');
        const frequency = this.value;
        
        let html = '';
        if (frequency === 'weekly') {
            html = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Day of Week *</label>
                    <select name="schedule_config[day_of_week]" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                        <option value="7">Sunday</option>
                    </select>
                </div>
            `;
        } else if (frequency === 'monthly') {
            html = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Day of Month *</label>
                    <input type="number" name="schedule_config[day_of_month]" min="1" max="28" value="1" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">Day of the month to run (1-28)</p>
                </div>
            `;
        }
        
        config.innerHTML = html;
    });
    
    // Trigger on load if frequency is already selected
    document.getElementById('frequency')?.dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
