<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Website Settings') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm sm:rounded-lg">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="p-6">
            <form method="POST" action="{{ route('admin.website-settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Logo & Branding</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                @if($settings->logo)
                                    <img src="{{ Storage::url($settings->logo) }}" alt="Logo" class="mt-2 h-20">
                                @endif
                                <input type="file" name="logo" id="logo" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $settings->primary_color) }}"
                                    class="mt-1 block w-full h-10 rounded-md border-gray-300">
                                @error('primary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $settings->phone) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $settings->email) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" id="address" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $settings->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Hero Section</h3>
                        <div class="space-y-6">
                            <div>
                                <label for="hero_title" class="block text-sm font-medium text-gray-700">Hero Title</label>
                                <input type="text" name="hero_title" id="hero_title" value="{{ old('hero_title', $settings->hero_title) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('hero_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700">Hero Subtitle</label>
                                <textarea name="hero_subtitle" id="hero_subtitle" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                                @error('hero_subtitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hero_image" class="block text-sm font-medium text-gray-700">Hero Image</label>
                                @if($settings->hero_image)
                                    <img src="{{ Storage::url($settings->hero_image) }}" alt="Hero" class="mt-2 h-40">
                                @endif
                                <input type="file" name="hero_image" id="hero_image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('hero_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">WhatsApp Integration</h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-green-800">
                                <strong>Note:</strong> Configure WhatsApp Business API using Twilio. Get your credentials from 
                                <a href="https://www.twilio.com/whatsapp" target="_blank" class="underline">Twilio WhatsApp</a>.
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" 
                                    {{ old('whatsapp_enabled', $settings->whatsapp_enabled) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="whatsapp_enabled" class="ml-2 block text-sm text-gray-900">
                                    Enable WhatsApp Notifications
                                </label>
                            </div>
                            
                            <div>
                                <label for="whatsapp_account_sid" class="block text-sm font-medium text-gray-700">Twilio Account SID</label>
                                <input type="text" name="whatsapp_account_sid" id="whatsapp_account_sid" 
                                    value="{{ old('whatsapp_account_sid', $settings->whatsapp_account_sid) }}"
                                    placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('whatsapp_account_sid')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="whatsapp_auth_token" class="block text-sm font-medium text-gray-700">Twilio Auth Token</label>
                                <input type="password" name="whatsapp_auth_token" id="whatsapp_auth_token" 
                                    value="{{ old('whatsapp_auth_token', $settings->whatsapp_auth_token) }}"
                                    placeholder="Your auth token"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('whatsapp_auth_token')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="whatsapp_from_number" class="block text-sm font-medium text-gray-700">WhatsApp From Number</label>
                                <input type="text" name="whatsapp_from_number" id="whatsapp_from_number" 
                                    value="{{ old('whatsapp_from_number', $settings->whatsapp_from_number) }}"
                                    placeholder="+14155238886 (Twilio Sandbox) or your WhatsApp Business number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-1 text-xs text-gray-500">Format: +1234567890 (include country code)</p>
                                @error('whatsapp_from_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Footer</h3>
                        <div>
                            <label for="footer_text" class="block text-sm font-medium text-gray-700">Footer Text</label>
                            <textarea name="footer_text" id="footer_text" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('footer_text', $settings->footer_text) }}</textarea>
                            @error('footer_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
