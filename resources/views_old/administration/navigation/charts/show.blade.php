@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $chart->name }}</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Navigation</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation.charts') }}">Charts</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $chart->name }}</li>
                    </ul>
                </nav>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="bg-primary card border-0 shadow rounded overflow-hidden p-4"
                         style="background: url('{{ asset('images/splash/instruments.jpg') }}') center center; background-size: cover;">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-8">
                                <div class="text-center bg-white p-4 rounded">
                                    <h5 class="mt-3 mb-0">{{ $chart->name }}</h5>
                                    <small class="text-muted">
                                        @if ($is_dfs)
                                            DFS - {{ $chart->type }}
                                        @else
                                            {{ $chart->type }}
                                        @endif
                                    </small>
                                    <small class="text-muted">{{ $chart->airac }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            @if ($is_dfs)
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end align-content-end">
                            <span class="badge-danger">
                                Can not delete DFS-AIP Chart
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end align-content-end">
                            <form action="{{ route('administration.navigation.charts.delete', ['chart' => $chart]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <label class="form-label">Removing a chart will only clear the database record and not delete the linked
                                    file.</label>
                                <button type="submit" class="btn btn-soft-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mt-4">
                <div class="col-12">
                    @if ($is_dfs)
                        <img id="dfsImage" src="{{ $imgSource }}" />
                    @else
                        <iframe src="{{ $chart->href }}?token={{ \App\Libraries\ChartAuthorization::grantAccessToken($chart) }}"
                                frameborder="0" style="width: 100%; height: 100%; min-height: 550px;"></iframe>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@push('custom-script')
    <script>
      $("#dfsImage").on("contextmenu", (e) => {
        e.preventDefault();
      });
    </script>
@endpush
