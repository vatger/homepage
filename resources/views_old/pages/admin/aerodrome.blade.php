<div class="container-fluid">
    <div class="layout-specing">

        <x-layouts.admin.content
            :header="$aerodrome->icao"
            :links="[
                    route(
                    'administration.dashboard') => 'Administration',
                    route('administration.navigation') => 'Navigation',
                    route('administration.navigation.aerodromes') => 'Flugplatzverwaltung',
                ]"
        ></x-layouts.admin.content>

        <x-layouts.admin.card-image-bar
            :bg_img="$aerodrome->background_image_url ?? iasset('images/profile/profile_1.png')"
            :m_img="iasset('/images/profile/avatar_placeholder.png')"
            :title="$aerodrome->name"
            :subtitle="$aerodrome->icao . ' ' . $aerodrome->iata "
        ></x-layouts.admin.card-image-bar>

        <div class="row">
            <x-layouts.admin.sidebar-col
                title="Übersicht"
                position="left"
                :items="[
                        ['Name', $aerodrome->name],
                        ['ICAO', $aerodrome->icao, 'database'],
                        ['IATA', $aerodrome->iata, 'database'],
                        ['Stadt', $aerodrome->city, 'map'],
                        ['Bundesland', $aerodrome->state, 'map'],
                        ['Zivil', $aerodrome->civilian ? 'Yes' : 'No', 'check-circle'],
                        ['Militär', $aerodrome->military ? 'Yes' : 'No', 'check-circle'],
                        ['Längengrad', $aerodrome->latitude, 'map-pin'],
                        ['Breitengrad', $aerodrome->longitude, 'map-pin'],
                        ['Höhe', $aerodrome->elevation, 'bar-chart-2'],

                    ]"
            >

                <div class="d-flex align-items-center mt-3 border-top pt-3">
                    <div class="flex-1">
                        <form wire:submit="save">
                            <div class="mb-3">
                                <label for="formFileSm" class="form-label">Aerodrome Header Image</label>
                                <input type="file" wire:model="photo" class="form-control form-control-sm" id="formFileSm">
                            </div>
                            @error('photo') <span class="error">{{ $message }}</span> @enderror
                            <div class="col-auto">
                                <button type="submit" class="btn btn-soft-primary btn-sm">
                                    Bild Ändern
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-layouts.admin.sidebar-col>
            <!--end col-->

            <x-layouts.admin.sidebar-col
                position="right"
            >
                <x-layouts.admin.card>
                    <x-layouts.admin.card-header
                        position="left"
                        title="Zugewiesene Stationen"
                        :subtitle="$aerodrome->stations()->count()"
                    ></x-layouts.admin.card-header>
                    <x-layouts.admin.card-header
                        position="right"
                    >
                        <li class="list-inline-item" style="width: 100%">
                            <div class="row">
                                <a href="https://github.com/VATGER-Nav/datahub">Stationen verwalten</a>
                            </div>
                        </li>
                    </x-layouts.admin.card-header>

                    <div class="p-4 table-responsive">
                        <table class="table table-center bg-white mb-0">
                            <thead>
                            <tr class="text-center">
                                <th class="border-bottom p-3">Name</th>
                                <th class="border-bottom p-3">Ident</th>
                                <th class="border-bottom p-3">Frequency</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($aerodrome->stations as $s)
                                <tr class="text-center">
                                    <td>{{ $s->name }}</td>
                                    <td>{{ $s->ident }} </td>
                                    <td>{{ $s->fixed_frequency }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </x-layouts.admin.card>

                <x-layouts.admin.card>
                    <x-layouts.admin.card-header
                        position="left"
                        title="Links"
                        :subtitle="$aerodrome->links()->count()"
                    ></x-layouts.admin.card-header>
                    <x-layouts.admin.card-header
                        position="right"
                    >
                        Add
                    </x-layouts.admin.card-header>


                    <div class="p-4 table-responsive">
                        <table class="table table-center bg-white mb-0">
                            <thead>
                            <tr class="text-center">
                                <th class="border-bottom p-3">Type</th>
                                <th class="border-bottom p-3">Name</th>
                                <th class="border-bottom p-3">Link</th>
                                <th class="border-bottom p-3">id</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($aerodrome->links as $l)
                                <tr class="text-center">
                                    <td>{{ $l->type }}</td>
                                    <td>{{ $l->name }} </td>
                                    <td>{{ $l->href }}</td>
                                    <td>{{ $l->id }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </x-layouts.admin.card>

            </x-layouts.admin.sidebar-col>


            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div>
