<!-- Section Start -->
<section class="section pt-md-5 pt-5">
    <!-- Start Features -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h4 class="title mb-4">@lang('booking.atc.title')</h4>
                    <p class="text-muted para-desc mx-auto mb-0">@lang('booking.atc.text.landing')</p>

                    <div class="alert alert-danger mt-5" role="alert" id="danger-alert-event" style="display: none; width: 60%; margin-left: 20%">
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row pt-2">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr id="bookingCalendarHeader">
                            </tr>
                        </thead>
                        <tbody id="bookingCalendarBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end col-->
        </div>
        <!-- End Features -->
    </div>
</section>
<!--end section-->
<!-- section End -->

@push('custom-script')
    <script>
        function randomHsl() {
            return 'hsl(' + Math.random() * 360 + ', 32%, 16%)';
        }

        $(document).ready(function() {
            let bookingApiEndpoint =
                '{{ route('api.booking.atc', ['start' => \Carbon\Carbon::now()->utc()->format('Y-m-d'),'end' => \Carbon\Carbon::now()->utc()->addDays(4)->format('Y-m-d')]) }}';
            $.ajax({
                type: 'GET',
                url: bookingApiEndpoint,
                success: function(data) {
                    $('#bookingCalendarHeader').html(
                        '<th style="width: 16.66%">Aerodrome / Station</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(1)->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(2)->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(3)->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(4)->format('Y-m-d') }}</th>'
                    );
                    let calendarBody = '';
                    for (i = 0; i < data.length; i++) {
                        calendarBody +=
                            '<tr><td colspan="6" class="text-start" style="background-color: rgba(47, 85, 212, 0.05) !important;">' +
                            data[i].name + '<br>' + data[i].icao + '</td></tr>';
                        for (j = 0; j < data[i].stations.length; j++) {
                            if (data[i].stations[j].bookings !== undefined && data[i].stations[j].bookings
                                .length > 0) {
                                calendarBody += '<tr><td>' + data[i].stations[j].name + '<br>' + data[i]
                                    .stations[j].ident + '</td>';
                                // Evaluate the bookings and move them to correct columns
                                let bookings = data[i].stations[j].bookings;
                                let today = DateTime.now().setZone('utc');
                                for (day = 0; day < 5; day++) {
                                    let date = today.plus({
                                        days: day
                                    });
                                    // Compare bookings to the days and attach to column if required
                                    calendarBody += '<td><div class="row">';
                                    for (x = 0; x < bookings.length; x++) {
                                        let startDay = DateTime.fromISO(bookings[x].starts_at);
                                        if (date.hasSame(startDay, 'day')) {
                                            calendarBody +=
                                                '<div class="col-12" style="background-color: ' +
                                                randomHsl() + '; color: #fff;">' + bookings[x].startTime +
                                                ' - ' + bookings[x].endTime;
                                            @if (Auth::check())
                                                calendarBody += '<br>' + bookings[x].controller.username;
                                            @endif
                                            calendarBody += '</div>';
                                        }
                                    }
                                    calendarBody += '</div></td>';
                                }
                                // Close the row
                                calendarBody += '</tr>';
                            }
                        }
                    }
                    $('#bookingCalendarBody').html(calendarBody);
                }
            });
        });
    </script>
@endpush
