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
                        <x-layouts.admin.card-header position="left" title="E-Mail Adressen"  :subtitle="count($emails)" />

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
                                @foreach ($emails as $email )
                                    <tr class="text-center">
                                        <td>{{ $email->id }}</td>
                                        <td>{{ $email->username }}</td>
                                        <td>{{ $email->email }}</td>
                                        <td><button wire:click='change("{{$email->id}}","{{$email->email}}")' data-bs-toggle="modal" data-bs-target="#LoginForm" name="change" class="btn btn-primary" @if($email->change) disabled @endif>Anpassen</button></td>
                                        <td><button name="create" class="btn btn-primary" @if(true) disabled @endif wire:click="create({{ $email->id }},{{ $email->email }})">Anlegen</button></td>
                                        {{--<td><button name="create" class="btn btn-primary" @if($email->create) disabled @endif wire:click="create({{ $email->id }},{{ $email->email }})">Anlegen</button></td>--}}
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
