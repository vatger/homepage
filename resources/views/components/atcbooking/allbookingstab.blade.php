<div class="card blog blog-detail atc-bookings-panel">
    <div class="card-body content text-center">
        <h4 class="mb-2"><i class="text-primary me-1 text-center"></i><a class="text-primary">@lang('booking.atc.all.title')</a></h4>
        <div class="row justify-content-center mb-1">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <p class="text-muted para-desc mx-auto mb-0">@lang('booking.atc.all.text')</p>
                </div>
            </div>
        </div>
        <div class="w-100">
            <div class="accordion">
                <div class="accordion-item atc-booking-filter">
                    <h2 class="accordion-header">
                        <button wire:ignore.self class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-header-1" aria-expanded="false" aria-controls="accordion-header-1">
                            @lang('booking.atc.all.filter')
                        </button>
                    </h2>
                    <div wire:ignore.self id="accordion-header-1" class="accordion-collapse border-0 collapse" aria-labelledby="accordion-header-1"
                         data-bs-parent="#general-section" style="">
                        <div class="accordion-body text-muted">
                            <form id="filter-bookings-form">
                                <div class="row" id="search-container">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('booking.atc.search.from-text')</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="calendar" class="fea icon-sm icons"></i>
                                                @if(!$selected_my_bookings)
                                                    <input wire:model.live="selected_start_at" id="date-end-select" class="form-control ps-5" type="date"
                                                           min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                                @else
                                                    <input class="form-control ps-5" type="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" disabled>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('booking.atc.search.till-text')</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="calendar" class="fea icon-sm icons"></i>
                                                @if(!$selected_my_bookings)
                                                    <input wire:model.live="selected_end_at" id="date-end-select" class="form-control ps-5" type="date"
                                                           min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                                @else
                                                    <input class="form-control ps-5" disabled>
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="search-container">
                                    {{--
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Sort by Regionalgroup</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="database" class="fea icon-sm icons"></i>
                                                <select name="report-rg" type="text" class="form-control ps-5">
                                                    <option value="-1">-</option>

                                                    @foreach (\App\Models\Navigation\Fir::all() as $fir)
                                                        <option value="{{ $fir->id }}">
                                                            {{ $fir->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    --}}
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('booking.atc.all.search')</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="map-pin" class="fea icon-sm icons"></i>
                                                @if(!$selected_my_bookings)
                                                    <input wire:model.live="selected_search" type="text" class="form-control ps-5" placeholder="EDDF, Langen Radar, 119.905...">
                                                @else
                                                    <input class="form-control ps-5" disabled>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-check-label">@lang('booking.atc.all.quick-select')</label>
                                            <div class="form-control ps-5">
                                                <div class="form-check">
                                                    <input wire:model.live="selected_my_bookings" class="form-check-input" type="checkbox" />
                                                    <label class="form-check-label">@lang('booking.atc.all.my-bookings')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table class="table mb-0 table-center">
                    <thead>
                    <tr>
                        <th wire:click="sortBy('controller_id')" style="width: 33%" scope="col" class="border-bottom text-center">
                            @lang('booking.atc.all.controller')
                            <i data-feather="{{ $this->getSortIconClasses('controller_id') }}"></i>
                        </th>
                        <th {{--wire:click="sortBy('station_id')"--}} style="width: 33%" scope="col" class="border-bottom text-center">
                            @lang('booking.atc.all.position')
                        </th>
                        <th wire:click="sortBy('starts_at')" style="width: 33%" scope="col" class="border-bottom text-center">
                            @lang('booking.atc.all.timeframe')
                            <i data-feather="{{ $this->getSortIconClasses('starts_at') }}"></i>
                        </th>
                        <th scope="col" class="border-bottom text-center">

                        </th>
                    </tr>
                    </thead>
                    <tbody wire:poll.5s>
                    @foreach($filtered_bookings as $booking)
                        <tr>
                            <td>
                                {{ $booking->controller->username }}
                                <small>({{ $booking->controller_id }})</small>
                                @if($booking->training)
                                    <span class="badge bg-primary">T</span>
                                @endif
                                @if($booking->event)
                                    <span class="badge bg-info">E</span>
                                @endif
                                @if($booking->vatger_event)
                                    <span class="badge bg-danger">E</span>
                                @endif
                            </td>
                            <td>{{ $booking->station->ident }}</td>
                            <td>
                                {{ $booking->starts_at->format('d.m.') }}
                                {{ $booking->starts_at->format('H:i') }}-{{ $booking->ends_at->format('H:i') }}z
                            </td>
                            <td>
                                @if(!$booking->vatsim_booking_id)
                                    <button class="btn badge bg-warning" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="{{ __('booking.atc.all.external-warning') }}">
                                        <i data-feather="info" class="fea icon-sm"></i>
                                    </button>
                                @endif
                                @if($booking->controller_id == \Illuminate\Support\Facades\Auth::user()?->id)
                                    <button wire:click="delete({{$booking->id}})"
                                            wire:confirm="{{ __('booking.atc.all.delete-confirm') }}"
                                            class="btn badge bg-danger mt-1">
                                        <i data-feather="trash" class="fea icon-sm"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $filtered_bookings->links() }}
            </div>
        </div>
    </div>
</div>
