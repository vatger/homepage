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
                                    <th class="border-bottom" style="width: 20%">Anpassen</th>
                                    <th class="border-bottom" style="width: 20%">Anlegen</th>
                                </tr>
                                </thead>
                                <tbody id="member-list-content">
                                @foreach ($emails as $email)
                                    <tr class="text-center">
                                        <td>{{ $email->id }}</td>
                                        <td>{{ $email->username }}</td>
                                        <td>{{ $email->email }}</td>
                                        <td><button name="change" class="btn btn-primary" @if($email->change) disabled @endif wire:click="change({{$email->id}})">Anpassen</button></td>
                                        <td><button name="create" class="btn btn-primary" @if($email->create) disabled @endif wire:click="create({{$email->id}})">Anlegen</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-layouts.admin.card>
            </div>

        </div>
        <!--end row-->
    </div>

</div>
