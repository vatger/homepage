@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Regionalgruppenanfrage - {{ $regionalgroupRequest->account->id }}</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.regionalgroup.index') }}">Regionalgruppen</a></li>
                        <li class="breadcrumb-item text-capitalize"><a
                                href="{{ route('administration.regionalgroup.view', ['regionalgroup' => $regionalgroup->id]) }}">{{ $regionalgroup->name }}</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Anfragen</li>
                    </ul>
                </nav>
            </div>

            <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-1">
                <div class="col mt-4">
                    <a class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-account fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">Benutzer</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ $regionalgroupRequest->account->username }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->
                <div class="col mt-4">
                    <a class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-account-group fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">Typ</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ Str::ucfirst($regionalgroupRequest->type) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->
                <div class="col mt-4">
                    <a class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-calendar fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">Datum</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ $regionalgroupRequest->created_at->format('d.m.Y') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->

                <div class="col mt-4">
                    <a class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-headset fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">ATC Rating</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ $regionalgroupRequest->account->userData->atcRatingShort }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->

                <div class="col mt-4">
                    <a class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi @if (strtoupper($regionalgroupRequest->account->userData->subdivision_code) == 'GER') mdi-check @else mdi-alert-outline @endif fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">vACC / Subdivision</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ $regionalgroupRequest->account->userData->subdivision_code }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col mt-4">
                    <div class="card shadow border-0 " data-form-type="other">
                        <div class="row row-custom p-4 mb-2">
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-0">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <h6 class="mb-0 text-muted">Grund</h6>
                                </div>
                            </div>
                        </div>

                        <div id="content-container" class="border-top" style="margin-top: -20px">
                            <div class="row p-4 pt-0 table-responsive">
                                <div class="px-3">
                                    <p class="text-dark mb-0 mt-3">{!! $regionalgroupRequest->reason !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col mt-4">
                    <div class="card shadow border-0 " data-form-type="other">
                        <div class="row row-custom p-4 mb-2">
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-0">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <h6 class="mb-0 text-muted">Antwort (Optional)</h6>
                                </div>
                            </div>
                        </div>

                        <div id="content-container">
                            <div class="row row-custom px-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Vorlage</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="clipboard" class="fea icon-sm icons"></i>
                                            <select id="template-select" class="form-control ps-5">
                                                <option value="0" selected>-</option>
                                                @foreach ($regionalgroup->templates as $templ)
                                                    <option value="{{ $templ->id }}">{{ $templ->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->

                                <div class="pb-4">
                                    <textarea name="" id="rejection-reason" class="p-4"></textarea>
                                </div>
                            </div>

                            <div class="col p-4 row row-custom d-flex border-top">
                                <form
                                    action="{{ route('administration.regionalgroup.request.update', ['regionalgroup' => $regionalgroup, 'regionalgroupRequest' => $regionalgroupRequest]) }}"
                                    method="post" class="px-2 pb-lg-0 pb-md-0 pb-sm-2 pb-2"
                                    style="padding-left: 0 !important; display: inline-block; width: auto !important;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-soft-success">Anfrage Annehmen</button>
                                </form>
                                <form
                                    action="{{ route('administration.regionalgroup.request.delete', ['regionalgroup' => $regionalgroup, 'regionalgroupRequest' => $regionalgroupRequest]) }}"
                                    method="post" style="display: inline-block; width: auto !important;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-soft-danger">Anfrage Ablehnen</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>

    <style>
        .row-custom {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

@push('custom-script')
    <script src="https://cdn.tiny.cloud/1/f5oxwmdtukvy1qwch4b3ghpazlyw2rzjxsljjdiis3kedxhg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        const templates = @JSON($regionalgroup->templates);

        // Initialize tinymce using global config
        const tinySettings = config.tinyMce.admin;
        tinySettings.selector = "#rejection-reason";

        tinymce.init(tinySettings);

        $(document).ready(() => {
            $("#template-select").on('change', function() {
                let val = $(this).val();
                let templateData;

                if (val === "0") return;

                for (let i = 0; i < templates.length; i++) {
                    if (templates[i]['id'] === parseInt(val)) {
                        templateData = templates[i];
                        break;
                    }
                }

                console.log(tinymce.activeEditor.setContent(templateData['message']));
            });
        });
    </script>
@endpush
