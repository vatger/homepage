@php
    use App\Http\Livewire\Administration\Tech\Apilogtable;
    use Carbon\Carbon;
@endphp
@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Systemadministration</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Systemadministration</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">APIlogs</li>
                    </ul>
                </nav>
            </div>

            <div class="row row-container">
                @livewire(Apilogtable::class)
            </div>
        </div>
    </div>
    {{--
    <div class="modal fade" id="syslog-modal" tabindex="-1" aria-labelledby="LoginForm-title" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Laden...</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-path" class="form-label">Pfad</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="folder" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-path" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-method" class="form-label">Methode</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="cloud" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-method" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Konto</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="user" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-account" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-date" class="form-label">Datum</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-date" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-file" class="form-label">Datei</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="alert-triangle" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-file" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-line" class="form-label">Fehler Zeile</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="alert-triangle" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-line" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-type" class="form-label">Fehlertyp</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="alert-triangle" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-type" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-message" class="form-label">Fehlermeldung</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="message-square" class="fea icon-sm icons"></i>
                                        <textarea disabled name="subject" id="syslog-message" class="form-control ps-5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-stack" class="form-label">Stack-Trace</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="file-text" class="fea icon-sm icons"></i>
                                        <textarea disabled name="subject" id="syslog-stack" class="form-control ps-5" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal">Schließen</button>
                    <button type="button" class="btn btn-sm btn-soft-danger">Entfernen</button>
                </div>
            </div>
        </div>
    </div>
    --}}
@endsection
