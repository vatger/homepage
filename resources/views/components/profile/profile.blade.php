@props(['user'])

<div class="tab-pane fade bg-white p-4 rounded shadow active show" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <div class="pb-4 border-bottom">
        <div class="row">
            <div class="col-md-6">
                <h5>@@lang() VATGER Details:</h5>
                <div class="mt-4">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mail fea icon-ex-md text-muted me-3">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Email:</h6>
                            <a class="text-muted">{{ $user->email_backup ? $user->email_backup : $user->email  }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Region:</h6>
                            <a class="text-muted">{{ $user->vatsimDetails->region_name }}, {{ $user->vatsimDetails->region_code }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Division:</h6>
                            <a class="text-muted">{{ $user->vatsimDetails->division_name }}, {{ $user->vatsimDetails->division_code }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">vACC:</h6>
                            <a class="text-muted">
                                @if (!empty($user->vatsimDetails->subdivision_code))
                                    {{ $user->vatsimDetails->subdivision_name }}, {{ $user->vatsimDetails->subdivision_code }}
                                @else
                                    -
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-calendar fea icon-ex-md text-muted me-3">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.profile.registered-on'):</h6>
                            <a class="text-muted mb-0">
                                @if (!empty($user->vatgerDetails->registered_at))
                                    {{ $user->vatgerDetails->registered_at->format('d.m.Y') }}
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-radio fea icon-ex-md text-muted me-3">
                            <circle cx="12" cy="12" r="2"></circle>
                            <path
                                    d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                            </path>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.atc-rating-text')-Rating:</h6>
                            <a class="text-muted mb-0">{{ $user->vatsimDetails->rating_atc_long }},
                                {{ $user->vatsimDetails->rating_atc_short }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mic fea icon-ex-md text-muted me-3">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.pilot-rating-text')-Rating:</h6>
                            <a class="text-muted mb-0">{{ $user->vatsimDetails->rating_pilot_long }},
                                {{ $user->vatsimDetails->rating_pilot_short }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

            <div class="col-md-6">
                <h5>@@lang() VATSIM Details:</h5>
                <div class="mt-4">
                    <div class="d-flex align-items-center">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mail fea icon-ex-md text-muted me-3">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Email:</h6>
                            <a class="text-muted">{{ $user->email }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Region:</h6>
                            <a class="text-muted">{{ $user->vatsimDetails->region_name }}, {{ $user->vatsimDetails->region_code }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Division:</h6>
                            <a class="text-muted">{{ $user->vatsimDetails->division_name }}, {{ $user->vatsimDetails->division_code }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">vACC:</h6>
                            <a class="text-muted">
                                @if (!empty($user->vatsimDetails->subdivision_code))
                                    {{ $user->vatsimDetails->subdivision_name }}, {{ $user->vatsimDetails->subdivision_code }}
                                @else
                                    -
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-calendar fea icon-ex-md text-muted me-3">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.profile.registered-on'):</h6>
                            <a class="text-muted mb-0">
                                @if (!empty($user->vatgerDetails->registered_at))
                                    {{ $user->vatgerDetails->registered_at->format('d.m.Y') }}
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-radio fea icon-ex-md text-muted me-3">
                            <circle cx="12" cy="12" r="2"></circle>
                            <path
                                    d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                            </path>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.atc-rating-text')-Rating:</h6>
                            <a class="text-muted mb-0">{{ $user->vatsimDetails->rating_atc_long }},
                                {{ $user->vatsimDetails->rating_atc_short }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mic fea icon-ex-md text-muted me-3">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.pilot-rating-text')-Rating:</h6>
                            <a class="text-muted mb-0">{{ $user->vatsimDetails->rating_pilot_long }},
                                {{ $user->vatsimDetails->rating_pilot_short }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
