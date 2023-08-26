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
                :bg_img="asset('images/profile/profile_1.png')"
                :m_img="asset('/images/profile/avatar_placeholder.png')"
                :title="$aerodrome->name"
                :subtitle="$aerodrome->icao . ' ' . $aerodrome->iata "
        ></x-layouts.admin.card-image-bar>

        <div class="row">
            <x-layouts.admin.sidebar-col
                    title="Übersicht"
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
                <button class="btn btn-sm btn-soft-primary mt-3" id="editAerodromeDataButton" data-bs-toggle="modal"
                        data-bs-target="#editAerodromeDataModal">Daten
                    Bearbeiten
                </button>
                
                <div class="d-flex align-items-center mt-3 border-top pt-3">
                    <div class="flex-1">
                        <form enctype="multipart/form-data" id="upload-image-form">
                            <div class="mb-3">
                                <label for="formFileSm" class="form-label">Aerodrome Header Image</label>
                                <input class="form-control form-control-sm" id="formFileSm" type="file" name="image">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-soft-primary btn-sm">Bild Ändern</button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-layouts.admin.sidebar-col>
            <!--end col-->

            <div class="modal fade" id="editAerodromeDataModal" tabindex="-1" aria-labelledby="editAerodromeDataModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAerodromeDataModalLabel">Flugplatzdaten bearbeiten</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editAerodromeDataForm">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="mb-3">
                                            <label for="syslog-account" class="form-label">Name</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-hash fea icon-sm icons">
                                                    <line x1="4" y1="9" x2="20" y2="9"></line>
                                                    <line x1="4" y1="15" x2="20" y2="15"></line>
                                                    <line x1="10" y1="3" x2="8" y2="21"></line>
                                                    <line x1="16" y1="3" x2="14" y2="21"></line>
                                                </svg>
                                                <input name="name" id="name" class="form-control ps-5" data-form-type="other"
                                                       value="{{ $aerodrome->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">ICAO</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-compass fea icon-sm icons">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                                </svg>
                                                <input name="icao" id="icao" class="form-control ps-5" data-form-type="other"
                                                       value="{{ $aerodrome->icao }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="syslog-account" class="form-label">IATA</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-maximize-2 fea icon-sm icons">
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <polyline points="9 21 3 21 3 15"></polyline>
                                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                                </svg>
                                                <input name="iata" id="iata" class="form-control ps-5" data-form-type="other"
                                                       value="{{ $aerodrome->iata }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Stadt</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-maximize-2 fea icon-sm icons">
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <polyline points="9 21 3 21 3 15"></polyline>
                                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                                </svg>
                                                <input name="city" id="city" class="form-control ps-5" data-form-type="other"
                                                       value="{{ $aerodrome->city }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Stadt</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-maximize-2 fea icon-sm icons">
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <polyline points="9 21 3 21 3 15"></polyline>
                                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                                </svg>
                                                <input name="state" id="state" class="form-control ps-5" data-form-type="other"
                                                       value="{{ $aerodrome->state }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12 col-6">
                                        <div class="mb-3">
                                            <label for="syslog-account" class="form-label">Zivil</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-map-pin fea icon-sm icons">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                <select name="civil" id="civil" class="form-control ps-5">
                                                    <option value="0" {{ !$aerodrome->civilian ? 'selected' : '' }}>Nein</option>
                                                    <option value="1" {{ $aerodrome->civilian ? 'selected' : '' }}>Ja</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12 col-6">
                                        <div class="mb-3">
                                            <label for="syslog-account" class="form-label">Militär</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-map-pin fea icon-sm icons">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                <select name="military" id="military" class="form-control ps-5">
                                                    <option value="0" {{ !$aerodrome->military ? 'selected' : '' }}>Nein</option>
                                                    <option value="1" {{ $aerodrome->military ? 'selected' : '' }}>Ja</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Latitude</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-maximize-2 fea icon-sm icons">
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <polyline points="9 21 3 21 3 15"></polyline>
                                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                                </svg>
                                                <input name="latitude" id="latitude" class="form-control ps-5" data-form-type="other"
                                                       value="{{ $aerodrome->latitude }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Longitude</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-maximize-2 fea icon-sm icons">
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <polyline points="9 21 3 21 3 15"></polyline>
                                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                                </svg>
                                                <input name="longitude" id="longitude" class="form-control ps-5"
                                                       data-form-type="other" value="{{ $aerodrome->longitude }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Höhe</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-maximize-2 fea icon-sm icons">
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <polyline points="9 21 3 21 3 15"></polyline>
                                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                                </svg>
                                                <input name="elevation" id="elevation" class="form-control ps-5"
                                                       data-form-type="other" value="{{ $aerodrome->elevation }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Abbrechen</button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="updateAerodromeData()">Speichern</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-8 mt-4 order-2">
                {{--
                @include('administration.navigation.aerodromes.partials.runway')

                @include('administration.navigation.aerodromes.partials.stations')

                @include('administration.navigation.aerodromes.partials.charts')
                   --}}
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div>
