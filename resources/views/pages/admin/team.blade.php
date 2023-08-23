<div>
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0"></h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize">Mitgliederverwaltung</li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Gruppenverwaltung</li>
                    </ul>
                </nav>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="bg-primary card border-0 shadow rounded overflow-hidden p-4"
                         style="background: url('{{ asset('theme/images/profile/profile_1.png') }}') center;">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-8">
                                <div class="text-center bg-white p-4 rounded">
                                    <img src="{{ asset('/images/profile/avatar_placeholder.png') }}" class="rounded-circle shadow avatar avatar-md-md" alt="">
                                    <h5 class="mt-3 mb-0">{{ $team->name }}</h5>
                                    <small class="text-muted">{{ 'Erstellt am: ' . $team->created_at->format('d.m.Y') }}</small>
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
                    <div class="card border-0 shadow rounded px-4 pb-4 pt-2">
                        <div class="row p-4 border-bottom">
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <div class="d-flex align-items-center">
                                        <div class="icon text-center rounded-pill">
                                            <i data-feather="users" class="fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Mitglieder</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0">{{ $team->role->users->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
                                <li class="list-inline-item" style="width: 100%">
                                    <div class="row">
                                        <input wire:model="user_id" type="number" class="form-control-sm form-control float-end mb-1" placeholder="CID">
                                        <button wire:click="addUser()" class="btn btn-sm btn-soft-primary float-end">Benutzer Hinzufügen</button>
                                    </div>
                                </li>
                            </div>
                        </div>

                        <div class="row pt-4 ps-4 table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th class="border-bottom" style="width: 33%">CID</th>
                                    <th class="border-bottom" style="width: 33%">Name</th>
                                    <th class="border-bottom" style="width: 33%">Aktion</th>
                                </tr>
                                </thead>
                                <tbody id="member-list-content">
                                @if ($team->role->users->count() == 0)
                                    <tr class="text-center">
                                        <td colspan="3" class="text-muted text-center">Keine Benutzer in dieser Gruppe</td>
                                    </tr>
                                @else
                                    @foreach ($team->role->users as $u)
                                        <tr class="text-center" id="user-{{ $u->id }}">
                                            <td>{{ $u->id }}</td>
                                            <td>{{ $u->username }}</td>
                                            <td>
                                                <button wire:click="removeUser({{$u->id}})" class="btn btn-sm btn-soft-danger">Entfernen
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-4 col-md-12 mt-4 order-1">
                    <div class="card border-0 rounded shadow p-4">
                        <h5 class="mb-0">Übersicht:</h5>
                        <div class="mt-4">
                            <div class="d-flex align-items-center">
                                <i data-feather="users" class="fea icon-ex-md text-muted me-3"></i>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-2">Benutzer:</h6>
                                    <p class="text-muted">{{ $team->role->users->count() }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <i data-feather="lock" class="fea icon-ex-md text-muted me-3"></i>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-2">Berechtigungen:</h6>
                                    <p class="text-muted">{{ $team->role->permissions->count() }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <i data-feather="arrow-up" class="fea icon-ex-md text-muted me-3"></i>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-2">Übergeordnetes Team:</h6>
                                    @if($team->super_team)
                                        <a href="{{ route('administration.team', ['team' => $team->super_team]) }}" class="text-muted">
                                            {{ $team->super_team->name }}
                                        </a>
                                    @else
                                        <p class="text-muted">kein übergeordnetes Team</p>
                                    @endif

                                    <select wire:model.live="selected_superteam" class="form-select form-control mt-2" aria-label="Übergeordnetes Team">
                                        <option value="-1" @if($selected_superteam == -1) selected @endif>kein übergeordnetes Team</option>
                                        @foreach(App\Models\Groups\Team::all() as $t)
                                            <option value="{{$t->id}}" @if($selected_superteam == $t->id) selected @endif>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <i data-feather="arrow-down" class="fea icon-ex-md text-muted me-3"></i>
                                <div class="flex-1">
                                    <h6 class="text-primary mb-2">Untergeordnete Teams:</h6>
                                    @forelse($subteams as $t)
                                        <p>
                                            <a href="{{ route('administration.team', ['team' => $t]) }}" class="text-muted">
                                                {{ $t->name }}
                                            </a>
                                        </p>
                                    @empty
                                        <p class="text-muted">keine untergeordnete Teams</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="d-flex align-items-center pt-3 mt-3 border-top">
                                <button class="btn btn-sm btn-soft-danger" data-bs-toggle="modal" data-bs-target="#deleteGroupModal">Gruppe
                                    Löschen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            @can('membership.teams.edit.permissions')
                <div class="row">
                    <div class="col-lg-8 col-md-12 mt-4 order-2">
                        <div class="card border-0 shadow rounded p-4">
                            <div class="row p-4 border-bottom">
                                <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                    <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                        <div class="d-flex align-items-center">
                                            <div class="icon text-center rounded-pill">
                                                <i data-feather="lock" class="fs-4 mb-0"></i>
                                            </div>
                                            <div class="flex-1 ms-3">
                                                <h6 class="mb-0 text-muted">Berechtigungen</h6>
                                                <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $team->role->permissions->count() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-4 ps-4 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Berechtigung</th>
                                        <th style="width: 25% !important;">Aktion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($permissions as $p)
                                        <tr class="text-center">
                                            <td>{{ $p->id }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>
                                                @if ($team->role->hasPermissionTo($p))
                                                    <button wire:click="changePermission({{$p->id}}, false)" class="btn btn-sm btn-soft-danger">
                                                        Entfernen
                                                    </button>
                                                @else
                                                    <button wire:click="changePermission({{$p->id}}, true)" class="btn btn-sm btn-soft-success">
                                                        Hinzufügen
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--end col-->

                    <div class="col-lg-4 col-md-12 mt-4 order-1">

                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
        </div>
    </div>
    @endcan

    <div class="modal fade" id="deleteGroupModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-body py-5">
                    <div class="text-center">
                        <div class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
                             style="height: 95px; width:95px;">
                            <h1 class="mb-0">
                                <i data-feather="alert-triangle"></i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" style="margin-top: -9px; margin-left: 0"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-alert-triangle">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </h1>
                        </div>
                        <div class="mt-4">
                            <h4>"{{ $team->name }}" Löschen?</h4>
                            <p class="text-muted">Bist Du sicher, dass Du diese Gruppe löschen möchtest. Dieser Schritt kann nicht rückgängig
                                gemacht werden?</p>
                            <div class="mt-4">
                                <a href="" class="btn btn-soft-danger btn-sm">Gruppe Löschen</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .row-custom {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
</div>
