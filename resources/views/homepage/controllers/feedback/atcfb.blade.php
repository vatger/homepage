@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url(" @yield('hero-img-src', asset('images/getstarted/getstarted_1.png')) ")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">ATC Feedback</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">VATGER</a></li>
                        @lang('controller.feedback.breadcrumb.0')
                    </ul>
                </nav>
            </div>
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- Hero End -->

    <!-- Shape Start -->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!--Shape End-->

    <!-- Blog STart -->
    <section class="section">
        <div class="container">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-lg-12 col-md-6">
                    <div class="card blog blog-detail border-0 shadow rounded">
                        <div class="card-body content">
                            <div class="row pb-2 border-bottom">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('controller.feedback.your-id-text')</label>
                                        <div class="form-icon position-relative">
                                            <p class="text-muted">{{ \Illuminate\Support\Facades\Auth::user()->id }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('controller.feedback.your-name-text')</label>
                                        <div class="form-icon position-relative">
                                            <p class="text-muted">{{ \Illuminate\Support\Facades\Auth::user()->username }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>

                            @include('homepage.controllers.feedback.partials.create')

                            <small class="text-muted">Fields marked with <span class="text-danger">*</span> are required. <br> For feedback
                                regarding multiple poeple (e.g. during an event) please only enter the date and your feedback message.</small>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- Blog End -->

    <div class="modal fade" id="confirmation-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-body p-0">
                    <div class="container-fluid px-0">
                        <div class="row align-items-end g-0">
                            <div class="col-lg-6 col-md-5">
                                <form class="login-form p-4">
                                    <h5>Zusammenfassung</h5>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">User-ID (CID)</label>
                                                <div class="form-icon position-relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <input disabled id="modal-user-cid" class="form-control ps-5">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">ATC-Position</label>
                                                <div class="form-icon position-relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <input disabled id="modal-subject" class="form-control ps-5">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <div class="form-icon position-relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <input disabled id="modal-name" class="form-control ps-5">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Datum</label>
                                                <div class="form-icon position-relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <input disabled id="modal-report-date" class="form-control ps-5">
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                            <!--end col-->

                            <div class="col-lg-6 col-md-7 p-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3" id="modal-error-container">

                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-soft-primary w-100">Submit </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end container-->
                </div>
            </div>
        </div>
    </div>

    <style>
        .daterangepicker {
            color: black !important;
        }
    </style>
@endsection

@push('custom-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/f5oxwmdtukvy1qwch4b3ghpazlyw2rzjxsljjdiis3kedxhg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="{{ asset('/js/custom/controllers/feedback/atcfb.js') }}"></script>
@endpush
