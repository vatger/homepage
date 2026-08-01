@props(['partners'])

<section class="section border-t border-secondary-200 dark:border-secondary-800">
    <div class="site-container">
        <div class="mx-auto max-w-2xl text-center">
            <span class="landing-section-label">@lang('pages.landing.community')</span>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">@lang('landing.partner.title')</h2>
            <p class="mt-4 text-secondary-600 dark:text-secondary-300">@lang('landing.partner.text')</p>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($partners as $partner)
                <a href="{{ $partner->link_url }}" title="{{ $partner->name }}" target="_blank" rel="noopener"
                   class="flex min-h-28 items-center justify-center rounded-2xl border border-primary-800 bg-primary-900 p-5 transition hover:border-accent-500 hover:bg-primary-800 dark:border-secondary-700 dark:bg-primary-900 dark:hover:bg-primary-800">
                    <img src="{{ iasset($partner->logo_url, 320) }}" class="max-h-16 w-auto object-contain" alt="{{ $partner->name }}">
                </a>
            @endforeach
        </div>
    </div>
</section>
