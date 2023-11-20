<div>
    <div class="container-fluid">
        <div class="layout-specing">
            <x-layouts.admin.content
                    header="Survey Verwaltung"
                    :links="[
                    route('administration.dashboard') => 'Administration',
                    route('administration.survey') => 'Survey Verwaltung',
                ]"
            ></x-layouts.admin.content>

            <div class="row">
                <x-layouts.admin.sidebar-col position="left" title="Übersicht">
                    <div class="d-flex align-items-center mb">
                        <i data-feather="arrow-up" class="fea icon-ex-md text-muted me-3"></i>
                        <div class="flex-1">
                            <h6 class="text-primary mb-2">Übergeordnetes Team:</h6>

                            @can('membership.')
                                <select wire:model.live="selected_superteam" class="form-select form-control mt-2" aria-label="Übergeordnetes Team">
                                    <option value="-1" @if($selected_superteam == -1) selected @endif>kein übergeordnetes Team</option>
                                    @foreach(App\Models\Groups\Team::all() as $t)
                                        <option value="{{$t->id}}" @if($selected_superteam == $t->id) selected @endif>{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            @endcan
                        </div>
                    </div>

                </x-layouts.admin.sidebar-col>

                <x-layouts.admin.sidebar-col position="right">
                    <x-layouts.admin.card>
                        <x-layouts.admin.card-header position="left" title="Mitglieder" :subtitle="999" />
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
                                @if (1)
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

        </div>
        <!--end row-->
    </div>

</div>
