@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $aerodrome->name }}</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Navigation</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation.aerodromes') }}">Flugplatzverwaltung</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $aerodrome->icao }}</li>
                    </ul>
                </nav>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="bg-primary card border-0 shadow rounded overflow-hidden p-4" id="banner-image-container"
                        style="background: url('{{ asset('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg') }}') center center; background-size: cover;">
                        <div class="loader-show" id="img-loader" style="position:absolute; width: 100%; height: 100%; top: 0; left: 0; display: none">
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-8">
                                <div class="text-center bg-white p-4 rounded">
                                    <h5 class="mt-3 mb-0">{{ $aerodrome->name }}</h5>
                                    <small class="text-muted">{{ $aerodrome->icao }} @if ($aerodrome->iata)
                                            | {{ $aerodrome->iata }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

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
                                Bearbeiten</button>

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
                    @include('administration.navigation.aerodromes.partials.runway')

                    @include('administration.navigation.aerodromes.partials.stations')

                    @include('administration.navigation.aerodromes.partials.charts')
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>

    <style>
        .row-custom {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>

    <style>
        @keyframes load {
            0% {
                margin-left: -100%;
            }

            100% {
                margin-left: 100%;
            }
        }

        .loader-show {
            transition: opacity 0.5s;
        }

        .loader-show::before {
            content: '';
            display: block;
            height: 100%;
            min-height: 200px;
            width: 100%;
            @auth @if (\Auth::user()->settings->dark_mode)background: linear-gradient(to right, transparent 0%, rgb(64 64 64 / 39%) 50%, transparent 100%);
        @else background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
            @endif@else background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
        @endauth animation: 1.5s ease-in-out 0s infinite normal none running;
        animation-name: load;
    }
</style>
@endsection

@push('custom-script')
<script>
    function updateAerodromeData() {
        let formData = new FormData();
        formData = $('#editAerodromeDataForm').serialize();

        axios.post('{{ route('administration.navigation.aerodromes.update', ['aerodrome' => $aerodrome]) }}', formData)
            .then(res => {
                if (res.data) location.reload();
            }).catch(function(error) {
                console.log(error.data.toJSON());
            });
    }

    $(document).ready(function() {
        // $("#assignChartForm").on("submit", function(event) {
        //     event.preventDefault();

        //     let formVars = $(this).serialize();
        //     $.ajax({
        //         data: formVars,
        //         type: 'PATCH',
        //         url: '{{ route('administration.navigation.aerodromes.update', ['aerodrome' => $aerodrome]) }}',
        //         success: function (data)
        //         {
        //             new Noty({
        //                 text: '@lang('profile.profile.notifications.settings-saved-successfully')',
        //                 progressBar: true,
        //                 modal: false,
        //                 maxVisible: 1,
        //                 timeout: 5000,
        //                 layout: 'topRight',
        //                 type: 'success',
        //                 callbacks: {
        //                         onClose: function () {
        //                             location.reload();
        //                         }
        //                     }
        //             }).show();
        //         }
        //     });
        // });

        $("#upload-image-form").on('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            $("#banner-image-container").css('background', '').attr('style',
                'background-color: white !important');
            $("#img-loader").css('display', 'block');

            $.ajax({
                method: 'POST',
                url: '{{ route('administration.navigation.aerodromes.update', ['aerodrome' => $aerodrome]) }}',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                dataType: 'JSON',
                success: (data) => {
                    // We need to add a cache-buster at the end to force image reload
                    $("#banner-image-container").attr('style',
                        `background: url('{{ asset('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg?' . \Carbon\Carbon::now()->timestamp) }}') center center; background-size: cover; background-color: white !important`
                    )

                    $("#img-loader").css('display', 'none');
                },
                error: (data) => {
                    console.log(data['responseJSON']['message']);
                    showNoty(data['responseJSON']['message'], 'error');
                    $("#banner-image-container").attr('style',
                        `background: url('{{ asset('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg') }}') center center; background-size: cover;`
                    );
                    $("#img-loader").css('display', 'none');
                }
            });
        });
    });
</script>
@endpush
