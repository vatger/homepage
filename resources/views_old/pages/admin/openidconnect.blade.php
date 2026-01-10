<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
            header="Passport Clients"
        ></x-layouts.admin.content>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="Passport Clients" :subtitle="\Laravel\Passport\Client::count()" />

            <x-layouts.admin.card-header position="right">

            </x-layouts.admin.card-header>


            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text">
                        <th class="border-bottom p-3" style="white-space: nowrap">
                            id
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">
                            user_id
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">
                            name
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">
                            redirect
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">
                            secret
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($clients as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->user_id ?? '-' }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->redirect }}</td>
                            <td>{{ $c->secret }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $clients->links() }}
            </div>

        </x-layouts.admin.card>
    </div>
</div>
