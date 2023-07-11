@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $user->username }}</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize">Mitgliederverwaltung</li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $user->id }}</li>
                    </ul>
                </nav>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="bg-primary card border-0 shadow rounded overflow-hidden p-4"
                        style="background: url('{{ asset('images/profile/profile_1.png') }}'); background-position: center; background-size: cover;">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-8">
                                <div class="text-center bg-white p-4 rounded">
                                    <img src="{{ asset('/images/profile/avatar_placeholder.png') }}" class="rounded-circle shadow avatar avatar-md-md"
                                        alt="">
                                    <h5 class="mt-3 mb-0">{{ $user->username }}</h5>
                                    <small class="text-muted">{{ $user->id }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-lg-8 col-md-12 mt-4 order-2">
                    <div class="card border-0 shadow rounded p-4">
                        <div class="col-lg-12">
                            <ul class="nav nav-pills nav-justified flex-column flex-sm-row" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-cloud-tab" data-bs-toggle="pill" href="#activity-pill" role="tab"
                                        aria-controls="pills-cloud" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Aktivität</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-smart-tab" data-bs-toggle="pill" href="#notes-pill" role="tab"
                                        aria-controls="pills-smart" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Notizen</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#teamspeak-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">TeamSpeak</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#forum-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Forum</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#bans-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Sperren</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#danger-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Gefahrenzone</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                            </ul>
                            <!--end nav pills-->
                        </div>
                        <!--end col-->

                        <div class="row pt-3">
                            <div class="col-12">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="activity-pill" role="tabpanel" aria-labelledby="activity-pill">
                                        <p class="text-muted mb-0">You can combine all the Landrick templates into a single one, you can take a
                                            component from the Application theme and use it in the Website.</p>
                                    </div>
                                    <!--end teb pane-->

                                    <div class="tab-pane fade" id="notes-pill" role="tabpanel" aria-labelledby="notes-pill">
                                        @include('administration.membership.membership.partials.membershipnotes')
                                    </div>
                                    <!--end teb pane-->

                                    <div class="tab-pane fade" id="teamspeak-pill" role="tabpanel" aria-labelledby="teamspeak-pill">
                                        @include('administration.membership.membership.partials.teamspeak')
                                    </div>
                                    <!--end teb pane-->

                                    <div class="tab-pane fade" id="forum-pill" role="tabpanel" aria-labelledby="forum-pill">
                                        <p class="text-muted mb-0">You can combine all the Landrick templates into a single one, you can take a
                                            component from the Application theme and use it in the Website.</p>
                                    </div>
                                    <!--end teb pane-->

                                    <div class="tab-pane fade" id="bans-pill" role="tabpanel" aria-labelledby="bans-pill">
                                        <p class="text-muted mb-0">You can combine all the Landrick templates into a single one, you can take a
                                            component from the Application theme and use it in the Website.</p>
                                    </div>
                                    <!--end teb pane-->

                                    <div class="tab-pane fade" id="danger-pill" role="tabpanel" aria-labelledby="danger-pill">
                                        @include('administration.membership.membership.partials.dangerzone')
                                    </div>
                                    <!--end teb pane-->
                                </div>
                                <!--end tab content-->
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-4 col-md-12 mt-4 order-1">
                    <div class="card border-0 rounded shadow p-4">
                        <h5 class="mb-0">Persönliche Daten:</h5>
                        <div class="mt-4">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-mail fea icon-ex-md text-muted me-3">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-0">Email:</h6>
                                    <a href="javascript:void(0)" class="text-muted">{{ $user->email }}</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-book-open fea icon-ex-md text-muted me-3">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-0">Ausbildung:</h6>
                                    <a href="javascript:void(0)" class="text-muted">{{ $user->userData->getAtcRatingShortAttribute() }} |
                                        {{ $user->userData->getPilotRatingShortAttribute() }}</a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mt-3 pt-3 border-top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-globe fea icon-ex-md text-muted me-3">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-0">Regionszuweisung:</h6>
                                    <a href="javascript:void(0)"
                                        class="text-muted">{{ $user->userData->region_name . ' (' . $user->userData->region_code . ')' }}</a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-globe fea icon-ex-md text-muted me-3">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-0">Divisionszuordnung:</h6>
                                    <a href="javascript:void(0)"
                                        class="text-muted">{{ $user->userData->division_name . ' (' . $user->userData->division_code . ')' }}</a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mt-3">
                                @if (strtoupper($user->userData->subdivision_code) == 'GER')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-globe fea icon-ex-md text-muted me-3">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-alert-triangle fea icon-ex-md text-muted me-3">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                @endif
                                <div class="flex-1">
                                    <h6 class="text-primary mb-0">vACC Zuordnung:</h6>
                                    <a href="javascript:void(0)"
                                        class="text-muted">{{ $user->userData->subdivision_name . ' (' . $user->userData->subdivision_code . ')' }}</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card border-0 rounded shadow p-4 mt-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Übersicht:</h5>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>

    <div class="modal fade" id="teamspeak-modal" tabindex="-1" aria-labelledby="LoginForm-title" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Laden...</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">UUID</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="user" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="tsmodal-uuid" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="tsmodal-lastos" class="form-label">Letztes Betriebssystem</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="monitor" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="tsmodal-lastos" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tsmodal-regip" class="form-label">Registrierungs-IP</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="globe" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="tsmodal-regip" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tsmodal-lastip" class="form-label">Letzte IP</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="globe" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="tsmodal-lastip" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tsmodal-lastlogin" class="form-label">Letzter Login</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="tsmodal-lastlogin" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tsmodal-regdate" class="form-label">Registrierungsdatum</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="tsmodal-regdate" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal">Schließen</button>
                    <button type="button" class="btn btn-sm btn-soft-danger">Entfernen</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-link {
            border-radius: 0 !important;
        }
    </style>
@endsection
