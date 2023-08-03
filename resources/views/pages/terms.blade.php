@extends('layouts.master')

@section('content')
    <section class="bg-half-170 bg-light d-table w-100 d-print-none">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title">Datenschutzerklärung</h4>
                        <ul class="list-unstyled mt-4 mb-0">
                            <li class="list-inline-item h6 date text-muted">
                                <span class="text-dark">Last Revised :</span>
                                {{ $gdpr_date->format('jS F Y') }}
                            </li>
                        </ul>
                        <h4 class="title">Impressum</h4>
                        <ul class="list-unstyled mt-4 mb-0">
                            <li class="list-inline-item h6 date text-muted">
                                <span class="text-dark">Last Revised :</span>
                                {{ $imprint_date->format('jS F Y') }}
                            </li>
                        </ul>
                    </div>
                </div>  <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow border-0 rounded">
                        <div class="card-body">
                            <h5 class="card-title">Introduction :</h5>
                            <p class="text-muted">
                                It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One mayspeculate that over th course of time certain letters were added or
                                deleted at various positions within the text.
                            </p>

                            <h5 class="card-title">Policies:</h5>

                            <div class="accordion pt-2">

                                <div class="accordion-item rounded mt-2">
                                    <h2 class="accordion-header" id="gdpr">
                                        <button class="accordion-button border-0 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                            GDPR
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse border-0 collapse show" aria-labelledby="headingOne" data-bs-parent="#gdpr" style="">
                                        <div class="accordion-body">
                                            {!! $gdpr !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item rounded mt-2">
                                    <h2 class="accordion-header" id="imprint">
                                        <button class="accordion-button border-0 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                                                aria-controls="collapseTwo">
                                            Imprint
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse border-0 collapse show" aria-labelledby="headingOne" data-bs-parent="#imprint" style="">
                                        <div class="accordion-body">
                                            {!! $imprint !!}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-3">
                                <a href="javascript:void(0)" class="btn btn-primary mt-2 me-2">Accept</a>
                                <a href="javascript:void(0)" class="btn btn-outline-primary mt-2">Decline</a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section>

@endsection
