@extends('homepage.partials.master')

@section('content')

    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100" style="background-image: url('{{ asset('images/help/help_' . rand(1, 1) . '.png') }}');">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading title-heading">
                        <h4 class="title text-white title-dark mb-4"> Partner </h4>
                        <p class="text-white-50 para-desc mx-auto mb-0">@lang('faq.subtitle')</p>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">VATGER</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Partner</li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--end container-->
    </section>
    <!-- Hero End -->

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card features feature-primary feature-full-bg rounded p-4 bg-light position-relative overflow-hidden border-0">
                        <span class="h1 icon-color">
                            <i class="uil uil-briefcase"></i>
                        </span>
                        <div class="card-body p-0 content">
                            <h5>Our Vision</h5>
                            <p class="para text-muted mb-0">It is a long established fact that a reader will be of a page reader will be of at
                                its layout.</p>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-4 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card features feature-primary feature-full-bg rounded p-4 bg-light position-relative overflow-hidden border-0">
                        <span class="h1 icon-color">
                            <i class="uil uil-rocket"></i>
                        </span>
                        <div class="card-body p-0 content">
                            <h5>Our Mission</h5>
                            <p class="para text-muted mb-0">It is a long established fact that a reader will be of a page reader will be of at
                                its layout.</p>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-4 col-md-6 col-12 mt-4 mt-lg-0 pt-2 pt-lg-0">
                    <div class="card features feature-primary feature-full-bg rounded p-4 bg-light position-relative overflow-hidden border-0">
                        <span class="h1 icon-color">
                            <i class="uil uil-crosshairs"></i>
                        </span>
                        <div class="card-body p-0 content">
                            <h5>Our Goal</h5>
                            <p class="para text-muted mb-0">It is a long established fact that a reader will be of a page reader will be of at
                                its layout.</p>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->

        @if ($partners->count() == 0)
            <div class="container mt-5">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="alert alert-danger mt-3 text-center" role="alert" id="error-message">Wir haben derzeit keine Partner,
                            die wir euch zeigen können. Schaut gerne später noch mal nach.</div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
        @else
            @foreach ($partners as $p)
                @if ($loop->index % 2 == 0)
                    <div class="container mt-100 mt-60 @if ($loop->index > 0) border-top pt-4 @endif">
                        <div class="row align-items-center">
                            <div class="col-lg-5 col-md-6 mt-4 pt-2">
                                <img src="{{ $p->logo_url }}" style="max-width: 100%">
                            </div>
                            <!--end col-->

                            <div class="col-lg-7 col-md-6 mt-4 pt-2">
                                <div class="section-title ms-lg-5">
                                    <h4 class="title mb-4">{{ $p->name }}</h4>
                                    <div class="text-muted">{!! $p->description !!}</div>

                                    <div class="mt-4">
                                        <a href="{{ $p->link_url }}" class="btn btn-sm btn-soft-primary">Mehr lesen <i
                                                class="uil uil-angle-right-b align-middle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end container-->
                @else
                    <div class="container mt-100 mt-60 border-top pb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-5 col-md-6 mt-4 pt-2 order-1 order-md-2">
                                <img src="{{ $p->logo_url }}" style="max-width: 100%">
                            </div>
                            <!--end col-->

                            <div class="col-lg-7 col-md-6 mt-4 pt-2 order-2 order-md-1">
                                <div class="section-title me-lg-5">
                                    <h4 class="title mb-4">{{ $p->name }}</h4>
                                    <div class="text-muted">{!! $p->description !!}</div>

                                    <div class="mt-4">
                                        <a href="{{ $p->link_url }}" class="btn btn-sm btn-soft-primary">Mehr lesen <i
                                                class="uil uil-angle-right-b align-middle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end container-->
                @endif
            @endforeach
        @endif
    </section>
    <!-- section End -->
@endsection
