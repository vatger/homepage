<div class="card border-0 shadow rounded sidebar sticky-bar ms-lg-4">
    <div class="card-body p-0 text-center form-control">
        <h4 class="mt-4"><a class="text-primary">@lang('booking.atc.create.title')</a></h4>
        <div class="">
            <div class="row p-3">
                <div class="col-12 mb-3">
                    <label class="form-label" for="date-select">@lang('booking.atc.create.date-text')</label>
                    <div class="form-icon position-relative">
                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                        <input wire:model="selected_date" id="date-select" type="date" class="form-control ps-5" placeholder="01.01.2023" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    @error('selected_date')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="start-time-select">@lang('booking.atc.create.start-time-text')</label>
                    <div class="form-icon position-relative">
                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                        <input wire:model="selected_start_at" name="start_at" id="start-time-select" type="number" class="form-control ps-5" placeholder="1900">
                    </div>
                    @error('selected_start_at')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="end-time-select">@lang('booking.atc.create.end-time-text')</label>
                    <div class="form-icon position-relative">
                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                        <input wire:model="selected_end_at" id="end-time-select" type="number" class="form-control ps-5" placeholder="2130">
                    </div>
                    @error('selected_end_at')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="station-search">@lang('booking.atc.create.station-text')</label>
                    @if(!$selected_station)
                        <div class="form-icon position-relative">
                            <i data-feather="search" class="fea icon-sm icons"></i>
                            <input wire:model.live="station_search" id="station-search" type="text" class="form-control ps-5">
                        </div>
                        @if(!empty($station_search))
                            <table class="table table-hover form-control" wire:loading.class="opacity-5">
                                @foreach($station_suggestions as $s)
                                    <tr wire:click="station_select({{$s->id}})">
                                        <td>{{ $s->ident }}</td>
                                        <td><small>{{ $s->name }}</small></td>
                                        <td><small>{{ $s->fixed_frequency }}</small></td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    @else
                        <table class="table table-hover form-control">
                            <tr wire:click="station_select(-1)">
                                <td>{{ $selected_station->ident }}</td>
                                <td><small>{{ $selected_station->name }}</small></td>
                                <td><small>{{ $selected_station->fixed_frequency }}</small></td>
                                <td><i data-feather="trash" class="fea icon-sm icons"></i></td>
                            </tr>
                        </table>
                    @endif
                    @error('selected_station')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                @if(!$selected_vatger_event)
                    <!--<div class="col-12 mb-3">
                            <div class="form-check">
                                <label class="form-check-label" for="voice-selector">@lang('booking.atc.create.voice-text')</label>
                                -->
                    <input wire:model="selected_voice" class="form-check-input" type="checkbox" id="voice-selector" name="voice" hidden="hidden">
                    <!--</div>
                    </div>-->

                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="event-selector">@lang('booking.atc.create.event-text')</label>
                            <input wire:model="selected_event" class="form-check-input" type="checkbox" id="event-selector">
                        </div>
                    </div>
                @endif
                @if($can_vatger_event)
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="vatger-event-selector">
                                @lang('booking.atc.create.vatger-event-text')
                            </label>
                            <input wire:model="selected_vatger_event" class="form-check-input" type="checkbox" id="vatger-event-selector">
                        </div>
                    </div>
                @endif
                @if(!$selected_vatger_event)
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="training-selector">@lang('booking.atc.create.training-text')</label>
                            <input wire:model="selected_training" class="form-check-input" type="checkbox" id="training-selector">
                        </div>
                    </div>
                @endif
                <div class="col-12 mb-4">
                    <button wire:click="book()" class="btn btn-soft-primary">
                        @lang('booking.atc.create.save-button-text')
                    </button>
                </div>
                @if(!$selected_vatger_event)
                    <div class="col-12">
                        <div class="alert bg-soft-primary fw-medium" role="alert">
                            <a href="{{ route('policies', ['policy_id' => 'pol.booking']) }}" class="alert-link">
                                <i data-feather="info" class="fea fs-5 align-middle me-1"></i>
                                @lang('booking.atc.create.rules-text')
                            </a>
                        </div>
                    </div>
                @endif
                <!--end col-->
            </div>
        </div>
    </div>
</div>
