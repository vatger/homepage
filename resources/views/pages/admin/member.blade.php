<div>
    <div class="container-fluid">
        <div class="layout-specing">
            <x-layouts.admin.content
                    :header="$user->username"
                    :links="[
                        route('administration.dashboard') => 'Administration',
                        route('administration.members') => 'Mitgliederverwaltung'
                    ]"
            ></x-layouts.admin.content>

            <x-layouts.admin.card-image-bar
                    :bg_img="asset('images/profile/profile_1.png')"
                    :m_img="asset('/images/profile/avatar_placeholder.png')"
                    :title="$user->username"
                    :subtitle="$user->id"
            ></x-layouts.admin.card-image-bar>

            <div class="row">
                <x-layouts.admin.sidebar-col
                        position="left"
                        title="Persönliche Daten"
                        :items="[
                            $acting_user?->can('membership.users.details.view.email') ? ['Email', $user->email ,'mail'] : [],
                            ['Ausbildung', $user->vatsimDetails->rating_atc_short . ' | ' .$user->vatsimDetails->rating_pilot_short . ' | ' . $user->vatsimDetails->rating_military_short,'book-open'],
                            ['Regionszuweisung', $user->vatsimDetails->region_name . ' (' . $user->vatsimDetails->region_code . ')','globe'],
                            ['Divisionszuordnung', $user->vatsimDetails->division_name . ' (' . $user->vatsimDetails->division_code . ')','globe'],
                            ['vACC Zuordnung', $user->vatsimDetails->subdivision_name . ' (' . $user->vatsimDetails->subdivision_code . ')','globe'],

                        ]"
                ></x-layouts.admin.sidebar-col>

                <x-layouts.admin.sidebar-col
                        position="right">
                    <x-layouts.admin.card>
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

                        <div class="col-12">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="activity-pill" role="tabpanel" aria-labelledby="activity-pill">
                                    <div class="card shadow border-0 overflow-hidden">
                                        <div class="card-body">
                                            <h4 class="card-title">Current and past FIR memberships:</h4>
                                            <table class="table mb-0 table-center">
                                                <thead>
                                                <tr>
                                                    <th>name</th>
                                                    <th>joined</th>
                                                    <th>active_fir_member_at</th>
                                                    <th>left</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($user->firs as $f)
                                                    <tr>
                                                        <td>{{ $f->name }}</td>
                                                        <td>{{ $f->joined_at }}</td>
                                                        <td>{{ $f->active_fir_member_at }}</td>
                                                        <td>{{ $f->deleted_at }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--end teb pane-->

                                <div class="tab-pane fade" id="notes-pill" role="tabpanel" aria-labelledby="notes-pill">
                                    <p class="text-muted mb-0">
                                        @json($user->notes)
                                    </p>
                                </div>
                                <!--end teb pane-->

                                <div class="tab-pane fade" id="teamspeak-pill" role="tabpanel" aria-labelledby="teamspeak-pill">

                                    <div class="card shadow border-0 overflow-hidden">
                                        <div class="card-body">
                                            <h4 class="card-title">Current teamspeak registrations:</h4>
                                            <table class="table mb-0 table-center">
                                                <thead>
                                                <tr>
                                                    <th>uid</th>
                                                    <th>dbid</th>
                                                    <th>created_at</th>
                                                    <th>last_login</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($user->teamspeakRegistrations as $t)
                                                    <tr>
                                                        <td>{{ $t->uid }}</td>
                                                        <td>{{ $t->dbid }}</td>
                                                        <td>{{ $t->created_at }}</td>
                                                        <td>{{ $t->last_login }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0">
                                        He should have the groups: <code>@json($user->service_roles(\App\Models\Groups\ServiceRoleType::TeamspeakServergroup))</code>.
                                    </p>
                                </div>
                                <!--end teb pane-->

                                <div class="tab-pane fade" id="forum-pill" role="tabpanel" aria-labelledby="forum-pill">
                                    <p class="text-muted mb-0">
                                        @if($user->settings->forum_id)
                                            The user has an account with the id <code>{{ $user->settings->forum_id }}</code>.
                                            He should have the groups: <code>@json($user->service_roles(\App\Models\Groups\ServiceRoleType::ForumGroup))</code>.
                                        @else
                                            The user does not have an account.
                                        @endif

                                    </p>
                                </div>
                                <!--end teb pane-->

                                <div class="tab-pane fade" id="bans-pill" role="tabpanel" aria-labelledby="bans-pill">

                                    <div class="card shadow border-0 overflow-hidden">
                                        <div class="card-body">
                                            <h4 class="card-title">Current and past bans:</h4>
                                            <table class="table mb-0 table-center">
                                                <thead>
                                                <tr>
                                                    <th>type</th>
                                                    <th>starts_at</th>
                                                    <th>ends_at</th>
                                                    <th>author_id & reason</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($user->bans as $b)
                                                    <tr>
                                                        <td>{{ $b->type }}</td>
                                                        <td>{{ $b->starts_at }}</td>
                                                        <td>{{ $b->ends_at }}</td>
                                                        <td>
                                                            <b>{{ $b->author_id }}</b>
                                                            <p>{{ $b->reason }}</p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <!--end teb pane-->

                                <div class="tab-pane fade" id="danger-pill" role="tabpanel" aria-labelledby="danger-pill">
                                    <p class="text-muted mb-0">
                                        <button wire:click="force_member_update()">force member update</button>
                                    </p>
                                </div>
                                <!--end teb pane-->
                            </div>
                            <!--end tab content-->
                        </div>
                        <!--end col-->

                    </x-layouts.admin.card>
                </x-layouts.admin.sidebar-col>

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
</div>
