@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Navigation Charts</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Navigation</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation.charts') }}">Charts</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Creation</li>
                    </ul>
                </nav>
            </div>

            <div class="row row-cols-1">
                <div class="col mt-4">
                    <div class="d-flex justify-content-between p-4 shadow rounded-top">
                        <h6 class="fw-bold mb-0">Charts - Creation</h6>
                    </div>
                    <div class="row p-4 shadow rounded-bottom">
                        <div class="col-12">
                            <form action="{{ route('administration.navigation.charts.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Airac</label>
                                    <input type="text" name="airac" id="airac" class="form-control" value="{{ date('ym') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="The name of the chart">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link</label>
                                    <input type="text" name="href" id="link" class="form-control" placeholder="The FULL url to the chart!">
                                    {{-- https://nav.vatsim-germany.org/files/vfr/charts/public/EDDV_VAC.pdf --}}
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="chart-fri-selector">Chart flight rule indicator. (VFR charts can only be
                                        seen by authenticated users!)</label>
                                    <select class="form-select form-control" id="chart-fri-selector" name="fri">
                                        <option value="ifr">IFR</option>
                                        <option value="vfr">VFR</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="chart-type-selector">Chart type indicator.</label>
                                    <select class="form-select form-control" id="chart-type-selector" name="type">
                                        <option value="aoi">AOI</option>
                                        <option value="afc">AFC</option>
                                        <option value="agc">AGC</option>
                                        <option value="apc">APC</option>
                                        <option value="sid">SID</option>
                                        <option value="star">STAR</option>
                                        <option value="iac">IAC</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="published" value="0" />
                                        <input class="form-check-input" type="checkbox" value="1" id="published-selector" name="published">
                                        <label class="form-check-label" for="published-selector">Is this chart published?</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="public_available" value="0" />
                                        <input class="form-check-input" type="checkbox" value="1" id="public-selector" name="public_available">
                                        <label class="form-check-label" for="public-selector">Is this chart public?</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-soft-primary" type="submit">Create chart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
