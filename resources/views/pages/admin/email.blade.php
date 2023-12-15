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
                        <x-layouts.admin.card-header position="left" title="E-Mail Adressen"  :subtitle="0" />

                        <div class="row pt-4 ps-4 table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th class="border-bottom" style="width: 10%">CID</th>
                                    <th class="border-bottom" style="width: 20%">Username</th>
                                    <th class="border-bottom" style="width: 30%">E-Mailadresse</th>
                                    <th class="border-bottom" style="width: 20%">Änderung speichern</th>
                                    <th class="border-bottom" style="width: 20%">Anlegen</th>
                                </tr>
                                </thead>
                                <tbody id="member-list-content">
                                @foreach ($emails as $key => $email )
                                    <div>{{$key}}</div>
                                    <tr class="text-center">
                                        <td>{{ $email->id }}</td>
                                        <td>{{ $email->username }}</td>
                                        <td><div class="form-icon position-relative">
                                                <i data-feather="mail" class="fea icon-sm icons"></i>
                                                <input wire:model.live="emails.{{$key}}.email" type="text" @if($email->change) disabled @endif class="form-control ps-5">
                                            </div></td>
                                        <td><button name="change" class="btn btn-primary m-1" @if($email->change) disabled @endif wire:click="change({{$key}})">Änderung speichern</button></td>
                                        <td><button name="create" class="btn btn-primary" @if($email->create) disabled @endif wire:click="create({{$key}})">Anlegen</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="modal fade" id="LoginForm" tabindex="-1" aria-labelledby="LoginForm-title" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded shadow border-0">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title" id="LoginForm-title">Neue E-Mail Adresse eingeben</h5>
                                        </div>
                                        <div class="modal-body">


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                            <button wire:click="change()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Speichern</button>
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
