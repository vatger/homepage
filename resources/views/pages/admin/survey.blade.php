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
                        <div class="flex-1">
                            <h6 class="text-primary mb-2">Ausgewählte Umfrage:</h6>
                            <select wire:model="selected_survey" class="form-select form-control mt-2" aria-label="Ausgewählte Umfrage">
                                @foreach($surveys as $s)
                                    <option value="{{$s->sid}}" @if($selected_survey == $s->sid) selected @endif>{{ $s->surveyls_title . '#' . $s->sid }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb">
                        <div class="flex-1">
                            <h6 class="text-primary mb-2">Ausgewählte Gruppe:</h6>
                            <select wire:model="selected_selection" class="form-select form-control mt-2" aria-label="Ausgewählte Gruppe">
                                @foreach($selections as $s)
                                    <option value="{{$s->id}}" @if($selected_selection == $s->id) selected @endif>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb">
                        <div class="flex-1">
                            <h6 class="text-primary mb-2">Keys generieren:</h6>
                            <p>Ausgewählte Umfrage: <code>?</code></p>
                            <p>Ausgewählte Gruppe: <code>?</code></p>
                            <button class="btn">
                                <i data-feather="plus" class="fea"></i>
                            </button>
                        </div>
                    </div>

                </x-layouts.admin.sidebar-col>

                <x-layouts.admin.sidebar-col position="right">
                    <x-layouts.admin.card>
                        <x-layouts.admin.card-header position="left" title="Keys" :subtitle="$keys->count()" />
                        <x-layouts.admin.card-header position="right">
                            <li class="list-inline-item" style="width: 100%">
                                <div class="row">
                                    <input type="text" class="form-control-sm form-control float-end mb-1" placeholder="Search" disabled>
                                </div>
                            </li>
                        </x-layouts.admin.card-header>


                        <div class="row pt-4 ps-4 table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th class="border-bottom" style="width: 33%">CID</th>
                                    <th class="border-bottom" style="width: 33%">Survey Name</th>
                                    <th class="border-bottom" style="width: 33%">Aktion</th>
                                </tr>
                                </thead>
                                <tbody id="member-list-content">
                                @if ($keys->count()==0)
                                    <tr class="text-center">
                                        <td colspan="3" class="text-muted text-center">Noch keine Keys</td>
                                    </tr>
                                @else
                                    @foreach ($keys as $k)
                                        <tr class="text-center">
                                            <td>{{ $k->user_id }}</td>
                                            <td>{{ $k->name }}</td>
                                            <td>
                                                ?
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
