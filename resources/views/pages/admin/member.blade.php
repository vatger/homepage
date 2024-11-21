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
                :bg_img="iasset('images/profile/profile_1.png')"
                :m_img="iasset('/images/profile/avatar_placeholder.png')"
                :title="$user->username"
                :subtitle="$user->id"
            ></x-layouts.admin.card-image-bar>

            <div class="row">
                <x-layouts.admin.sidebar-col
                    position="left"
                    title="Persönliche Daten"
                    :items="[
                            $user->isCurrentlyInRemoval() ? ['Removal', 'PENDING' ,'user-x'] : [],
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
                                    <a wire:ignore.self class="nav-link active" id="pills-cloud-tab" data-bs-toggle="pill" href="#activity-pill" role="tab"
                                       aria-controls="pills-cloud" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Aktivität</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a wire:ignore.self class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#ts-board-pill" role="tab"
                                       aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">TS & Forum</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->


                                <li class="nav-item">
                                    <a wire:ignore.self class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#bans-pill" role="tab"
                                       aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Sperren</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a wire:ignore.self class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#staff-pill" role="tab"
                                       aria-controls="pills-apps" aria-selected="false">
                                        <div class="text-center py-1">
                                            <h6 class="mb-0">Staff</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->

                                <li class="nav-item">
                                    <a wire:ignore.self class="nav-link" id="pills-apps-tab" data-bs-toggle="pill" href="#danger-pill" role="tab"
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
                                <div wire:ignore.self class="tab-pane fade show active" id="activity-pill" role="tabpanel" aria-labelledby="activity-pill">
                                    <h4 class="card-title">Membership data:</h4>
                                    <ul>
                                        <li>last_seen_at: {{ $user->vatgerDetails->last_seen_at }}</li>
                                        <li>registered_at: {{ $user->vatgerDetails->registered_at }}</li>
                                        <li>active_member_at: {{ $user->vatgerDetails->active_member_at }}</li>
                                        <li>vatger_member_at: {{ $user->vatgerDetails->vatger_member_at }}</li>
                                        <li>active_vatger_member_at:
                                            {{ $user->vatgerDetails->active_vatger_member_at }}
                                        </li>
                                        <li>warning_inactive_at: {{ $user->vatgerDetails->warning_inactive_at }}</li>
                                        <li>inactive_at: {{ $user->vatgerDetails->inactive_at }}</li>
                                        <li>warning_delete_at: {{ $user->vatgerDetails->warning_delete_at }}</li>
                                        <li>delete_at: {{ $user->vatgerDetails->delete_at }}</li>

                                        <li>is_fir_active_member:
                                            {{ $user->vatgerDetails->is_fir_active_member ? 'yes' : 'no' }}</li>
                                        <li>is_vatger_voter:
                                            {{ $user->vatgerDetails->is_vatger_voter ? 'yes' : 'no' }}</li>
                                        <li>is_fir_voter:
                                            {{ $user->vatgerDetails->is_fir_voter ? 'yes' : 'no' }}</li>
                                        <li>can_change_fir:
                                            {{ $user->vatgerDetails->can_change_fir ? 'yes' : 'no' }}
                                            <small>({{  $user->vatgerDetails->can_change_fir_reason }})</small>
                                        </li>

                                    </ul>


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
                                <!--end teb pane-->


                                <div wire:ignore.self class="tab-pane fade" id="ts-board-pill" role="tabpanel" aria-labelledby="teamspeak-pill">
                                    <h4 class="card-title">Teamspeak:</h4>

                                    @if(count($user->teamspeakRegistrations) > 0)
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
                                    @else
                                        <p class="text-muted mb-0">
                                            The user does not have an account.
                                        </p>
                                    @endif

                                    <hr>
                                    <h4 class="card-title">Forum:</h4>
                                    <p class="text-muted mb-0">
                                        @if($user->settings->forum_id)
                                            The user has an account with the id <code>{{ $user->settings->forum_id }}</code>.
                                        @else
                                            The user does not have an account.
                                        @endif

                                    </p>

                                </div>
                                <!--end teb pane-->

                                <div wire:ignore.self class="tab-pane fade" id="forum-pill" role="tabpanel" aria-labelledby="forum-pill">

                                </div>
                                <!--end teb pane-->

                                <div wire:ignore.self class="tab-pane fade" id="bans-pill" role="tabpanel" aria-labelledby="bans-pill">
                                    <button type="button" class="mb-3 btn btn-sm btn-danger" data-bs-target="#suspension-modal" data-bs-toggle="modal">Sperre Hinzufügen</button>

                                    <table class="table mb-0 table-center">
                                        <thead>
                                        <tr>
                                            <th>Typ</th>
                                            <th>Start</th>
                                            <th>Ende</th>
                                            <th>Aktion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user->bans as $b)
                                            <tr>
                                                <td>@switch($b->type)
                                                        @case(\App\Models\Membership\UserBanType::vatger_ban) VATGER @break
                                                        @case(\App\Models\Membership\UserBanType::vatsim_inactivity) Inaktiv @break
                                                        @case(\App\Models\Membership\UserBanType::vatsim_ban) VATSIM @break
                                                        @case(\App\Models\Membership\UserBanType::pilot_rating_incomplete) P0 nicht bestanden @break
                                                        @default Unbekannt @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $b->starts_at->format('d.m.Y H:i') }}</td>
                                                <td>{{ $b->ends_at?->format('d.m.Y H:i') ?? 'Permanent' }}</td>
                                                <td>
                                                    <button data-bs-target="#suspension-modal-view" wire:click="showBanInformation({{$b->id}})" data-bs-toggle="modal"
                                                            class="btn btn-sm btn-outline-primary">Details
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end teb pane-->

                                <div wire:ignore class="tab-pane fade" id="staff-pill" role="tabpanel" aria-labelledby="danger-pill">
                                    <h4 class="card-title">Staff service accounts:</h4>
                                    @if(count($user->service_roles()) > 0)
                                        <table class="table mb-0 table-center">
                                            <thead>
                                            <tr>
                                                <th>service_type</th>
                                                <th>service_role</th>
                                                <th>service_role_name</th>
                                                <th>via team_id</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($user->service_roles() as $s)
                                                <tr>
                                                    <td>{{ $s->service_type }}</td>
                                                    <td>{{ $s->service_role }}</td>
                                                    <td>{{ $s->service_role_name }}</td>
                                                    <td>{{ $s->team_id }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted mb-0">
                                            The user does not have an account.
                                        </p>
                                    @endif
                                </div>
                                <!--end teb pane-->

                                <div wire:ignore class="tab-pane fade" id="danger-pill" role="tabpanel" aria-labelledby="danger-pill">
                                    <div class="mt-4">
                                        <button class="btn btn-sm btn-primary" wire:click="force_member_update()">Force Member Update</button>
                                        <p class="text-muted mt-2">
                                            Zieht sich einmal neue Informationen aus der API und stößt update Aktionen an. Kann bedenkenlos ausgeführt werden.
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-sm btn-primary" wire:click="mark_member_seen()">Force Member Update</button>
                                        <p class="text-muted mt-2">
                                            Der Member ist inaktiv, will seinen Account verlängern. Setzt last_seen_at = now().
                                        </p>
                                    </div>
                                    <hr>
                                    <div class="mt-4">
                                        <button class="btn btn-sm btn-danger" wire:confirm="ACHTUNG: Soll der Nutzer zur Löschung markiert werden?" wire:click="mark_member_for_removal()">Mark for
                                            deletion!
                                        </button>
                                        <p class="text-muted mt-2">
                                            Markiert den Account zur Löschung, wenn der User nicht in 24h auf die E-Mail reagiert, wird der Account gelöscht.
                                        </p>
                                    </div>
                                    <hr>
                                    <div class="mt-4">
                                        <button class="btn btn-sm btn-danger" wire:confirm="ACHTUNG: Soll der Nutzer gelöscht werden?" wire:click="mark_member_for_removal_now()">Delete now!!!
                                        </button>
                                        <p class="text-muted mt-2">
                                            Markiert den Account zur Löschung, der Account wird direkt gelöscht.
                                        </p>
                                    </div>
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

    <x-admin.member.createban-modal></x-admin.member.createban-modal>
    <x-admin.member.viewban-modal :banInformation="$banInformation"></x-admin.member.viewban-modal>

    <style>
        .nav-link {
            border-radius: 0 !important;
        }
    </style>
</div>

@push('scripts')
    @vite(['resources/ts/special/member.ts'])
@endpush
