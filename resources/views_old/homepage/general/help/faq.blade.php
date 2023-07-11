@extends('homepage.partials.master')

@section('content')
    <section class="bg-half-170 d-table w-100" style="background-image: url('{{ asset('images/help/help_' . rand(1, 1) . '.png') }}');">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading title-heading">
                        <h4 class="title text-white title-dark mb-4"> @lang('faq.title') </h4>
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
                        <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--end container-->
    </section>

    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-5 col-12 d-none d-md-block">
                    <div class="rounded-md shadow p-4 sticky-bar">
                        <ul class="list-unstyled sidebar-nav mb-0 py-2" id="navmenu-nav">
                            <li class="mb-3 navbar-item"><a href="#general" class="mouse-down h6 text-dark">@lang('faq.section-general.title')</a></li>
                            <li class="mb-3 navbar-item"><a href="#general" class="mouse-down h6 text-dark">@lang('faq.technical-general.title')</a></li>
                        </ul>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-8 col-md-7 col-12">
                    <div class="section-title" id="general">
                        <h4>@lang('faq.section-general.title')</h4>
                    </div>

                    <div class="accordion mt-2 pt-2" id="general-section">
                        @for ($i = 0; $i < 2; $i++)
                            <div class="accordion-item rounded shadow bg-white mt-2">
                                <h2 class="accordion-header" id="{{ 'accordion-header-' . $i }}">
                                    <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#{{ 'collapse_s1_' . $i }}" aria-expanded="false" aria-controls="{{ 'collapse_s1_' . $i }}">
                                        @lang('faq.section-general.content.' . $i . '.question')
                                    </button>
                                </h2>
                                <div id="{{ 'collapse_s1_' . $i }}" class="accordion-collapse border-0 collapse"
                                    aria-labelledby="{{ 'collapse_s1_' . $i }}" data-bs-parent="#general-section" style="">
                                    <div class="accordion-body text-muted">
                                        <p class="text-muted">@lang('faq.section-general.content.' . $i . '.answer')</p>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="section-title mt-5" id="technical">
                        <h4>@lang('faq.technical-general.title')</h4>
                    </div>

                    <div class="accordion mt-2 pt-2" id="technical-section">
                        @for ($i = 0; $i < 2; $i++)
                            <div class="accordion-item rounded shadow bg-white mt-2">
                                <h2 class="accordion-header" id="{{ 'accordion-header-' . $i }}">
                                    <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#{{ 'collapse_s2_' . $i }}" aria-expanded="false" aria-controls="{{ 'collapse_s2_' . $i }}">
                                        @lang('faq.section-technical.content.' . $i . '.question')
                                    </button>
                                </h2>
                                <div id="{{ 'collapse_s2_' . $i }}" class="accordion-collapse border-0 collapse"
                                    aria-labelledby="{{ 'collapse_s2_' . $i }}" data-bs-parent="#technical-section" style="">
                                    <div class="accordion-body text-muted">
                                        <p class="text-muted">@lang('faq.section-technical.content.' . $i . '.answer')</p>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
@endsection
