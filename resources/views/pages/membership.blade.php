@extends('layouts.master')

@section('content')

    <section class="bg-half-170 bg-primary d-table w-100" id="hero-section"
             style="background: url('{{ asset('images/profile/profile_1.png') }}') center center; background-size: cover">
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%);"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--ed container-->
    </section>



    <section class="section mt-60">
        <div class="container mt-lg-3">
            <div class="card public-profile border-0 rounded shadow mb-3" style="z-index: 1;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-9">
                            <div class="row align-items-end">
                                <div class="col-md-7 text-md-start text-center mt-4 mt-sm-0">
                                    <h3 class="title mb-0">@yield('section-title', $user->username)</h3>
                                    <small class="text-muted h6 me-2">@yield('section-subtitle', $user->id)</small>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <div class="row">
                <div class="col-log-auto col-lg-4 col-12 mb-4">
                    <div class="sidebar sticky-bar p-4 rounded shadow">
                        <div class="widget pb-4 border-bottom">
                            <h5 class="widget-title">Ratings:</h5>
                            <div class="row mt-4">
                                <div class="col-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-headphones fea icon-ex-md text-primary mb-1">
                                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                        <path
                                                d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z">
                                        </path>
                                    </svg>
                                    <h5 class="mb-0">{{ $user->vatsimDetails->rating_atc_short }}</h5>
                                    <p class="text-muted mb-0">@lang('profile.profile.atc-rating-text')</p>
                                </div>
                                <!--end col-->

                                <div class="col-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-mic fea icon-ex-md text-primary mb-1">
                                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                                        <line x1="12" y1="19" x2="12" y2="23"></line>
                                        <line x1="8" y1="23" x2="16" y2="23"></line>
                                    </svg>
                                    <h5 class="mb-0">{{ $user->vatsimDetails->rating_pilot_short }}</h5>
                                    <p class="text-muted mb-0">@lang('profile.profile.pilot-rating-text')</p>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>

                        <div class="widget mt-4">
                            <ul class="nav nav-pills nav-justified flex-column bg-white p-3 mb-0" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link rounded active" id="profile" data-bs-toggle="pill" href="#profile-tab" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">@lang('profile.profile.menu.profile-text')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item mt-2 pb-2 border-bottom">
                                    <a class="nav-link rounded" id="notification" data-bs-toggle="pill" href="#notification-tab" role="tab"
                                       aria-controls="settings" aria-selected="false">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">@lang('profile.profile.menu.notification-text')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item mt-2">
                                    <a class="nav-link rounded" id="settings" data-bs-toggle="pill" href="#settings-tab" role="tab"
                                       aria-controls="settings" aria-selected="false">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">@lang('profile.profile.menu.settings-text')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item mt-2">
                                    <a class="nav-link rounded" id="teamspeak" data-bs-toggle="pill" href="#teamspeak-tab" role="tab"
                                       aria-controls="teamspeak" aria-selected="false">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">@lang('profile.profile.menu.teamspeak-text')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item mt-2 pb-2 border-bottom">
                                    <a class="nav-link rounded" id="feedback" data-bs-toggle="pill" href="#feedback-tab" role="tab"
                                       aria-controls="feedback" aria-selected="false">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">@lang('profile.profile.menu.feedback-text')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item mt-2">
                                    <a href="{{ route('vatsim.authentication.connect.logout') }}" class="nav-link rounded" aria-selected="false">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0 text-danger">@lang('navigation.user.logout')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-8 col-12 tab-content">
                    <div class="tab-content" id="pills-tabContent">
                        @component('components.profile.profile', ['user' => $user])
                        @endcomponent
                        {{--
                        @include('homepage.members.profile.partials.profile')

                        @include('homepage.members.profile.partials.notification')

                        @include('homepage.members.profile.partials.settings')

                        @include('homepage.members.profile.partials.teamspeak')

                        @include('homepage.members.profile.partials.feedback')
                        --}}
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
@endsection
