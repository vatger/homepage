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
            <div class="col-lg-4 col-md-12 col-sm-12 col-12 mt-4 order-1">
                <div class="card border-0 rounded shadow p-4">
                    <h5 class="mb-0">Übersicht:</h5>
                    <div class="mt-4">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather fea icon-ex-md text-muted me-3">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Name:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->name }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-database fea icon-ex-md text-muted me-3">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">ICAO:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->icao }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-database fea icon-ex-md text-muted me-3">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">IATA:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->iata }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-map fea icon-ex-md text-muted me-3">
                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                <line x1="8" y1="2" x2="8" y2="18"></line>
                                <line x1="16" y1="6" x2="16" y2="22"></line>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Stadt:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->city }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-map fea icon-ex-md text-muted me-3">
                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                <line x1="8" y1="2" x2="8" y2="18"></line>
                                <line x1="16" y1="6" x2="16" y2="22"></line>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Bundesland:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->state }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-check-circle fea icon-ex-md text-muted me-3">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Zivil:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->civilian ? 'Yes' : 'No' }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-check-circle fea icon-ex-md text-muted me-3">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Militär:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->military ? 'Yes' : 'No' }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-map-pin ea icon-ex-md text-muted me-3">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Längengrad:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->latitude }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-map-pin ea icon-ex-md text-muted me-3">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Breitengrad:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->longitude }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-bar-chart-2 fea icon-ex-md text-muted me-3">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            <div class="flex-1">
                                <h6 class="text-primary mb-0">Höhe:</h6>
                                <a href="javascript:void(0)" class="text-muted">{{ $aerodrome->elevation }}</a>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-soft-primary mt-3" id="editAerodromeDataButton" data-bs-toggle="modal"
                                data-bs-target="#editAerodromeDataModal">Daten
                            Bearbeiten
                        </button>

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
                    </div>
                </div>
            </div>
            <!--end col-->

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
