@extends('homepage.general.firststeps.partial.hero')

@section('title')
    @lang('first-steps.introduction.title')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">@lang('first-steps.introduction.breadcrumb')</li>
@endsection

@section('links')
    <div class="d-flex align-items-center mt-3">
        <div class="flex-1">
            <a href="https://board.vatsim-germany.org/" target="_blank">
                <button type="button" class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">VATGER Forum <i data-feather="external-link"
                        class="fea icon-sm" style="margin-left: 10px; margin-top:-4px"></i></button>
            </a>
        </div>
    </div>

    <div class="d-flex align-items-center mt-3">
        <div class="flex-1">
            <button class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">Newbieday</button>
        </div>
    </div>

    <div class="d-flex align-items-center mt-3">
        <div class="flex-1">
            <button class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">@lang('general.faq')</button>
        </div>
    </div>
@endsection

@section('blog-content')
    <h5 class="mt-2">@lang('first-steps.introduction.text-title')</h5>
    <p class="text-muted mt-3 pb-4 border-bottom">
        @lang('first-steps.introduction.text-content.0')
    </p>

    <!-- Getting Started Section Start -->
    <h5 class="mt-4">Getting Started</h5>
    <div class="accordion mt-2 pt-2" id="general-section">
        <div class="accordion-item rounded shadow bg-white mt-2">
            <h2 class="accordion-header" id="">
                <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#getstarted-collapse" aria-expanded="false" aria-controls="getstarted-collapse">
                    @lang('general.phrases.read-more')
                </button>
            </h2>
            <div id="getstarted-collapse" class="accordion-collapse border-0 collapse" aria-labelledby="getstarted-collapse"
                data-bs-parent="#general-section" style="">
                <div class="accordion-body text-muted">
                    <div class="text-center">
                        <ul class="nav nav-pills rounded-pill justify-content-center d-inline-block border py-1 px-2" id="pills-tab" role="tablist">
                            <li class="nav-item d-inline-block">
                                <a class="nav-link px-3 rounded-pill monthly active" id="Pilot" data-bs-toggle="pill" href="#pilot" role="tab"
                                    aria-controls="Month" aria-selected="true">@lang('first-steps.introduction.role-select.pilot-tab')</a>
                            </li>
                            <li class="nav-item d-inline-block">
                                <a class="nav-link px-3 rounded-pill yearly" id="Atc" data-bs-toggle="pill" href="#atc" role="tab"
                                    aria-controls="Year" aria-selected="false">@lang('first-steps.introduction.role-select.atco-tab')</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade active show" id="pilot" role="tabpanel" aria-labelledby="Pilot">
                            <div class="row">
                                <h5 class="mt-2">@lang('first-steps.introduction.role-select.pilot.how-to-become')</h5>
                                <p class="text-muted mt-1">
                                    @lang('first-steps.introduction.role-select.pilot.how-to-become-content.0')
                                </p>

                                <button class="btn btn-soft-primary w-100">@lang('first-steps.introduction.role-select.pilot.button-content')</button>
                            </div>
                            <!--end row-->
                        </div>

                        <div class="tab-pane fade" id="atc" role="tabpanel" aria-labelledby="Atc">
                            <div class="row">
                                <h5 class="mt-2">@lang('first-steps.introduction.role-select.atco.how-to-become')</h5>
                                <p class="text-muted mt-1">
                                    @lang('first-steps.introduction.role-select.atco.how-to-become-content.0')
                                </p>

                                <h5 class="mt-2">@lang('first-steps.introduction.role-select.atco.previous-knowledge')</h5>
                                <p class="text-muted mt-1">
                                    @lang('first-steps.introduction.role-select.atco.previous-knowledge-content.0')
                                </p>

                                <a href="{{ route('getting-started.atc') }}"><button class="btn btn-soft-primary w-100">@lang('first-steps.introduction.role-select.atco.button-content')</button></a>
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p class="pb-3 border-bottom"></p>
    <!-- Getting Started Section End -->

    <!-- Newbieday Section Start -->
    <h5 class="mt-4">Newbieday</h5>
    <div class="accordion mt-2 pt-2" id="general-section">
        <div class="accordion-item rounded shadow bg-white mt-2">
            <h2 class="accordion-header" id="">
                <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#newbieday-collapse"
                    aria-expanded="false" aria-controls="newbieday-collapse">
                    @lang('general.phrases.read-more')
                </button>
            </h2>
            <div id="newbieday-collapse" class="accordion-collapse border-0 collapse" aria-labelledby="newbieday-collapse"
                data-bs-parent="#general-section" style="">
                <div class="accordion-body text-muted">
                    <p class="text-muted mt-3">
                        @lang('first-steps.introduction.newbieday.text-content.0')
                    </p>
                    <p class="pb-1">
                        <strong>@lang('first-steps.introduction.newbieday.timer-text')</strong>
                        <!--TODO: Only show if ND-Planned-->
                    <div class="rounded p-1 bg-light">
                        <table class="w-100">
                            <tr>
                                <td>
                                    <div class="rounded m-1 p-2 bg-soft-info"><strong>12.01.2022, 18:00z</strong></div>
                                </td>
                                <td><a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" title="All places taken"
                                        alt=""><button type="button" class="btn btn-soft-info" disabled
                                            style="width: 100%">@lang('first-steps.introduction.newbieday.sign-up-button-content')</button></a></td>
                                <!-- TODO: If Newbieday is currently active (ie. signups), then show signup button normally. Else, show disabled button with tooltip-->
                            </tr>
                        </table>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <p class="pb-3 border-bottom"></p>
    <!-- Newbieday Section End -->

    <h5 class="mt-4">@lang('first-steps.introduction.vatger-services.text-title')</h5>
    <div class="accordion mt-2 pt-2" id="general-section">
        <div class="accordion-item rounded shadow bg-white mt-2">
            <h2 class="accordion-header" id="">
                <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#services-collapse" aria-expanded="false" aria-controls="services-collapse">
                    @lang('general.phrases.read-more')
                </button>
            </h2>
            <div id="services-collapse" class="accordion-collapse border-0 collapse" aria-labelledby="services-collapse"
                data-bs-parent="#services-collapse" style="">
                <div class="accordion-body text-muted">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 pt-3">
                            <div class="card explore-feature border-0 rounded text-center bg-white shadow">
                                <div class="card-body">
                                    <div class="icon rounded-circle shadow-lg d-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-book-open">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mt-3 title">Knowledgebase</h5>
                                    <p class="text-muted mb-0">Want to learn more? Head over to our Wiki and find interesting articles all around
                                        aviation.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 pt-3">
                            <div class="card explore-feature border-0 rounded text-center bg-white shadow">
                                <div class="card-body">
                                    <div class="icon rounded-circle shadow-lg d-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-users">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mt-3 title">Community</h5>
                                    <p class="text-muted mb-0">Share your experience with fellow avaitors or aviation enthusiasts on our forums or
                                        join our TeamSpeak.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 pt-3">
                            <div class="card explore-feature border-0 rounded text-center bg-white shadow">
                                <div class="card-body">
                                    <div class="icon rounded-circle shadow-lg d-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-bar-chart-2">
                                            <line x1="18" y1="20" x2="18" y2="10"></line>
                                            <line x1="12" y1="20" x2="12" y2="4"></line>
                                            <line x1="6" y1="20" x2="6" y2="14"></line>
                                        </svg>
                                    </div>
                                    <h5 class="mt-3 title">Statistics-Center</h5>
                                    <p class="text-muted mb-0">Get a glimps at the activity on the network. Or just stalk others.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 pt-3">
                            <div class="card explore-feature border-0 rounded text-center bg-white shadow">
                                <div class="card-body">
                                    <div class="icon rounded-circle shadow-lg d-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-send">
                                            <line x1="22" y1="2" x2="11" y2="13"></line>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                        </svg>
                                    </div>
                                    <h5 class="mt-3 title">Just Fly</h5>
                                    <p class="text-muted mb-0">The main reason for our hobby. We do not make any difference between VFR or IFR,
                                        both are welcome anytime.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 pt-3">
                            <div class="card explore-feature border-0 rounded text-center bg-white shadow">
                                <div class="card-body">
                                    <div class="icon rounded-circle shadow-lg d-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </div>
                                    <h5 class="mt-3 title">Material</h5>
                                    <p class="text-muted mb-0">We offer a lot of helping materials to give newer or non regional members a better
                                        understanding of how to fly in Germany.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 pt-3">
                            <div class="card explore-feature border-0 rounded text-center bg-white shadow">
                                <div class="card-body">
                                    <div class="icon rounded-circle shadow-lg d-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-lock">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </div>
                                    <h5 class="mt-3 title">Dataprotection / Security</h5>
                                    <p class="text-muted mb-0">We are always focused on dataprotection and security for our members. We do only
                                        track data that is actually necessary for the purpose of flying / controlling on the VATSIM network.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
