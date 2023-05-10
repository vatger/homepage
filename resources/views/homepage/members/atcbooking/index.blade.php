@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-primary d-table w-100" id="hero-section"
        style="background: url('{{ asset('images/bookings/booking_' . rand(1, 1) . '.png') }}') center center; background-size: cover">
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">@lang('booking.atc.title')</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                        <li class="breadcrumb-item active">@lang('booking.atc.title')</li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <!--end section-->
    </div>
    <!-- Hero End -->

    <section class="section">
        <div class="container">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-lg-7 col-md-6 mb-4">
                    @include('homepage.members.atcbooking.partials.mybookings')

                    @include('homepage.members.atcbooking.partials.allbookings')
                </div>
                <!-- BLog End -->

                <!-- START SIDEBAR -->
                @include('homepage.members.atcbooking.partials.bookposition')
                <!-- END SIDEBAR -->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>

    <style>
        .daterangepicker {
            color: black !important;
        }
    </style>
@endsection

@push('custom-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(function() {
            $('#date-start-select').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                locale: {
                    format: 'DD.MM.YYYY HH:mm',
                },
                timePicker: true,
            }, function(start, end, label) {
                // console.log(start);
            });

            $('#date-end-select').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                locale: {
                    format: 'DD.MM.YYYY HH:mm',
                },
                timePicker: true,
            }, function(start, end, label) {
                // console.log(start);
            });
        });
    </script>

    <script>
        function formatTimes(originalTimeString) {
            return luxon.DateTime.fromISO(originalTimeString, {
                zone: 'utc'
            }).toLocaleString(luxon.DateTime.TIME_24_SIMPLE)
        }

        function disableInput() {
            $("#date-start-select").prop('disabled', true);
            $("#date-end-select").prop('disabled', true);
        }

        function enableInput() {
            $("#date-start-select").prop('disabled', false);
            $("#date-end-select").prop('disabled', false);
        }

        function updateAtcBookingTable() {
            const actionUrl = '{{ route('api.booking.atc.filter') }}';
            const data = $('#filter-bookings-form').serialize();
            disableInput();

            $.ajax({
                type: 'GET',
                url: actionUrl,
                data: data,
                success: function(data) {
                    let bookings = data;
                    if (bookings.length !== 0)
                        $('#atcBookings').empty();
                    else
                        $("#filter-count").text(`${bookings.length} results matching your filter`).css('display',
                            'block');

                    for (let i = 0; i < bookings.length; i++) {
                        if (bookings[i].divider) {
                            $('#atcBookings').append('<tr><td class="text-center text-muted" colspan="3">' + luxon
                                .DateTime.fromISO(bookings[i].starts_at).toLocaleString(luxon.DateTime
                                    .DATE_FULL) + '</td></tr>');
                        }
                        $('#atcBookings').append('<tr><td class="text-center">' + bookings[i].controller.username +
                            '</td><td class="text-center">' + bookings[i].station.ident +
                            '</td><td class="text-center">' + formatTimes(bookings[i].starts_at) + ' - ' +
                            formatTimes(bookings[i].ends_at) + '</td></tr>');
                    }

                    enableInput();
                }
            });
        }

        $(document).ready(function() {

            const actionPersonalUrl = '{{ route('api.booking.atc.personal') }}';

            $.ajax({
                type: 'GET',
                url: actionPersonalUrl,
                success: function(data) {
                    let bookings = data;
                    if (bookings.length !== 0)
                        $('#atcPersonal').empty();

                    for (let i = 0; i < bookings.length; i++) {
                        if (bookings[i].divider) {
                            $('#atcPersonal').append(
                                '<tr><td class="text-center text-muted" colspan="4">' + luxon.DateTime
                                .fromISO(bookings[i].starts_at).toLocaleString(luxon.DateTime
                                    .DATE_FULL) + '</td></tr>');
                        }
                        let editUrl = '{{ route('controllers.booking.edit', 'booking_id') }}';
                        editUrl = editUrl.replace('booking_id', bookings[i].id);
                        let deleteUrl = '{{ route('controllers.booking.delete', 'booking_id') }}';
                        deleteUrl = deleteUrl.replace('booking_id', bookings[i].id);
                        $('#atcPersonal').append(
                            `<tr>
                        <td class="text-center"> ${bookings[i].station.ident} </td>
                        <td class="text-center"> ${formatTimes(bookings[i].starts_at)} - ${formatTimes(bookings[i].ends_at)} </td>
                        <td class="text-center"><form action="${deleteUrl}" method="POST"><input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="_method" value="DELETE">
                    <div class="btn-group">
                        <a href="${editUrl}" class="btn btn-sm btn-soft-primary mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <button type="submit" class="btn btn-sm btn-soft-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>
                        </div>
                    </form>

                    </tr>`);
                    }


                }
            });

            // Load upcoming bookings once for the beginning
            let now = new Date();
            let end = new Date();
            end.setDate(now.getDate() + 7);
            $('#date-start-select').data('daterangepicker').setStartDate(now);
            $('#date-end-select').data('daterangepicker').setStartDate(end);
            updateAtcBookingTable();

            // When the filter button is clicked. Find bookings for given timeframe
            $('#date-start-select').change(function() {
                updateAtcBookingTable();
            });

            $("#date-end-select").change(function() {
                updateAtcBookingTable();
            });


        });
    </script>
@endpush
