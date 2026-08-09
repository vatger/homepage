<div>
    <div class="container-fluid">
        <div class="layout-specing">
            <x-layouts.admin.content
                    :header="$team->name"
                    :links="[
                    route('administration.dashboard') => 'Administration',
                    route('administration.teams') => 'Gruppenverwaltung',
                ]"
            ></x-layouts.admin.content>

            <div class="row">
                <x-layouts.admin.sidebar-col position="left" title="Übersicht">
                    <div class="d-flex align-items-center mb">
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
                            @can('membership.teams.edit')
                                <select wire:model.live="selected_superteam" class="form-select form-control mt-2" aria-label="Übergeordnetes Team">
                                    <option value="-1" @if($selected_superteam == -1) selected @endif>kein übergeordnetes Team</option>
                                    @foreach(App\Models\Groups\Team::all() as $t)
                                        <option value="{{$t->id}}" @if($selected_superteam == $t->id) selected @endif>{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            @endcan
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
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

                    @can('membership.teams.edit')
                        <div class="border-top pt-3 mt-3">
                            <h6 class="text-primary mb-3">Team-Anzeige</h6>
                            <div class="mb-2">
                                <label class="form-label" for="team-title-de">Titel Deutsch</label>
                                <input id="team-title-de" wire:model="team_title_de" class="form-control form-control-sm" type="text">
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="team-title-en">Titel Englisch</label>
                                <input id="team-title-en" wire:model="team_title_en" class="form-control form-control-sm" type="text">
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="team-email">Team-E-Mail</label>
                                <input id="team-email" wire:model="team_email" class="form-control form-control-sm" type="email" placeholder="team@example.org">
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label" for="team-order">Reihenfolge</label>
                                    <input id="team-order" wire:model="team_order" class="form-control form-control-sm" type="number" min="0">
                                </div>
                                <div class="col-6 d-flex align-items-end">
                                    <div class="form-check mb-1">
                                        <input id="team-show" wire:model="team_show" class="form-check-input" type="checkbox">
                                        <label class="form-check-label" for="team-show">Anzeigen</label>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="saveTeamDisplaySettings" class="btn btn-sm btn-soft-primary" type="button">Speichern</button>
                        </div>
                    @endcan

                    <div class="d-flex align-items-center pt-3 mt-3 border-top">
                        <button class="btn btn-sm btn-soft-danger" data-bs-toggle="modal" data-bs-target="#deleteGroupModal">Gruppe
                            Löschen
                        </button>
                        <x-layouts.admin.danger-modal
                                id="deleteGroupModal"
                                title="{{ $team->name }} Löschen?"
                                text="Bist Du sicher, dass Du diese Gruppe löschen möchtest? Dieser Schritt kann nicht rückgängig gemacht werden?"
                        >
                            <div class="mt-4">
                                <button wire:click="deleteTeam()" class="btn btn-soft-danger btn-sm">Gruppe Löschen</button>
                            </div>
                        </x-layouts.admin.danger-modal>
                    </div>
                </x-layouts.admin.sidebar-col>

                <x-layouts.admin.sidebar-col position="right">
                    <x-layouts.admin.card>
                        <x-layouts.admin.card-header position="left" title="Mitglieder" :subtitle="$team->users->count()" />
                        <x-layouts.admin.card-header position="right">
                            <li class="list-inline-item" style="width: 100%">
                                <div class="row">
                                    <input wire:model="user_id" type="number" class="form-control-sm form-control float-end mb-1" placeholder="CID">
                                    <button wire:click="addUser()" class="btn btn-sm btn-soft-primary float-end">Benutzer Hinzufügen</button>
                                </div>
                            </li>
                        </x-layouts.admin.card-header>


                        <div class="row pt-4 ps-4 table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th class="border-bottom">CID</th>
                                    <th class="border-bottom">Name</th>
                                    <th class="border-bottom">Titel DE</th>
                                    <th class="border-bottom">Titel EN</th>
                                    <th class="border-bottom">Anzeigen</th>
                                    <th class="border-bottom">Reihenfolge</th>
                                    <th class="border-bottom">Aktion</th>
                                </tr>
                                </thead>
                                <tbody id="member-list-content">
                                @if ($team->users->count() == 0)
                                    <tr class="text-center">
                                        <td colspan="7" class="text-muted text-center">Keine Benutzer in dieser Gruppe</td>
                                    </tr>
                                @else
                                    @foreach ($team->users as $u)
                                        <tr class="text-center" id="user-{{ $u->id }}">
                                            <td>{{ $u->id }}</td>
                                            <td>{{ $u->username }}</td>
                                            <td>
                                                <input wire:model="member_settings.{{ $u->id }}.title_de" list="member-title-de-options" class="form-control form-control-sm" type="text" aria-label="Titel Deutsch">
                                            </td>
                                            <td>
                                                <input wire:model="member_settings.{{ $u->id }}.title_en" list="member-title-en-options" class="form-control form-control-sm" type="text" aria-label="Titel Englisch">
                                            </td>
                                            <td><input wire:model="member_settings.{{ $u->id }}.show" class="form-check-input" type="checkbox" aria-label="Anzeigen"></td>
                                            <td><input wire:model="member_settings.{{ $u->id }}.order" class="form-control form-control-sm" type="number" min="0" aria-label="Reihenfolge"></td>
                                            <td class="text-nowrap">
                                                <button wire:click="saveMemberDisplaySettings({{ $u->id }})" class="btn btn-sm btn-soft-primary mb-1" type="button">Speichern</button>
                                                <button wire:confirm="Soll dieses Mitglied wirklich aus dem Team entfernt werden?" wire:click="removeUser({{$u->id}})" class="btn btn-sm btn-soft-danger">Entfernen
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <datalist id="member-title-de-options">
                            @foreach($member_title_recommendations['de'] as $title)
                                <option value="{{ $title }}"></option>
                            @endforeach
                        </datalist>
                        <datalist id="member-title-en-options">
                            @foreach($member_title_recommendations['en'] as $title)
                                <option value="{{ $title }}"></option>
                            @endforeach
                        </datalist>
                    </x-layouts.admin.card>
                </x-layouts.admin.sidebar-col>
            </div>


            @can('membership.teams.edit')
                <div class="row">
                    <x-layouts.admin.sidebar-col position="left" title="External Groups">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach ($external_service_statuses as $status)
                                <span class="badge rounded-pill {{ $status['available'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $status['label'] }}:
                                    {{ $status['available'] ? 'Available' : 'Unavailable' }}
                                </span>
                            @endforeach
                        </div>
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-center bg-white mb-0 w-100" style="table-layout: fixed;">
                                <thead>
                                <tr class="text-center">
                                    <th style="width: 25%;">Service</th>
                                    <th>External group</th>
                                    <th style="width: 72px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($external_groups as $r)
                                    <tr class="text-center">
                                        <td class="text-break">
                                            {{ str($r->external_group_type->name)->headline() }}
                                        </td>
                                        <td class="text-break">
                                            <span>{{ $r->external_group }}</span>
                                            @if($r->external_group_name)
                                                <small class="d-block">({{ $r->external_group_name }})</small>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <button wire:confirm="Soll diese externe Gruppe wirklich entfernt werden?" wire:click="removeExternalGroup({{ $r->id }})" class="btn btn-sm btn-soft-danger">
                                                <i data-feather="trash" class="fea icon-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <select wire:model="selected_external_group_type" class="form-select form-control-sm form-control" aria-label="External group service">
                                            @foreach(App\Models\Groups\TeamExternalGroupType::cases() as $type)
                                                <option value="{{ $type->value }}">{{ str($type->name)->headline() }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input wire:model="selected_external_group" class="form-control-sm form-control" aria-label="External group identifier" />
                                    </td>
                                    <td>
                                        <button wire:click="addExternalGroup" class="btn btn-sm btn-soft-success"><i data-feather="plus" class="fea icon-sm"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">

                        </div>

                    </x-layouts.admin.sidebar-col>

                    <x-layouts.admin.sidebar-col position="right">
                        <x-layouts.admin.card>
                            <x-layouts.admin.card-header position="left" title="Permissions" :subtitle="$team->permissions->count()" icon="lock" />
                            <x-layouts.admin.card-header position="right" />
                            <div class="row pt-4 ps-4 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Berechtigung</th>
                                        <th>Aktion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($permissions as $p)
                                        <tr class="text-center">
                                            <td>{{ $p->id }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>
                                                @if ($team->hasPermissionTo($p))
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
                        </x-layouts.admin.card>
                    </x-layouts.admin.sidebar-col>
                </div>
                <!--end row-->
            @endcan
        </div>
        <!--end row-->
    </div>

</div>
