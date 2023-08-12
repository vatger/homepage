<nav id="sidebar" class="sidebar-wrapper sidebar-dark">
    <div class="sidebar-content" data-simplebar="init" style="height: calc(100% - 60px);">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer">
                </div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <div class="sidebar-brand">
                                <a href="{{ route('landing') }}">
                                    <img src="{{ asset('images/vacc_logo_white.png') }}" height="24" class="logo-light-mode" alt="">
                                    <img src="{{ asset('images/vacc_logo.png') }}" height="24" class="logo-dark-mode" alt="">
                                    <span class="sidebar-colored">
                                        <img src="{{ asset('images/vacc_logo_white.png') }}" height="24" alt="">
                                    </span>
                                </a>
                            </div>

                            <ul class="sidebar-menu">
                                <li>
                                    <a href="{{ route('administration.dashboard') }}"><i class="mdi mdi-monitor-dashboard me-2"></i>Übersicht</a>
                                </li>

                                <li class="sidebar-dropdown">
                                    <a href="javascript:void(0)">
                                        <i data-feather="users" class="ms-2"></i>
                                        Mitgliederverwaltung
                                    </a>
                                    <div class="sidebar-submenu">
                                        <ul>
                                            <li>
                                                <a href="{{ route('administration.members') }}">
                                                    <i data-feather="users" class="ms-2"></i>
                                                    Mitgliederverwaltung
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('administration.members') }}">
                                                    <i data-feather="user-plus" class="ms-2"></i>
                                                    Gruppenverwaltung
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="sidebar-dropdown">
                                    <a href="javascript:void(0)"><i class="mdi mdi-compass me-2"></i>Navigation</a>
                                    <div class="sidebar-submenu">
                                        <ul>
                                            <li><a href="{{ route('administration.navigation.aerodromes') }}"><i
                                                            class="mdi mdi-airport me-2"></i>Flugplätze</a></li>
                                            <li><a href="{{ route('administration.navigation.stations') }}"><i
                                                            class="mdi mdi-radio-tower me-2"></i>Stationen</a></li>
                                            <li><a href="{{ route('administration.navigation.charts') }}"><i class="mdi mdi-map me-2"></i>Karten</a>
                                            </li>
                                            <li><a href="javascript:void(0)"><i class="mdi mdi-map-marker me-2"></i>Navaids</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>


                                <li class="sidebar-dropdown">
                                    <a href="javascript:void(0)"><i class="mdi mdi-folder-multiple-image me-2"></i>Contentverwaltung</a>
                                    <div class="sidebar-submenu">
                                        <ul>
                                            <li><a href="{{ route('administration.members') }}"><i
                                                            class="mdi mdi-image me-2"></i>Medienverwaltung</a></li>
                                            <li><a href="{{ route('administration.members') }}"><i class="mdi mdi-xml me-2"></i>URL-Kürzer</a>
                                            </li>
                                            <li><a href="{{ route('administration.members') }}"><i
                                                            class="mdi mdi-account-heart-outline me-2"></i>Partnerverwaltung</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="sidebar-dropdown">
                                    <a href="javascript:void(0)"><i class="mdi mdi-account-edit me-2"></i>Dienste</a>
                                    <div class="sidebar-submenu">
                                        <ul>
                                            <li><a href="javascript:void(0)"><i class="mdi mdi-gitlab me-2"></i>GitLab</a></li>
                                            <li><a href="javascript:void(0)"><i class="mdi mdi-school me-2"></i>Moodle</a></li>
                                            <li><a href="javascript:void(0)"><i class="mdi mdi-book-open-variant me-2"></i>Wiki</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="sidebar-dropdown">
                                    <a href="javascript:void(0)"><i class="mdi mdi-database-alert me-2"></i>Systemadministration</a>
                                    <div class="sidebar-submenu">
                                        <ul>
                                            <li><a href="{{ route('administration.members') }}"><i
                                                            class="mdi mdi-folder-text me-2"></i>Systemlogs</a></li>
                                            <li><a href="{{ route('administration.members') }}"><i
                                                            class="mdi mdi-database-alert me-2"></i>Scheduled
                                                    Updated</a></li>
                                            <li><a href="{{ route('administration.members') }}"><i class="mdi mdi-cog me-2"></i>Failed Jobs</a>
                                            </li>
                                            <li><a href="{{ route('administration.members') }}"><i
                                                            class="mdi mdi-cog me-2"></i>Systemadministration</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <!-- sidebar-menu  -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 818px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar" style="height: 258px; display: block; transform: translate3d(0px, 0px, 0px);">
            </div>
        </div>
    </div>
    <!-- Sidebar Footer -->
    <ul class="sidebar-footer list-unstyled mb-0">
        <li class="list-inline-item mb-0">
            Version {{ config('app.version') }}
        </li>
    </ul>
    <!-- Sidebar Footer -->
</nav>
