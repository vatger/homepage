<header id="topnav" class="defaultscroll sticky">
    <div class="container">
        <!-- Logo container-->
        <a class="logo" href="{{ route('landing') }}">
            <span class="logo-light-mode">
                <img src="{{ asset('images/vacc_logo.png') }}" class="l-dark" height="32" alt="">
                <img src="{{ asset('images/vacc_logo_white.png') }}" class="l-light" height="32" alt="">
            </span>
            <img src="{{ asset('images/vacc_logo_white.png') }}" height="32" class="logo-dark-mode" alt="">
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
                @if (Session::has('language'))
                    @switch(Session::get('language'))
                        @case('de')
                            <li><a href="{{ route('language.change', ['lang' => 'en']) }}" class="sub-menu-item"><img
                                            src="{{ asset('images/united-kingdom.svg') }}" height="25px" style="margin-top:-3px"></a></li>
                            @break

                        @case('en')
                            <li><a href="{{ route('language.change', ['lang' => 'de']) }}" class="sub-menu-item"><img src="{{ asset('images/germany.svg') }}"
                                                                                                                      height="25px" style="margin-top:-3px"></a></li>
                            @break

                        @default
                            <li><a href="{{ route('language.change', ['lang' => 'en']) }}" class="sub-menu-item"><img
                                            src="{{ asset('images/united-kingdom.svg') }}" height="25px" style="margin-top:-3px"></a></li>
                    @endswitch
                @else
                    <li><a href="{{ route('language.change', ['lang' => 'de']) }}" class="sub-menu-item"><img
                                    src="{{ asset('images/germany.svg') }}" height="25px" style="margin-top:-3px"></a>
                    </li>
                @endif

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.piloten.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{ route('getting-started.pilot') }}" class="sub-menu-item">@lang('navigation.piloten.erste-schritte')</a>
                        </li>
                        <li><a href="javascript:void(0)" class="sub-menu-item">@lang('navigation.piloten.training')</a>
                        </li>
                        <li><a href="{{ route('pilots.aerodromes.viewall') }}" class="sub-menu-item">@lang('navigation.piloten.flugplaetze')</a></li>
                        <li><a href="https://tours.vatger.de" class="sub-menu-item">@lang('navigation.piloten.eventroutes')</a></li>
                        <li><a href="{{ route('pilots.livemap') }}" class="sub-menu-item">Network Livemap</a></li>
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.lotsen.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{ route('getting-started.atc') }}" class="sub-menu-item">@lang('navigation.lotsen.erste-schritte')</a>
                        </li>
                        <li><a href="{{ route('controllers.feedback') }}" class="sub-menu-item">@lang('navigation.lotsen.feedback')</a>
                        </li>
                        <li><a href="javascript:void(0)" class="sub-menu-item">@lang('navigation.lotsen.gastlotsen')</a>
                        </li>
                        <li><a href="javascript:void(0)" class="sub-menu-item">@lang('navigation.lotsen.dokumente')</a>
                        </li>
                        <li><a href="javascript:void(0)" class="sub-menu-item">@lang('navigation.lotsen.solos')</a></li>
                        @auth
                            @if (Route::has('atciss.home'))
                                <li><a href="{{ route('atciss.home') }}" class="sub-menu-item">@lang('navigation.lotsen.atciss')</a></li>
                            @endif
                            <li><a href="{{ route('euroscope.scenarios.index') }}" class="sub-menu-item">EuroScope
                                    Scenarios</a></li>
                            <li><a href="{{ route('euroscope.sectorfile.index') }}" class="sub-menu-item">EuroScope
                                    Sectorfile</a></li>
                        @endauth
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.community.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="ts3server://ts3.vatsim-germany.org" class="sub-menu-item">@lang('navigation.community.teamspeak')</a>
                        </li>
                        <li><a href="https://board.vatsim-germany.org" class="sub-menu-item">@lang('navigation.community.forum')</a></li>
                        <li><a href="https://knowledgebase.vatsim-germany.org/" class="sub-menu-item">@lang('navigation.community.wiki')</a></li>
                        <li><a href="https://vatsim-germany.myspreadshop.de/" target="_blank" class="sub-menu-item">@lang('navigation.community.fan-shop')</a>
                        </li>
                        @if (Route::has('stats.landing'))
                            <li><a href="{{ route('stats.landing') }}" class="sub-menu-item">@lang('navigation.community.stats')</a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    <a href="javascript:void(0)">@lang('navigation.hilfe.titel')</a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="https://support.vatsim-germany.org/" class="sub-menu-item">@lang('navigation.hilfe.support')</a>
                        </li>
                        @if (Session::has('language') && Session::get('language') == 'de')
                            <li><a href="https://knowledgebase.vatsim-germany.org/books/contact/page/contact-vatsim-germany"
                                   class="sub-menu-item">@lang('navigation.hilfe.personal')</a></li>
                        @else
                            <li><a href="https://knowledgebase.vatsim-germany.org/books/contact/page/contact-vatsim-germany" class="sub-menu-item">@lang('navigation.hilfe.personal')</a>
                            </li>
                        @endif

                        <li><a href="{{ route('help.faq') }}" class="sub-menu-item">FAQ</a></li>
                    </ul>
                </li>

                <li class="has-submenu parent-menu-item">
                    @auth
                        <a href="javascript:void(0)">{{ Auth::user()->firstname }}</a><span class="menu-arrow"></span>
                        <ul class="submenu">
                            <li><a href="{{ route('member.profile') }}" class="sub-menu-item">@lang('navigation.user.profile')</a></li>
                            <li><a href="{{ route('controllers.booking.index') }}" class="sub-menu-item">@lang('navigation.user.booking')</a></li>
                            <li>
                                <a href="{{ config('app.forcehttps') ? 'https://' : 'http://' . 'training.' . str_ireplace('www.', '', parse_url(url('/'), PHP_URL_HOST)) }}"
                                   class="sub-menu-item">ATC Training</a>
                            </li>
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
                                    <i class="" data-feather="bell"></i><span> {{ count(Auth::user()->unreadNotifications) }} </span>
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
