@extends('website.layout')

@section('content')
    @php
        $heroSubtitle = trim((string) ($page->meta_description ?? ''));
    @endphp

    @include('website.partials.inner-page-hero', [
        'settings' => $settings,
        'title' => $page->title,
        'subtitle' => $heroSubtitle,
        'waveFillClass' => 'text-slate-50',
    ])

    @include('website.partials.trust-strip', ['settings' => $settings])

    @include('website.partials.page-promos-teaser', ['settings' => $settings, 'promos' => $pagePromos])

    <section class="relative -mt-px py-14 md:py-20 bg-gradient-to-b from-slate-50/80 to-white border-t border-slate-100/80">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="cms-content rounded-2xl border border-slate-200/60 bg-white px-6 py-8 md:px-10 md:py-10 shadow-sm shadow-slate-200/40">
                <div class="cms-content-body prose prose-slate prose-lg max-w-none
                    prose-headings:font-display prose-headings:tracking-tight prose-headings:text-slate-900
                    prose-p:text-slate-600 prose-p:leading-relaxed">
                    {!! $page->content !!}
                </div>
            </article>
        </div>
    </section>
@endsection
