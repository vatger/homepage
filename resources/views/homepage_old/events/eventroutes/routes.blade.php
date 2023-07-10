@php use Carbon\Carbon; @endphp
@extends('homepage.partials.master')
@section('content')
    <section class="bg-half-170 bg-light d-table w-100 " style='background-image: url("https://vatsim-germany.org/resources/image/142")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">VATGER Touren</h2>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="mybutton buttonround btn-soft-primary p-1 px-3" href="{{ route('eventroutes.info') }}">Infos</a>
                        <a class="mybutton buttonround  p-1 px-3" href="{{ route('eventroutes.routes') }}">Touren</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <div class="row">
        @foreach ($events as $event)
            <div class=" col-4  p-3">
                <div class="card text-center ercon" style="background-color: rgb(10 41 58 / 50%);">
                    <img class="erimage card-img-top" src=" {{ $event->img_url ? $event->img_url : asset('images/vacc_logo.png') }}" alt="Card image cap"
                        style="height: 8%">
                    <div class="ermiddle">
                        <h3 class="erimgtext text">17 Legs vollständig</h3>
                    </div>

                    <div class="progress erprogress">
                        <div class="progress-bar w-50" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->name }}</h5>
                        <div class="row">
                            <div class="card-text col-3">{{ count($event->legs) }} Legs</div>
                            <div class="card-text col-6">
                                @if ($event->flight_rules = 'I')
                                    IFR
                                @else
                                    VFR
                                @endif
                            </div>
                            <div class="card-text col-3">JA</div>
                        </div>
                        <div class="row">
                            <div class="col-2"></div>
                            <button class="btn btn-primary btn-sm text-sm-left buttonround col-2"
                                onclick="showModal('routedetails-{{ $event->id }}')">Details
                            </button>
                            <div class="col-4"></div>
                            @if ($event->joined_by_me)
                                <a href="{{ route('eventroutes.signup', ['eventroute' => $event]) }}">
                                    <button class="btn btn-secondary btn-sm text-sm-right buttonround"></button>
                                </a>
                            @else
                                <button class="btn btn-success btn-sm text-sm-right buttonround col-2">Läuft</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @foreach ($events as $event)
        <div class="modal fade" id="routedetails-{{ $event->id }}" tabindex="-1" aria-labelledby="legModalLabel{{ $event->id }}"
            style="display: none;" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded shadow border-0">

                    <div class="row">
                        <img src=" {{ $event->img_url ? $event->img_url : asset('images/vacc_logo.png') }}" alt="Banner">
                    </div>

                    <div class="row text-center p-3">
                        <div class="col-5">
                            <h4>
                                {{ count($event->legs) }} Legs
                            </h4>
                        </div>
                        <div class="col-3">
                            <h4>
                                @if ($event->flight_rules = 'I')
                                    IFR
                                @else
                                    VFR
                                @endif
                            </h4>
                        </div>
                        <div class="col-4">
                            <h4>
                                Nein
                            </h4>
                        </div>

                    </div>

                    <div class="row ">
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    @foreach ($event->legs as $leg)
                                        <tbody>
                                            <tr class="text-center">
                                                <td>{{ $leg->departure->icao }} <i class="mdi mdi-send"></i> {{ $leg->arrival->icao }}</td>
                                                <td class="border-bottom p-3">
                                                    <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px">Nachreichen
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal"
                                data-form-type="other" style="color:rgb(255,0,0);">
                                Close
                            </button>
                            <button type="button" class="btn btn-sm btn-soft-primary" data-dismiss="modal" data-bs-dismiss="modal"
                                data-form-type="other">Badge anfragen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .daterangepicker {
            color: black !important;
        }
    </style>
    <!--End style for test -->
@endsection

@push('custom-script')
    <script src="{{ asset('js/custom/modal.js') }}"></script>
    <style>
        .bg-half-100 {
            padding: 100px 0;
        }

        .mybutton {
            background-color: #04AA6D;
            border: none;
            color: white;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
        }

        .buttonround {
            border-radius: 12px;
        }

        .erimage {
            opacity: 1;
            display: block;
            width: 100%;
            height: auto;
            transition: .5s ease;
            backface-visibility: hidden;
        }

        .ermiddle {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .ercon:hover .erimage {
            opacity: 0.3;
        }

        .ercon:hover .ermiddle {
            opacity: 1;
        }

        .erprogress {}

        .erimgtext {
            font-size: 25px;
            color: #2e8d2e;
            margin-bottom: 150px;
        }
    </style>
    <script>
        const mod = new Modal(modalTypes.warning);
        let btns = [{
                    {{-- callback: () => {
$("#testcontainer-123").text(mod.getModalUuid())
},
classes: 'btn-soft-danger',
text: 'abc',
disabled: true
}]

mod.bodyContent = `<div id="testcontainer-123">Das ist WIP</div>`

mod.addButtons(btns);

mod.create();

$("img").on('click', () => {
mod.show()
}) --}}
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        function showModal(id) {
            $("#" + id).css('display', 'block');
            $("#" + id).modal('show');
        }
    </script>
@endpush
