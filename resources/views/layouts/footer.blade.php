<footer class="border-t border-secondary-100 bg-primary-900 text-white/70 dark:border-secondary-800">
    <div class="site-container grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-5">
        <div class="md:col-span-2">
            <img src="{{ asset('images/brand/logo-dark.svg') }}" class="h-10 w-auto" alt="VATGER Logo">
            <p id="slogan_one" class="mt-5 max-w-md text-sm leading-6 text-secondary-300"></p>
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach([
                    ['https://www.facebook.com/vatger/', 'facebook', 'Facebook'],
                    ['https://twitter.com/vatger', 'twitter', 'Twitter'],
                    ['https://www.instagram.com/vatger/', 'instagram', 'Instagram'],
                    ['https://www.twitch.tv/vatsimgermany', 'twitch', 'Twitch'],
                    ['https://www.youtube.com/user/vatsimgermany', 'youtube', 'YouTube'],
                ] as [$href, $icon, $label])
                    <a href="{{ $href }}" target="_blank" rel="noopener" aria-label="{{ $label }}"
                       class="inline-flex size-10 items-center justify-center rounded-2xl border border-primary-700 hover:border-accent-500 hover:text-accent-400">
                        <i data-feather="{{ $icon }}" class="size-4"></i>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <h2 class="font-semibold text-white">VATGER</h2>
            <ul class="mt-4 grid gap-2 text-sm">
                <li><a href="{{ route('gdpr') }}" class="hover:text-accent-400">@lang('general.footer.data-protection')</a></li>
                <li><a href="{{ route('imprint') }}" class="hover:text-accent-400">@lang('general.footer.imprint')</a></li>
                <li><a href="{{ route('terms') }}" class="hover:text-accent-400">@lang('general.footer.terms')</a></li>
                <li><a href="{{ route('satzung') }}" class="hover:text-accent-400">@lang('general.footer.satzung')</a></li>
                <li><a href="{{ route('policy-list') }}" class="hover:text-accent-400">@lang('general.footer.further')</a></li>
            </ul>
        </div>

        <div>
            <h2 class="font-semibold text-white">@lang('general.footer.helpful-links')</h2>
            <ul class="mt-4 grid gap-2 text-sm">
                <li><a href="https://vatger-fv.de/" target="_blank" rel="noopener" class="hover:text-accent-400">VATGER Förderverein</a></li>
                <li><a href="https://aip.dfs.de/basicAIP/" target="_blank" rel="noopener" class="hover:text-accent-400">DFS Basic AIP</a></li>
            </ul>
        </div>

        <div class="flex flex-col items-start gap-5">
            <a href="https://vatsim.net" target="_blank" rel="noopener"><img src="{{ iasset('images/vatsim/VATSIM_Logo_White_500px.png', 300) }}" class="h-10 w-auto" alt="VATSIM"></a>
            <a href="https://vateud.net" target="_blank" rel="noopener"><img src="{{ iasset('images/vateud.png', 300) }}" class="h-10 w-auto" alt="VATSIM Europe Division"></a>
        </div>
    </div>

    <div class="border-t border-secondary-800">
        <div class="site-container flex flex-col items-center justify-between gap-4 py-6 text-sm sm:flex-row">
            <p>&copy; {{ now()->year }} VATSIM Germany</p>
            <div class="flex items-center gap-2">
                <x-preferences.language-switch />
                <x-preferences.theme-switch />
            </div>
        </div>
    </div>
</footer>
