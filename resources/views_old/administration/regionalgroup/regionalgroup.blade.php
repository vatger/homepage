@extends('administration.partials.master')

@section('content')

    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Regionalgroup Administration</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.regionalgroup.index') }}">Regionalgroups</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $regionalgroup->name }}</li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-5 mt-4">

                    @livewire('administration.regionalgroup.details', ['rg_id' => $regionalgroup->id])

                    <div class="card border-0 rounded shadow p-4 mt-4">
                        <h5 class="mb-0">Offene Anfragen:</h5>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="mb-1 table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Von</th>
                                                <th>Datum</th>
                                                <th>Art</th>
                                                <th>Aktion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($regionalgroup->requests->count() != 0)
                                                @foreach ($regionalgroup->requests as $rr)
                                                    <tr class="text-center">
                                                        <td>{{ $rr->account->username }}</td>
                                                        <td>{{ $rr->created_at->format('d.m.Y') }}</td>
                                                        <td>{{ $rr->type == 'guest' ? 'Gast' : 'Vollmitglied' }}</td>
                                                        <td>
                                                            <a
                                                                href="{{ route('administration.regionalgroup.request.view', ['regionalgroup' => $regionalgroup->id, 'regionalgroupRequest' => $rr->id]) }}">
                                                                <button class="btn btn-sm btn-soft-primary">Anzeigen</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center text-muted" colspan="4">Keine Anfragen</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>

                    <div class="card border-0 rounded shadow p-4 mt-4">
                        <h5 class="mb-0">Vorlagen:</h5>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="mb-1 table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Name</th>
                                                <th>Erstellt Am</th>
                                                <th>Aktion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($regionalgroup->templates->count() != 0)
                                                @foreach ($regionalgroup->templates as $tmpl)
                                                    <tr class="text-center">
                                                        <td>{{ $tmpl->name }}</td>
                                                        <td>{{ $tmpl->created_at ? $tmpl->created_at->format('d.m.Y') : '-' }}</td>
                                                        <td>
                                                            <a href="#">
                                                                <button class="btn btn-sm btn-soft-primary">Anzeigen</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center text-muted" colspan="4">Keine Vorlagen</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <button class="btn btn-sm btn-soft-primary">Neue Vorlage</button>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-7 mt-4">
                    <div class="card border-0 shadow rounded p-4">
                        <div class="col-lg-12">
                            <ul class="nav nav-pills nav-justified flex-column flex-sm-row" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-cloud-tab" data-bs-toggle="pill" href="#activity-pill" role="tab"
                                        aria-controls="pills-cloud" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Mitglieder</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-smart-tab" data-bs-toggle="pill" href="#notes-pill" role="tab"
                                        aria-controls="pills-smart" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Gäste</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#teamspeak-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Mentoren</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#forum-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Navigatoren</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#bans-pill" role="tab"
                                        aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Eventteam</h6>
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
                                        @livewire('administration.regionalgroup.memberlist', ['rg_id' => $regionalgroup->id])
                                    </div>
                                    <!--end teb pane-->
                                    <div class="tab-pane fade" id="notes-pill" role="tabpanel" aria-labelledby="notes-pill">
                                        @livewire('administration.regionalgroup.guestlist', ['rg_id' => $regionalgroup->id])
                                    </div>
                                    <!--end teb pane-->
                                    <div class="tab-pane fade" id="teamspeak-pill" role="tabpanel" aria-labelledby="teamspeak-pill">
                                        @livewire('administration.regionalgroup.mentorlist', ['rg_id' => $regionalgroup->id])
                                    </div>
                                    <!--end teb pane-->
                                    <div class="tab-pane fade" id="forum-pill" role="tabpanel" aria-labelledby="forum-pill">
                                        @livewire('administration.regionalgroup.navigatorlist', ['rg_id' => $regionalgroup->id])
                                    </div>
                                    <!--end teb pane-->
                                    <div class="tab-pane fade" id="bans-pill" role="tabpanel" aria-labelledby="bans-pill">
                                        @livewire('administration.regionalgroup.eventlerlist', ['rg_id' => $regionalgroup->id])
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
            </div>
        </div>
    </div>

@endsection
