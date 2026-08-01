@php
    $isLandingPage = request()->routeIs('landing');
    $menus = [
        __('navigation.piloten.titel') => [
            [route('redirect.knowledgebase.start-pilot'), __('navigation.piloten.erste-schritte'), true],
            [route('redirect.knowledgebase.training-pilot'), __('navigation.piloten.training'), true],
            [route('pilots.aerodromes.viewall'), __('navigation.piloten.flugplaetze'), false],
            [route('redirect.vatger-tours'), 'VATGER Touren', false],
            [route('redirect.pmp'), 'Pilot Mentoring', false],
        ],
        __('navigation.lotsen.titel') => [
            [route('redirect.knowledgebase.start-atc'), __('navigation.lotsen.erste-schritte'), true],
            [route('controllers.booking'), __('navigation.user.booking'), false],
            [route('redirect.training-center'), 'ATC Training', true],
            [route('redirect.sectorfiles'), 'Sectorfiles', true],
            [route('controllers.restricted'), 'Restricted Stations', false],
            [route('controllers.s1'), 'S1 Tower', false],
            [route('controllers.s1-stations'), 'S1 Stations', false],
            [route('controllers.required-courses'), 'Required courses', false],
            [route('redirect.support.feedback'), __('navigation.lotsen.feedback'), true],
        ],
        __('navigation.community.titel') => [
            [route('redirect.ts3'), __('navigation.community.teamspeak'), false],
            [route('redirect.board'), __('navigation.community.forum'), true],
            [route('redirect.discord'), __('navigation.community.discord'), true],
            [route('redirect.knowledgebase'), __('navigation.community.wiki'), true],
            [route('redirect.moodle'), __('navigation.community.moodle'), true],
            [route('redirect.spreadshop'), __('navigation.community.fan-shop'), true],
        ],
        __('navigation.hilfe.titel') => [
            [route('redirect.support'), __('navigation.hilfe.support'), false],
            [route('redirect.knowledgebase.contact'), __('navigation.hilfe.personal'), true],
        ],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-secondary-200 bg-white/95 text-primary-900 shadow-sm backdrop-blur dark:border-secondary-800 dark:bg-secondary-900/95 dark:text-secondary-50"
        x-data="{ mobile: false, scrolled: window.scrollY > 24 }"
        @scroll.window="scrolled = window.scrollY > 24"
        @keydown.escape.window="mobile = false">
    <div class="site-container flex h-20 items-center justify-between gap-4 transition-[height] duration-200"
         @if($isLandingPage) :class="scrolled ? 'h-20' : 'h-28'" @endif>
        <a href="{{ route('landing') }}" class="shrink-0" aria-label="{{ config('app.name') }}">
            <img src="{{ asset('images/brand/logo-light.svg') }}"
                 class="w-auto transition-[height] duration-200 dark:hidden {{ $isLandingPage ? '' : 'h-16' }}"
                 @if($isLandingPage) style="height: 6rem" :style="{ height: scrolled ? '3.5rem' : '6rem' }" @endif
                 alt="VATGER Logo">
            <img src="{{ asset('images/brand/logo-dark.svg') }}"
                 class="hidden w-auto transition-[height] duration-200 dark:block {{ $isLandingPage ? '' : 'h-16' }}"
                 @if($isLandingPage) style="height: 6rem" :style="{ height: scrolled ? '3.5rem' : '6rem' }" @endif
                 alt="VATGER Logo">
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="@lang('navigation.navigation')">
            <x-preferences.language-switch />
            <x-preferences.theme-switch />

            @foreach($menus as $label => $items)
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open" :aria-expanded="open"
                            class="inline-flex min-h-11 items-center gap-1 rounded-lg px-3 text-sm font-semibold uppercase tracking-wide hover:bg-secondary-100 hover:text-accent-600 dark:hover:bg-secondary-800 dark:hover:text-accent-400">
                        {{ $label }}
                        <i data-feather="chevron-down" class="size-4 transition-transform" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-cloak x-show="open" x-transition.origin.top.right
                         class="absolute right-0 top-full mt-2 w-64 rounded-xl border border-secondary-200 bg-white p-2 shadow-xl dark:border-secondary-700 dark:bg-secondary-800">
                        @foreach($items as [$href, $itemLabel, $external])
                            <a href="{{ $href }}" @if($external) target="_blank" rel="noopener" @endif
                               class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-secondary-100 hover:text-accent-600 dark:hover:bg-secondary-700 dark:hover:text-accent-400">
                                {{ $itemLabel }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                @auth
                    <button type="button" @click="open = !open" :aria-expanded="open"
                            class="inline-flex min-h-11 items-center gap-1 rounded-lg px-3 text-sm font-semibold uppercase tracking-wide hover:bg-secondary-100 dark:hover:bg-secondary-800">
                        {{ Auth::user()->firstname }}
                        <i data-feather="chevron-down" class="size-4"></i>
                    </button>
                    <div x-cloak x-show="open" x-transition.origin.top.right
                         class="absolute right-0 top-full mt-2 w-56 rounded-xl border border-secondary-200 bg-white p-2 shadow-xl dark:border-secondary-700 dark:bg-secondary-800">
                        <a href="{{ route('member.profile') }}" class="block rounded-lg px-3 py-2.5 text-sm hover:bg-secondary-100 dark:hover:bg-secondary-700">@lang('navigation.user.profile')</a>
                        @can('administration.access')
                            <a href="{{ route('administration.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm hover:bg-secondary-100 dark:hover:bg-secondary-700">@lang('navigation.user.administration')</a>
                        @endcan
                        <a href="{{ route('vatsim.authentication.connect.logout') }}" class="block rounded-lg px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/50">@lang('navigation.user.logout')</a>
                    </div>
                @else
                    <a href="{{ route('vatsim.authentication.connect.login') }}" class="btn btn-primary">@lang('navigation.user.login')</a>
                @endauth
            </div>

            @auth
                <a href="{{ route('member.profile') }}?tab=notifications"
                   class="relative inline-flex size-11 items-center justify-center rounded-lg hover:bg-secondary-100 dark:hover:bg-secondary-800"
                   aria-label="@lang('navigation.user.notifications')">
                    <i data-feather="bell" class="size-5"></i>
                    @if(count(Auth::user()->unreadNotifications) > 0)
                        <span class="absolute right-0 top-0 inline-flex min-w-5 items-center justify-center rounded-full bg-accent-500 px-1 text-xs font-bold text-white">{{ count(Auth::user()->unreadNotifications) }}</span>
                    @endif
                </a>
            @endauth
        </nav>

        <button type="button" @click="mobile = !mobile" :aria-expanded="mobile"
                class="inline-flex size-11 items-center justify-center rounded-lg border border-secondary-200 lg:hidden dark:border-secondary-700"
                aria-controls="mobile-navigation" aria-label="Menu">
            <span class="inline-flex" x-show="!mobile">
                <i data-feather="menu" class="size-5" aria-hidden="true"></i>
            </span>
            <span class="inline-flex" x-cloak x-show="mobile">
                <i data-feather="x" class="size-5" aria-hidden="true"></i>
            </span>
        </button>
    </div>

    <nav id="mobile-navigation" x-cloak x-show="mobile" x-transition
         class="site-container max-h-[calc(100vh-5rem)] overflow-y-auto border-t border-secondary-200 py-4 lg:hidden dark:border-secondary-800">
        <div class="mb-3 flex items-center gap-2">
            <x-preferences.language-switch />
            <x-preferences.theme-switch />
        </div>
        @foreach($menus as $label => $items)
            <details class="border-b border-secondary-200 py-2 dark:border-secondary-800">
                <summary class="cursor-pointer py-2 font-semibold">{{ $label }}</summary>
                <div class="grid gap-1 pb-2 pl-3">
                    @foreach($items as [$href, $itemLabel, $external])
                        <a href="{{ $href }}" @if($external) target="_blank" rel="noopener" @endif class="rounded-lg px-3 py-2 hover:bg-secondary-100 dark:hover:bg-secondary-800">{{ $itemLabel }}</a>
                    @endforeach
                </div>
            </details>
        @endforeach
        <div class="grid gap-1 pt-3">
            @auth
                <a href="{{ route('member.profile') }}" class="rounded-lg px-3 py-2 hover:bg-secondary-100 dark:hover:bg-secondary-800">@lang('navigation.user.profile')</a>
                @can('administration.access')
                    <a href="{{ route('administration.dashboard') }}" class="rounded-lg px-3 py-2 hover:bg-secondary-100 dark:hover:bg-secondary-800">@lang('navigation.user.administration')</a>
                @endcan
                <a href="{{ route('vatsim.authentication.connect.logout') }}" class="rounded-lg px-3 py-2 text-red-600 dark:text-red-400">@lang('navigation.user.logout')</a>
            @else
                <a href="{{ route('vatsim.authentication.connect.login') }}" class="btn btn-primary">@lang('navigation.user.login')</a>
            @endauth
        </div>
    </nav>
</header>
