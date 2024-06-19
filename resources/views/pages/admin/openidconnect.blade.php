<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
            header="Passport Clients"
        ></x-layouts.admin.content>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="Passport Clients" :subtitle="\Laravel\Passport\Client::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class=" fea icon-sm icons"></i>
                        <input wire:model.live="search" class="form-control ps-5" type="date">
                    </div>
                </li>
            </x-layouts.admin.card-header>


            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text">
                        <th class="border-bottom p-3" style="white-space: nowrap">
                            client
                        </th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($clients as $c)
                        <tr>
                            <td>@json($c)</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $clients->links() }}
            </div>

        </x-layouts.admin.card>
    </div>
</div>
