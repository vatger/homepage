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

            <x-layouts.admin.card-image-bar
                    :bg_img="asset('images/profile/profile_1.png')"
                    :m_img="asset('/images/profile/avatar_placeholder.png')"
                    :title="$team->name"
                    :subtitle="'Erstellt am: ' . $team->created_at->format('d.m.Y')"
            ></x-layouts.admin.card-image-bar>

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
                        <x-layouts.admin.card-header position="left" title="Mitglieder" :subtitle="$team->role->users->count()" />
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
                    </x-layouts.admin.card>
                </x-layouts.admin.sidebar-col>
            </div>


            @can('membership.teams.edit')
                <div class="row">
                    <x-layouts.admin.sidebar-col position="left" title="Service Roles">
                        <div class="row table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th>Type</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($service_roles as $r)
                                    <tr class="text-center">
                                        <td>{{ $r->service_type }}</td>
                                        <td>{{ $r->service_role }}
                                            @if($r->service_role_name)
                                                <small>({{ $r->service_role_name }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="removeServiceRole({{ $r->id }})" class="btn btn-sm btn-soft-danger">
                                                <i data-feather="trash" class="fea icon-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <select wire:model="selected_service_role_type" class="form-select form-control-sm form-control" aria-label="">
                                            @foreach(\App\Models\Groups\ServiceRole::$allowed_service_types as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <label>
                                            <input wire:model="selected_service_role" class="form-control-sm form-control" />
                                        </label>
                                    </td>
                                    <td>
                                        <button wire:click="addServiceRole" class="btn btn-sm btn-soft-success"><i data-feather="plus" class="fea icon-sm"></i></button>
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
                            <x-layouts.admin.card-header position="left" title="Permissions" :subtitle="$team->role->permissions->count()" icon="lock" />
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
                        </x-layouts.admin.card>
                    </x-layouts.admin.sidebar-col>
                </div>
                <!--end row-->
            @endcan
        </div>
        <!--end row-->
    </div>

</div>
