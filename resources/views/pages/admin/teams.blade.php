<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
                header="Gruppenverwaltung"
                :links="[ route('administration.dashboard') => 'Administration' ]"
        ></x-layouts.admin.content>

        <div class="row">
            <div class="col mt-4">
                <div class="card shadow border-0">
                    <div class="row p-4 border-bottom">
                        <div class="col-md-4 col-sm-12 mb-1">
                            <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                <div class="d-flex align-items-center">
                                    <div class="icon text-center rounded-pill">
                                        <i data-feather="circle"></i>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <h6 class="mb-0 text-muted">Teams</h6>
                                        <p class="fs-5 text-dark fw-bold mb-1">{{  App\Models\Groups\Team::count() }}</p>
                                        @if($limited_selection)
                                            <p class="text-warning">Es werden nur Teams angezeit, die dein Team verwalten kann.</p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('membership.teams.edit.permissions')
                            <div class="col-md-4 col-sm-12 mb-1">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <div class="subcribe-form">
                                        <h6 class="mb-2 text-muted">Neues Team anlegen</h6>
                                        <form>
                                            <input wire:model="addTeamName" class="form-control rounded-pill shadow" placeholder="Neuer Teamname">
                                            <button wire:click="addTeam" class="btn btn-pills btn-primary">
                                                <i data-feather="plus"></i>
                                            </button>
                                        </form><!--end form-->
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
                            <li class="list-inline-item" style="width: 100%">
                                <div class="form-icon position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-search fea icon-sm icons">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <input wire:model.live="search" class="form-control ps-5" type="text" placeholder="Teamname">
                                </div>
                            </li>
                        </div>
                    </div>

                    <div id="content-container">
                        <div class="row p-4 table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th class="border-bottom p-3">Name</th>
                                    <th class="border-bottom p-3">Super Team</th>
                                    <th class="border-bottom p-3">Aktion</th>
                                </tr>
                                </thead>
                                <tbody id="group-list-content">
                                @foreach($teams as $team)
                                    <tr class="text-center">
                                        <td>{{ $team->name }}</td>
                                        <td>{{ $team->super_team }}</td>
                                        <td>
                                            <a href="{{ route('administration.team', ['team' => $team]) }}" class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $teams->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
</div>
