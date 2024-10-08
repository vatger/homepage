<div>
    <div class="container-fluid">
        <div class="layout-specing">
            <x-layouts.admin.content
                header="Email Verwaltung"
                :links="[
                    route('administration.dashboard') => 'Administration',
                    route('administration.survey') => 'Email Verwaltung',
                ]"
            ></x-layouts.admin.content>

            <div class="row">


                <x-layouts.admin.card>

                    <div class="row pt-4 ps-4 table-responsive">
                        <table class="table table-center bg-white mb-0">
                            <thead>
                            <tr class="text-center">
                                <th class="border-bottom" style="width: 10%">CID</th>
                                <th class="border-bottom" style="width: 20%">Username</th>
                                <th class="border-bottom" style="width: 20%">E-Mailadresse</th>
                                <th class="border-bottom" style="width: 15%">Adresse anpassen</th>
                                <th class="border-bottom" style="width: 15%">Anlegen</th>
                                <th class="border-bottom" style="width: 20%">Löschen ab</th>
                            </tr>
                            </thead>
                            <tbody id="member-list-content">
                            @foreach ($emails as $email )
                                <tr class="text-center">
                                    <td>{{ $email->id }}</td>
                                    <td>{{ $email->username }}</td>
                                    <td>{{ $email->email }}</td>
                                    <td>
                                        <button wire:click='change("{{$email->id}}","{{$email->email}}")' data-bs-toggle="modal" data-bs-target="#LoginForm" name="change"
                                                @if($email->change)
                                                    class="btn btn-outline-primary" disabled
                                                @else
                                                    class="btn btn-primary"
                                            @endif
                                        >Anpassen
                                        </button>
                                    </td>
                                    <td>
                                        <button wire:click='create("{{ $email->id }}")' name="create"
                                                @if($email->create)
                                                    class="btn btn-outline-primary" disabled
                                                @else
                                                    class="btn btn-primary"
                                            @endif>Anlegen
                                        </button>
                                    </td>
                                    <td>
                                        @if($email->deletion_date)
                                            {{ date('d.m.Y H:i:s', strtotime($email->deletion_date) }}
                                            @if($email->deletion_date < Carbon\Carbon::now())
                                                <button wire:click='delete("{{ $email->id }}")' name="delete"
                                                        class="btn btn-danger">Löschen
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div wire:ignore.self class="modal fade" id="LoginForm" tabindex="-1" aria-labelledby="LoginForm-title" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded shadow border-0">
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title" id="LoginForm-title">Neue E-Mail Adresse eingeben</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-icon position-relative">
                                            <i data-feather="mail" class="fea icon-sm icons"></i>
                                            <input wire:model="newmail" name="email" id="email" type="email" class="form-control ps-5" value="">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                        <button wire:click="save()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Speichern</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </x-layouts.admin.card>
            </div>

        </div>
        <!--end row-->
    </div>

</div>
