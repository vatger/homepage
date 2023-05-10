@extends('homepage.partials.master')
@section('content')
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url("https://vatsim-germany.org/resources/image/142")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">VATGER Touren</h2>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button class="mybutton buttonround p-1 px-3" onclick="infoshow()" id="Infosbutton">Infos</button>
                        <button class="mybutton buttonround btn-soft-primary p-1 px-3" onclick="tourshow()" id="Tourenbutton">Touren</button>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
        <!--end container-->
        </div>
    </section>
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <section class="section pt-0">
        <div class="container">
            <div class="col-12 text-left" id="infos">
                <h1>Regeln</h1>
                <h5>
                    <i class="mdi mdi-arrow-right-bold"></i> &nbsp;Vor Beginn der Tour muss eine Anmeldung zur Tour erfolgen</br>
                    <i class="mdi mdi-earth"></i> &nbsp;Alle Touren und Legs müssen online geflogen werden</br>
                    <i class="mdi mdi-cloud"></i> &nbsp;Touren können entweder IFR/VFR only oder IFR oder VFR geflogen werden</br>
                    <i class="mdi mdi-airplane"></i> &nbsp;Der Flugzeugtyp darf frei gewählt werden, außer anders von der Tour
                    festgelegt</br>
                    <i class="mdi mdi-alphabetical"></i> &nbsp;Das Callsign darf frei gewählt und geändert werden</br>
                    <i class="mdi mdi-pause"></i> &nbsp;die Tour darf pausiert und unterbrochen werden</br>
                    <i class="mdi mdi-clipboard"></i> &nbsp;Ein Flugplan muss immer aufgegeben werden</br>
                    <i class="mdi mdi-numeric"></i> &nbsp;Wenn vorgegeben müssen die Legs in der vorgeschriebenen Reihenfolge geflogen
                    werden</br></br>
                    <a href="mailto:events@vatsim-germany.org" class="mybutton buttonround btn btn-sm" style="font-size: 15px">Email</a>
                </h5>
                <h1>
                    Leg nachreichen
                </h1>
                <h5>
                    Falls das Leg nicht nach 2 Stunden automatisch erkannt wurde, müsst ihr die manuelle Validierung anfragen. Dafür
                    geht ihr einfach auf das Leg, das nicht validiert ist,
                    Und drückt dann auf manuelle Validierung. Füllt nun das geöffnete Dialog aus. Nun müsst ihr nur noch auf Nachtrag
                    anfragen drücken und erledigt. Das Leg wird dann von
                    PR und Events manuell nachvalidiert.
                    <button class="mybutton buttonround btn-soft-primary p-1 px-3" onclick="legnachtragen()" id="Example">Beispiel</button>
                </h5>
            </div>

        </div>
    </section>

    <div class="modal fade" id="addRouteModal" tabindex="-1" aria-labelledby="createRouteModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="routeModal-title">Leg manuell nachreichen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="text-sm-center">
                    <h5>Um ein Leg nachzureichen, klicke auf das betroffene Leg in den Details der tour, du benötigst außerdem einen
                        Link, um dien Leg nachzuweisen, am besten über
                        Stats.vatsim.net oder statsim.net. </br>
                        Dein Leg wird überprüft und dann manuell nachgetragen. Du kannst in der Zwischenzeit die Tour trotzdem
                        weiterführen.</h5>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="route-form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">
                                            <h4>Tour</h4>
                                        </lable></span>
                                        <div class="form-icon position-relative">

                                            <body>Beispiel Tour</body>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">
                                            <h4>Leg</h4>
                                        </lable></span>
                                        <div class="form-icon position-relative">

                                            <body>Leg 3: EDDF <i class="mdi mdi-arrow-right-bold"></i> EDDB</body>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">
                                            <h4>Statsim.net/Stats.net Link</h4>
                                        </lable></span>
                                        <div class="form-icon position-relative">
                                            <input type="text" class="form-control" id="route-link" name="link">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal" data-form-type="other"
                        style="color:rgb(255,0,0);">Abbrechen</button>
                    <button type="button" class="btn btn-sm btn-soft-primary" data-dismiss="modal" data-bs-dismiss="modal"
                        data-form-type="other">Nachreichen</button>
                </div>
            </div>
        </div>
    </div>
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
    </style>
    <script>
        const mod = new Modal(modalTypes.warning);
        let btns = [{
            callback: () => {
                $("#testcontainer-123").text(mod.getModalUuid())
            },
            classes: 'btn-soft-danger',
            text: 'abc',
            disabled: false
        }]

        mod.bodyContent = `<div id="testcontainer-123">this is some example text</div>`

        mod.addButtons(btns);

        mod.create();

        $("img").on('click', () => {
            mod.show()
        })
    </script>
    <script type="text/javascript">
        function tourshow() {
            var x = document.getElementById("touren")
            if (x.style.display === "none") {
                x.style.display = "block";
                var x = document.getElementById("infos");
                x.style.display = "none"
                var v = document.getElementById("Infosbutton");
                var b = document.getElementById("Tourenbutton");
                b.classList.remove('btn-soft-primary');
                v.classList.add('btn-soft-primary');
            }
        }

        function infoshow() {
            var x = document.getElementById("infos");
            if (x.style.display === "none") {
                x.style.display = "block";
                var x = document.getElementById("touren");
                x.style.display = "none"
                var v = document.getElementById("Infosbutton");
                var b = document.getElementById("Tourenbutton");
                v.classList.remove('btn-soft-primary');
                b.classList.add('btn-soft-primary');
            }
        }

        function changebutton(String) {
            var x = document.getElementById("Infosbutton");
            var y = document.getElementById("Tourenbutton");
            if (String = "tour") {


            }

        }
    </script>
    //Jetzt test
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        function legnachtragen() {
            $("#createRoute-modal-button").css('display', 'block');

            $("#addRouteModal").modal('show');
        }
    </script>
@endpush
