<div class="col-lg-5 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
    <div class="card border-0 shadow rounded sidebar sticky-bar ms-lg-4">
        <div class="card-body p-0 text-center">
            <h4 class="mt-4"><i class="text-primary me-1 text-center"></i><a class="text-primary">Book Position</a></h4>
            <form id="create-booking-form" class="text-start" action="{{ route('controllers.booking.store') }}" method="POST">
                @csrf
                <div class="row p-3" id="create-container">
                    <div class="col-12 mb-3">
                        <label class="form-label">@lang('booking.atc.create.date-text')</label>
                        <div class="form-icon position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-book fea icon-sm icons">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <input name="date" id="date-select" type="text" class="form-control ps-5"
                                value="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">@lang('booking.atc.create.start-time-text')</label>
                        <div class="form-icon position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-calendar fea icon-sm icons">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <input name="start_at" id="start-time-select" type="text" class="form-control ps-5" value="">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">@lang('booking.atc.create.end-time-text')</label>
                        <div class="form-icon position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-calendar fea icon-sm icons">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <input name="end_at" id="end-time-select" type="text" class="form-control ps-5" value="">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-check p-0">
                            <label class="form-check-label" for="position-selector">@lang('booking.atc.create.station-text')</label>
                            <select class="form-control mt-2" aria-label="Station selector" id="position-selector" name="position">
                                @foreach (\App\Models\Navigation\Station::bookable()->get() as $s)
                                    <option value="{{ $s->ident }}">{{ $s->ident }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="voice-selector">@lang('booking.atc.create.voice-text')</label>
                            <input class="form-check-input" type="checkbox" value="true" id="voice-selector" name="voice">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="event-selector">@lang('booking.atc.create.event-text')</label>
                            <input class="form-check-input" type="checkbox" value="true" id="event-selector" name="event">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="training-selector">@lang('booking.atc.create.training-text')</label>
                            <input class="form-check-input" type="checkbox" value="true" id="training-selector" name="training">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" id="save-booking-button" class="btn btn-soft-primary" value="@lang('booking.atc.create.save-button-text')">
                    </div>
                    <!--end col-->
                </div>
            </form>
        </div>
    </div>
</div>
<!--end col-->

@push('custom-script')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(function() {

            $('#date-select').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker24Hour: true,
                locale: {
                    format: 'DD.MM.YYYY',
                },
                timePicker: false,
            }, function(start, end, label) {
                console.log(start);
            });

            $("#start-time-select").daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,
                timePickerSeconds: false,
                locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', (ev, picker) => {
                picker.container.find(".calendar-table").hide();
            });

            $("#end-time-select").daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                timePickerSeconds: false,
                locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', (ev, picker) => {
                picker.container.find(".calendar-table").hide();
            });
        });
    </script>
@endpush
