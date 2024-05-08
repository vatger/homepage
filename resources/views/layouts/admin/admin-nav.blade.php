<!-- sidebar-wrapper -->
<nav id="sidebar" class="sidebar-wrapper sidebar-dark">
    <div class="sidebar-content" data-simplebar style="height: calc(100% - 60px);">
        <div class="sidebar-brand">
            <a href="{{ route('landing') }}">
                <img src="{{ asset('images/vacc_logo_white.png') }}" height="24" class="logo-light-mode" alt="">
                <img src="{{ asset('images/vacc_logo.png') }}" height="24" class="logo-dark-mode" alt="">
                <span class="sidebar-colored">
                <img src="{{ asset('images/vacc_logo_white.png') }}" height="24" alt=""></span>
            </a>
        </div>
        <ul class="sidebar-menu">
            @can('administration.access')
                <li>
                    <a href="{{ route('administration.dashboard') }}">
                        <i data-feather="circle" class="fea me-2"></i>
                        Dashboard
                    </a>
                </li>
            @endcan
            @can('membership.users.view')
                <li>
                    <a href="{{ route('administration.members') }}">
                        <i data-feather="user" class="fea me-2"></i>
                        Members
                    </a>
                </li>
            @endcan
            @canany(['membership.teams.view', 'membership.teams.edit.members.subteam'])
                <li>
                    <a href="{{ route('administration.teams') }}">
                        <i data-feather="users" class="fea me-2"></i>
                        Teams
                    </a>
                </li>
            @endcan
            @can('mail.manage')
                <li>
                    <a href="{{ route('administration.email') }}">
                        <i data-feather="mail" class="fea me-2"></i>
                        Staff E-Mail
                    </a>
                </li>
            @endcan
            @can('survey')
                <li>
                    <a href="{{ route('administration.survey') }}">
                        <i data-feather="circle" class="fea me-2"></i>
                        Survey
                    </a>
                </li>
            @endcan
            @can('navigation.aerodromes.view')
                <li>
                    <a href="{{ route('administration.navigation.aerodromes') }}">
                        <i data-feather="map" class="fea me-2"></i>
                        Aerodromes
                    </a>
                </li>
            @endcan
            @can('navigation.stations.view')
                <li>
                    <a href="{{ route('administration.navigation.stations') }}">
                        <i data-feather="map-pin" class="fea me-2"></i>
                        Stations
                    </a>
                </li>
            @endcan
            <li>
                <a href="{{ route('administration.tech.apilog') }}">
                    <i data-feather="list" class="fea me-2"></i>
                    API Log
                </a>
            </li>
            <li>
                <a href="{{ route('administration.tech.syslog') }}">
                    <i data-feather="alert-triangle" class="fea me-2"></i>
                    SYS Log
                </a>
            </li>
            <li>
                <a href="{{ route('administration.tech.jobs') }}">
                    <i data-feather="terminal" class="fea me-2"></i>
                    Job Log
                </a>
            </li>
            <li>
                <a href="{{ route('administration.tech.gdpr') }}">
                    <i data-feather="user-x" class="fea me-2"></i>
                    Job Log
                </a>
            </li>
        </ul>
        <!-- sidebar-menu  -->
    </div>
    <!-- Sidebar Footer -->
    {{--
    <ul class="sidebar-footer list-unstyled mb-0">
        <li class="list-inline-item mb-0">
            <a href="https://1.envato.market/landrick" target="_blank" class="btn btn-icon btn-soft-light">
                <i class="ti ti-shopping-cart"></i>
            </a>
            <small class="text-muted fw-medium ms-1">Buy Now</small>
        </li>
    </ul>
    --}}
    <!-- Sidebar Footer -->
</nav>
<!-- sidebar-wrapper  -->
