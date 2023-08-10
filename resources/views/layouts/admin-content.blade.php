<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
</div>
<!-- Loader -->

<div class="page-wrapper toggled">
    @include('layouts.admin-navigation')

    <!-- Start Page Content -->
    <main class="page-content bg-light">

        <div class="top-header">
            <div class="header-bar d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="#" class="logo-icon me-3">
                        <img src="{{ asset('images/vacc_logo.png') }}" height="30" class="small" alt="">
                        <span class="big">
                                <img src="{{ asset('images/vacc_logo.png') }}" height="24" class="logo-light-mode" alt="">
                                <img src="{{ asset('images/vacc_logo_white.png') }}" height="24" class="logo-dark-mode" alt="">
                            </span>
                    </a>
                    <a id="close-sidebar" class="btn btn-icon btn-soft-light" href="javascript:void(0)">
                        <i data-feather="menu" class="fea"></i>
                    </a>
                </div>

                <ul class="list-unstyled mb-0">
                    <li class="list-inline-item mb-0 ms-2">
                        {{ Auth::user()->username }}
                    </li>

                    <li class="list-inline-item mb-0 ms-2">
                        <a href="{{ route('member.profile.notifications') }}">
                            <div class="dropdown dropdown-primary">
                                <button type="button" class="btn btn-icon btn-soft-light p-0">
                                    <i data-feather="bell" class="fea"></i>
                                </button>
                                @if(Auth::user()->unreadNotifications()->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                @endif
                                </span>
                            </div>
                        </a>
                    </li>

                    <li class="list-inline-item mb-0 ms-2">
                        <a href="{{ route('landing') }}">
                            <div class="btn btn-icon btn-danger">
                                <i data-feather="log-out" class="fea"></i>
                            </div>
                        </a>
                    </li>

                </ul>
                {{--
                <ul class="list-group list-group-horizontal">
                    <a href="javascript:void(0)"><span class="mdi mdi-bell">
                                @if (count(Auth::user()->unreadNotifications) > 0)
                                <span class="badge badge-warning navbar-badge">{{ count(Auth::user()->unreadNotifications) }}</span>
                        @endif
                    </a>
                    <li class="nav nav-light nav-right">
                        <ul class="navigation-menu nav-light nav-right">
                            <li class="list-inline-item mb-0 ms-1">
                                <div class="dropdown dropdown-primary">
                                    <button type="button" class="btn btn-soft-light dropdown-toggle p-0" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/profile/avatar_placeholder.png') }}"
                                                                                            class="avatar avatar-ex-small rounded" alt=""></button>
                                    <div class="dropdown-menu dd-menu dropdown-menu-end bg-white shadow border-0 mt-3 py-3"
                                         style="min-width: 200px;">
                                        <a class="dropdown-item d-flex align-items-center text-dark pb-3" href="">
                                            <img src="{{ asset('images/profile/avatar_placeholder.png') }}"
                                                 class="avatar avatar-md-sm rounded-circle border shadow" alt="">
                                            <div class="flex-1 ms-2">
                                                <span class="d-block">{{ Auth::user()->username }}</span>
                                                <small class="text-muted">{{ Auth::user()->vatsimDetails->rating_atc_short }}</small>
                                            </div>
                                        </a>
                                        <a class="dropdown-item text-dark" href="{{ route('administration.dashboard') }}"><span
                                                    class="mb-0 d-inline-block me-1"><i class="ti ti-home"></i></span>
                                            Dashboard</a>
                                        <div class="dropdown-divider border-top"></div>
                                        <a class="dropdown-item text-dark" href="{{ route('landing') }}"><span
                                                    class="mb-0 d-inline-block me-1"><i class="ti ti-logout"></i></span>
                                            Exit</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
                --}}
            </div>
        </div>

        @yield('content')
        {{ $slot ?? '' }}

        <footer class="bg-white shadow pt-3" style="padding: 0">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-sm-start text-center">
                            <p class="mb-0 text-muted">
                                © {{ Carbon\Carbon::now()->utc()->format('Y') }} {{ config('app.name') }}.</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end container-->
        </footer>
        <!--end footer-->
        <!-- End -->
    </main>
    <!--End page-content" -->
</div>
<!-- page-wrapper -->
