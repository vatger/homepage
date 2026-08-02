@extends('layouts.master')

@section('content')
    <section id="hero-section" class="landing-hero relative isolate flex items-center overflow-hidden bg-primary-900 bg-cover bg-center text-white"
             style="background-image: url('{{ iasset('images/hero-banners/hero_' . rand(1, 9) . '.png') }}')">
        <div class="absolute inset-0 -z-10 bg-primary-900/80"></div>
        <div class="site-container py-24 sm:py-32">
            <div class="max-w-3xl">
                <span class="landing-kicker">@lang('landing.welcome.badge-text')</span>
                <h1 class="mt-6 text-5xl font-bold tracking-tight text-white sm:text-6xl">vatger</h1>
                <p id="typewriter" class="mt-5 min-h-8 text-xl text-secondary-200 sm:text-2xl">@lang('pages.landing.hero-fallback')</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('redirect.knowledgebase.start') }}" class="btn btn-primary px-6">@lang('landing.join-now-button-content')</a>
                    <a href="#welcome" class="btn border-white/30 bg-white/5 px-6 text-white hover:bg-white/15">@lang('landing.read-more-button-content')</a>
                </div>
            </div>
        </div>
        <div class="absolute inset-x-0 bottom-0 h-16 translate-y-8 -skew-y-2 bg-secondary-50 dark:bg-secondary-900"></div>
    </section>

    <x-landing.welcome />
    <x-landing.live-traffic :traffic="$traffic" />
    <x-landing.events />
    <x-landing.partners :partners="\App\Models\Partner::all()" />
@endsection

@push('scripts')
    @vite(['resources/ts/special/landing-typewriter.ts'])
@endpush
