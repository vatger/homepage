@extends('homepage.partials.master')

@section('section-title')
    {{ $regionalgroup->name }}
@endsection

@section('section-subtitle')
    @if ($regionalgroup->fir)
        {{ $regionalgroup->fir->name }}
    @else
        -
        @endif @if ($_user->isMemberOfRegionalgroup($regionalgroup))
            | @lang('general.vatger.full-member')
            @endif @if ($_user->isGuestOfRegionalgroup($regionalgroup))
                | @lang('general.vatger.guest-member')
            @endif
        @endsection

        @section('content')
            @include('homepage.members.profile.partials.hero')

            <section class="section mt-60">
                <div class="container mt-lg-3">
                    <div class="row">
                        <div class="col-log-auto col-lg-4 col-12 mb-4">
                            <div class="sidebar sticky-bar p-4 rounded shadow">
                                <div class="widget pb-4 border-bottom">
                                    <h5 class="widget-title">@lang('regionalgroup.stats-title'):</h5>
                                    <div class="row mt-4">
                                        <div class="col-6 text-center">
                                            <h5 class="mb-0">{{ $regionalgroup->membersCount }}</h5>
                                            <p class="text-muted mb-0">@lang('regionalgroup.stats-full-members')</p>
                                        </div>
                                        <!--end col-->

                                        <div class="col-6 text-center">
                                            <h5 class="mb-0">{{ $regionalgroup->guestsCount }}</h5>
                                            <p class="text-muted mb-0">@lang('regionalgroup.stats-guest-members')</p>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>

                                <div class="widget mt-4 pb-1">
                                    <ul class="nav nav-pills nav-justified flex-column bg-white p-3 mb-0" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link rounded active" id="news" data-bs-toggle="pill" href="#news-tab" role="tab"
                                                aria-controls="news" aria-selected="true">
                                                <div class="text-start py-1 px-2">
                                                    <h6 class="mb-0">@lang('regionalgroup.regionalgroup.news-text')</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->

                                        <li class="nav-item mt-2">
                                            <a class="nav-link rounded" id="settings" data-bs-toggle="pill" href="#contact-tab" role="tab"
                                                aria-controls="contact" aria-selected="false">
                                                <div class="text-start py-1 px-2">
                                                    <h6 class="mb-0">@lang('regionalgroup.regionalgroup.contact-text')</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->

                                        <li class="nav-item mt-2">
                                            <a class="nav-link rounded" id="teamspeak" data-bs-toggle="pill" href="#mentoring-tab" role="tab"
                                                aria-controls="mentoring" aria-selected="false">
                                                <div class="text-start py-1 px-2">
                                                    <h6 class="mb-0">@lang('regionalgroup.regionalgroup.mentoring-staff-text')</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->

                                        <li class="nav-item mt-2">
                                            <a class="nav-link rounded" id="feedback" data-bs-toggle="pill" href="#navigation-tab" role="tab"
                                                aria-controls="navigation" aria-selected="false">
                                                <div class="text-start py-1 px-2">
                                                    <h6 class="mb-0">@lang('regionalgroup.regionalgroup.nav-staff-text')</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->

                                        <li class="nav-item mt-2">
                                            <a class="nav-link rounded" id="feedback" data-bs-toggle="pill" href="#event-tab" role="tab"
                                                aria-controls="event" aria-selected="false">
                                                <div class="text-start py-1 px-2">
                                                    <h6 class="mb-0">@lang('regionalgroup.regionalgroup.event-staff-text')</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->

                                        @if ($_user->isMemberOfRegionalgroup($regionalgroup) || $_user->isGuestOfRegionalgroup($regionalgroup))
                                            <li class="nav-item mt-3 pt-3 border-top">
                                                <a class="nav-link rounded" id="apply" data-bs-toggle="pill" href="#apply-tab" role="tab"
                                                    aria-controls="apply" aria-selected="false">
                                                    <div class="text-start py-1 px-2">
                                                        <h6 class="mb-0">@lang('regionalgroup.regionalgroup.membership-text')</h6>
                                                    </div>
                                                </a>
                                                <!--end nav link-->
                                            </li>
                                            <!--end nav item-->
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end col-->

                        <div class="col-lg-8 col-12 tab-content">
                            <div class="tab-content" id="pills-tabContent">
                                @include('homepage.members.regionalgroup.partials.news')

                                @include('homepage.members.regionalgroup.partials.contact')

                                @include('homepage.members.regionalgroup.partials.mentors')

                                @include('homepage.members.regionalgroup.partials.navigators')

                                @include('homepage.members.regionalgroup.partials.eventler')

                                @include('homepage.members.regionalgroup.partials.membership')
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end container-->
            </section>

            <style>
                th {
                    width: 33%;
                }

                .tox-notifications-container {
                    display: none !important;
                }
            </style>
        @endsection
