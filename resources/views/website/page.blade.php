@extends('website.layout')

@section('content')
    {{-- Page content from TinyMCE (SS4/SS5: grids, tables, accordions, speakers, schedule) --}}
    <div class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="cms-content">
                {{-- Optional page title with decorative line (like Speakers, Event Schedule) --}}
                <div class="mb-8">
                    <div class="w-12 h-1 rounded mb-4" style="background-color: var(--primary-color);"></div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $page->title }}</h1>
                </div>
                <div class="cms-content-body">
                    {!! $page->content !!}
                </div>
                @if($page->slug === 'contact' && isset($settings))
                <div class="mt-8 pt-8 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($settings->phone)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                            <p class="text-gray-600">{{ $settings->phone }}</p>
                        </div>
                    @endif
                    @if($settings->email)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">{{ $settings->email }}</p>
                        </div>
                    @endif
                    @if($settings->address)
                        <div class="md:col-span-2">
                            <h3 class="font-semibold text-gray-900 mb-2">Address</h3>
                            <p class="text-gray-600">{{ $settings->address }}</p>
                        </div>
                    @endif
                </div>
                @endif
            </article>
        </div>
    </div>
@endsection
