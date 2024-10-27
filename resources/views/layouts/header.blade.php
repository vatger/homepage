<header id="topnav" class="defaultscroll sticky">
    <div class="container">
        <!-- Logo container-->
        <a class="logo" href="{{ route('landing') }}">
            <span class="logo-light-mode">
                <img src="{{ iasset('images/vacc_logo.png', 170*2) }}" class="l-dark" height="32px">
                <img src="{{ iasset('images/vacc_logo_white.png', 170*2) }}" class="l-light" height="32px">
            </span>
            <img src="{{ iasset('images/vacc_logo_white.png', 170*2) }}" class="logo-dark-mode" alt="">
        </a>

        <!-- End Logo container-->
        <div class="menu-extras">
            <div class="menu-item">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </div>
        </div>

        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu nav-light nav-right">
                @switch(Session::get('language', 'en'))
                    @case('en')
                        <li>
                            <a href="{{ route('language.change', ['lang' => 'de']) }}" class="sub-menu-item">
                                <img src="{{ asset('images/germany.svg') }}" height="25px" style="margin-top:-3px" alt="DE">
                            </a>
                        </li>
                        @break
                    @case('de')
                    @default
                        <li>
                            <a href="{{ route('language.change', ['lang' => 'en']) }}" class="sub-menu-item">
                                <img src="{{ asset('images/united-kingdom.svg') }}" height="25px" style="margin-top:-3px" alt="ENG">
                            </a>
                        </li>
                @endswitch

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.piloten.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{ route('redirect.knowledgebase.start-pilot') }}" class="sub-menu-item" target="_blank">@lang('navigation.piloten.erste-schritte')</a></li>
                        <li><a href="{{ route('redirect.knowledgebase.training-pilot') }}" class="sub-menu-item" target="_blank">@lang('navigation.piloten.training')</a></li>
                        <li><a href="{{ route('pilots.aerodromes.viewall') }}" class="sub-menu-item">@lang('navigation.piloten.flugplaetze')</a></li>
                        <li><a href="{{ route('redirect.vatger-tours') }}" class="sub-menu-item">VATGER Touren</a></li>
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.lotsen.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{ route('redirect.knowledgebase.start-atc') }}" target="_blank" class="sub-menu-item">@lang('navigation.lotsen.erste-schritte')</a></li>
                        <li><a href="{{ route('controllers.booking') }}" class="sub-menu-item">@lang('navigation.user.booking')</a></li>
                        <li><a href="{{ route('redirect.training-center') }}" target="_blank" class=" sub-menu-item">ATC Training</a></li>
                        <li><a href="{{ route("redirect.sectorfiles") }}" target="_blank" class="sub-menu-item">Sectorfiles</a></li>
                        <li><a href="{{ route("controllers.restricted") }}" class="sub-menu-item">Restricted Stations</a></li>
                        <li><a href="{{ route("controllers.s1") }}" class="sub-menu-item">S1 Tower</a></li>
                        <li><a href="{{ route("controllers.required-courses") }}" class="sub-menu-item">Required courses</a></li>
                        <li><a href="{{ route("redirect.support.feedback") }}" target="_blank" class="sub-menu-item">@lang('navigation.lotsen.feedback')</a></li>
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.community.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{ route('redirect.ts3') }}" class="sub-menu-item">@lang('navigation.community.teamspeak')</a></li>
                        <li><a href="{{ route('redirect.board') }}" target="_blank" class="sub-menu-item">@lang('navigation.community.forum')</a></li>
                        <li><a href="{{ route('redirect.discord') }}" target="_blank" class="sub-menu-item">@lang('navigation.community.discord')</a></li>
                        <li><a href="{{ route('redirect.knowledgebase') }}" target="_blank" class="sub-menu-item">@lang('navigation.community.wiki')</a></li>
                        <li><a href="{{ route('redirect.moodle') }}" target="_blank" class="sub-menu-item">@lang('navigation.community.moodle')</a></li>
                        <li><a href="{{ route('redirect.spreadshop') }}" target="_blank" class="sub-menu-item">@lang('navigation.community.fan-shop')</a></li>
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.hilfe.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{ route('redirect.support') }}" class="sub-menu-item">@lang('navigation.hilfe.support')</a></li>
                        <li><a href="{{ route('redirect.knowledgebase.contact') }}" target="_blank" class="sub-menu-item">@lang('navigation.hilfe.personal')</a></li>
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    @auth
                        <a href="javascript:void(0)">{{ Auth::user()->firstname }}</a><span class="menu-arrow"></span>
                        <ul class="submenu">
                            <li><a href="{{ route('member.profile') }}" class="sub-menu-item">@lang('navigation.user.profile')</a></li>
                            @can('administration.access')
                                <li><a href="{{ route('administration.dashboard') }}" class="sub-menu-item">@lang('navigation.user.administration')</a></li>
                            @endcan
                            <li><a href="{{ route('vatsim.authentication.connect.logout') }}" class="sub-menu-item"
                                   style="color: #e43f52 !important;">@lang('navigation.user.logout')</a></li>
                        </ul>
                    @else
                        <a href="{{ route('vatsim.authentication.connect.login') }}">@lang('navigation.user.login')</a>
                    @endauth
                </li>
                @auth
                    <li class="parent-menu-item">
                        <a href="{{ route('member.profile') }}?tab=notifications">
                            <span class="">
                                @if (count(Auth::user()->unreadNotifications) > 0)
                                    <i class="fea fea-icon" data-feather="bell"></i><span> {{ count(Auth::user()->unreadNotifications) }} </span>
                                @endif
                            </span>
                        </a>
                    </li>
                @endauth
            </ul>
            <!--end navigation menu-->
        </div>
        <!--end navigation-->
    </div>
    <!--end container-->
</header>
<!--end header-->
<!-- Navbar End -->
