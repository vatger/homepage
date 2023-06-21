@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url("{{ asset('images/hero-banners/hero_1.png') }}")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">@lang('first-steps.become-atco.title')</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('getting-started') }}">Getting Started</a></li>
                        <li class="breadcrumb-item">ATC</li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('first-steps.become-atco.breadcrumb')</li>
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
                <div class="col-lg-8 col-md-6">
                    <div class="card blog blog-detail border-0 shadow rounded">
                        <div class="card-body content">
                            <h5>@lang('first-steps.become-atco.title')</h5>
                            <div id="getting-started-container">
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="accordion mt-2 pt-2">
                                        <div class="accordion-item rounded shadow bg-white mt-2">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button border-0 bg-light @if ($i != 0) collapsed @endif"
                                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $i + 1 }}"
                                                    data-id="{{ $i + 1 }}"
                                                    aria-expanded="@if ($i == 0) true @else false @endif"
                                                    aria-controls="collapse-{{ $i }}">
                                                    {{ $i + 1 }}. @lang('first-steps.become-atco.content.' . $i . '.title')
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $i + 1 }}"
                                                class="accordion-collapse border-0 collapse @if ($i == 0) show @endif"
                                                aria-labelledby="collapse-{{ $i + 1 }}" data-bs-parent="#getting-started-container" style="">
                                                <div class="accordion-body text-muted">
                                                    @lang('first-steps.become-atco.content.' . $i . '.content.0')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- START SIDEBAR -->
                <div class="col-lg-4 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card border-0 sidebar sticky-bar ms-lg-4">
                        <div class="card-body p-0">
                            <!-- RECENT POST -->
                            <div class="widget">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    @lang('general.blog.helpful-links')
                                </span>

                                <div class="mt-2">
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="flex-1">
                                            <a href="#" target="_blank">
                                                <button type="button" class="btn btn-soft-primary"
                                                    style="width: 90%; margin-left: 5%">@lang('general.faq')</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RECENT POST -->

                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    Navigation
                                </span>

                                @for ($i = 0; $i < 5; $i++)
                                    <div class="mt-2">
                                        <div class="d-flex align-items-center mt-3">
                                            <div class="flex-1">
                                                <a>
                                                    <button type="button" class="btn btn-soft-primary navigation-button"
                                                        id="navigation-{{ $i + 1 }}" style="width: 90%; margin-left: 5%"
                                                        data-id="{{ $i + 1 }}">@lang('first-steps.become-atco.content.' . $i . '.title')</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
                <!-- END SIDEBAR -->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- Blog End -->
@endsection

@push('custom-script')
    <script>
        function loadPage() {
            $(document).ready(() => {
                // Required, since the attr. gets overriden on load
                $("#navigation-1").attr('disabled', true);
            })

            $(".navigation-button").click(function() {
                let buttonId = $(this).data('id');

                enableAllButtons();

                $(`#collapse-${buttonId}`).collapse('toggle');
                $(this).attr('disabled', true);
            });

            $(".accordion-button").click(function() {
                let accordionId = $(this).data('id');

                enableAllButtons();

                if (!$(this).hasClass('collapsed'))
                    $(`#navigation-${accordionId}`).attr('disabled', true);
            });

            function enableAllButtons() {
                for (let i = 0; i < 6; i++) {
                    $(`#navigation-${i}`).attr('disabled', false);
                }
            }
        }

        deferLoading(loadPage);
    </script>
@endpush
