<div class="admin-shell">
    @include('layouts.admin.admin-nav')

    <button id="admin-sidebar-backdrop" class="admin-sidebar-backdrop" type="button" aria-label="Close navigation"></button>

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="flex items-center gap-3">
                <button id="admin-sidebar-toggle" class="admin-icon-button lg:hidden" type="button"
                        aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open navigation">
                    <i data-feather="menu"></i>
                </button>
                <a href="{{ route('administration.dashboard') }}" class="lg:hidden">
                    <img src="{{ asset('images/brand/logo-light.svg') }}" class="h-9 w-auto dark:hidden" alt="{{ config('app.name') }}">
                    <img src="{{ asset('images/brand/logo-dark.svg') }}" class="hidden h-9 w-auto dark:block" alt="{{ config('app.name') }}">
                </a>
                <span class="hidden text-sm font-bold uppercase tracking-widest text-secondary-500 lg:block">Administration</span>
            </div>

            <div class="admin-topbar-actions">
                <x-preferences.theme-switch />
                <span class="admin-user">{{ Auth::user()->username }}</span>
                <a href="{{ route('member.profile.notifications') }}" class="admin-icon-button" aria-label="Notifications">
                    <i data-feather="bell"></i>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="admin-notification-dot"></span>
                    @endif
                </a>
                <a href="{{ route('landing') }}" class="admin-icon-button" aria-label="Leave administration">
                    <i data-feather="log-out"></i>
                </a>
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <footer class="admin-footer">
            &copy; {{ Carbon\Carbon::now()->utc()->format('Y') }} {{ config('app.name') }}.
        </footer>
    </div>
</div>
