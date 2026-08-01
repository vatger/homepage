<aside id="admin-sidebar" class="admin-sidebar" aria-label="Administration navigation">
    <div class="admin-sidebar-brand">
        <a href="{{ route('landing') }}">
            <img src="{{ asset('images/brand/logo-light.svg') }}" class="dark:hidden" alt="{{ config('app.name') }}">
            <img src="{{ asset('images/brand/logo-dark.svg') }}" class="hidden dark:block" alt="{{ config('app.name') }}">
        </a>
    </div>

    <nav class="admin-sidebar-nav">
        @can('administration.access')
            <a class="admin-nav-link {{ request()->routeIs('administration.dashboard') ? 'is-active' : '' }}" href="{{ route('administration.dashboard') }}">
                <i data-feather="home"></i><span>Dashboard</span>
            </a>
        @endcan
        @can('membership.users.view')
            <a class="admin-nav-link {{ request()->routeIs('administration.members*') ? 'is-active' : '' }}" href="{{ route('administration.members') }}">
                <i data-feather="user"></i><span>Members</span>
            </a>
        @endcan
        @canany(['membership.teams.view', 'membership.teams.edit.members.subteam'])
            <a class="admin-nav-link {{ request()->routeIs('administration.teams*') ? 'is-active' : '' }}" href="{{ route('administration.teams') }}">
                <i data-feather="users"></i><span>Teams</span>
            </a>
        @endcanany
        @can('mail.manage')
            <a class="admin-nav-link {{ request()->routeIs('administration.email*') ? 'is-active' : '' }}" href="{{ route('administration.email') }}">
                <i data-feather="mail"></i><span>Staff E-Mail</span>
            </a>
        @endcan
        @can('survey')
            <a class="admin-nav-link {{ request()->routeIs('administration.survey*') ? 'is-active' : '' }}" href="{{ route('administration.survey') }}">
                <i data-feather="clipboard"></i><span>Survey</span>
            </a>
        @endcan
        @can('navigation.aerodromes.view')
            <a class="admin-nav-link {{ request()->routeIs('administration.navigation.aerodromes*') ? 'is-active' : '' }}" href="{{ route('administration.navigation.aerodromes') }}">
                <i data-feather="map"></i><span>Aerodromes</span>
            </a>
        @endcan
        @can('navigation.stations.view')
            <a class="admin-nav-link {{ request()->routeIs('administration.navigation.stations*') ? 'is-active' : '' }}" href="{{ route('administration.navigation.stations') }}">
                <i data-feather="map-pin"></i><span>Stations</span>
            </a>
        @endcan
        <a class="admin-nav-link {{ request()->routeIs('administration.tech.apilog*') ? 'is-active' : '' }}" href="{{ route('administration.tech.apilog') }}">
            <i data-feather="list"></i><span>API Log</span>
        </a>
        <a class="admin-nav-link {{ request()->routeIs('administration.tech.syslog*') ? 'is-active' : '' }}" href="{{ route('administration.tech.syslog') }}">
            <i data-feather="alert-triangle"></i><span>SYS Log</span>
        </a>
        <a class="admin-nav-link {{ request()->routeIs('administration.tech.jobs*') ? 'is-active' : '' }}" href="{{ route('administration.tech.jobs') }}">
            <i data-feather="terminal"></i><span>Job Log</span>
        </a>
        <a class="admin-nav-link {{ request()->routeIs('administration.tech.gdpr*') ? 'is-active' : '' }}" href="{{ route('administration.tech.gdpr') }}">
            <i data-feather="user-x"></i><span>GDPR Log</span>
        </a>
        <a class="admin-nav-link {{ request()->routeIs('administration.tech.openidconnect*') ? 'is-active' : '' }}" href="{{ route('administration.tech.openidconnect') }}">
            <i data-feather="key"></i><span>OpenID Connect</span>
        </a>
    </nav>

    <div class="admin-sidebar-footer">
        <a class="admin-nav-link" href="{{ route('landing') }}"><i data-feather="arrow-left"></i><span>Back to website</span></a>
    </div>
</aside>
