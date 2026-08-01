<section class="surface p-5 sm:p-7">
    <header class="text-center">
        <h2 class="text-2xl font-bold text-primary-900 dark:text-secondary-50">@lang('booking.atc.all.title')</h2>
        <p class="mt-2 text-secondary-500 dark:text-secondary-300">@lang('booking.atc.all.text')</p>
    </header>

    <details wire:ignore.self data-persist-details="atc-booking-filters"
             class="mt-8 rounded-2xl border border-secondary-200 bg-secondary-50 dark:border-secondary-700 dark:bg-secondary-900/40">
        <summary class="cursor-pointer px-5 py-4 font-semibold text-primary-900 dark:text-secondary-50">@lang('booking.atc.all.filter')</summary>
        <form id="filter-bookings-form" class="grid gap-5 border-t border-secondary-200 p-5 md:grid-cols-2 dark:border-secondary-700">
            <div>
                <label class="form-label" for="date-start-select">@lang('booking.atc.search.from-text')</label>
                @if(!$selected_my_bookings)
                    <input wire:model.live="selected_start_at" id="date-start-select" class="form-control" type="date" min="{{ now()->format('Y-m-d') }}">
                @else
                    <input class="form-control" type="date" value="{{ now()->format('Y-m-d') }}" disabled>
                @endif
            </div>
            <div>
                <label class="form-label" for="date-end-select">@lang('booking.atc.search.till-text')</label>
                @if(!$selected_my_bookings)
                    <input wire:model.live="selected_end_at" id="date-end-select" class="form-control" type="date" min="{{ now()->format('Y-m-d') }}">
                @else
                    <input class="form-control" disabled>
                @endif
            </div>
            <div>
                <label class="form-label" for="booking-search">@lang('booking.atc.all.search')</label>
                <input wire:model.live="selected_search" id="booking-search" type="search" class="form-control"
                       placeholder="EDDF, Langen Radar, 119.905..." @disabled($selected_my_bookings)>
            </div>
            <div>
                <span class="form-label">@lang('booking.atc.all.quick-select')</span>
                <label class="flex min-h-11 items-center gap-3 rounded-lg border border-secondary-300 bg-white px-3 dark:border-secondary-600 dark:bg-secondary-800">
                    <input wire:model.live="selected_my_bookings" class="form-check-input" type="checkbox">
                    <span class="text-sm font-medium">@lang('booking.atc.all.my-bookings')</span>
                </label>
            </div>
        </form>
    </details>

    <div class="table-responsive mt-8 rounded-xl border border-secondary-200 dark:border-secondary-700">
        <table class="table">
            <thead>
                <tr>
                    <th wire:click="sortBy('controller_id')" class="cursor-pointer">@lang('booking.atc.all.controller') <i data-feather="{{ $this->getSortIconClasses('controller_id') }}" class="inline size-4"></i></th>
                    <th>@lang('booking.atc.all.position')</th>
                    <th wire:click="sortBy('starts_at')" class="cursor-pointer">@lang('booking.atc.all.timeframe') <i data-feather="{{ $this->getSortIconClasses('starts_at') }}" class="inline size-4"></i></th>
                    <th><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody wire:poll.5s>
                @forelse($filtered_bookings as $booking)
                    <tr>
                        <td>
                            <span class="font-medium">{{ $booking->controller->username }}</span>
                            <small class="text-secondary-500">({{ $booking->controller_id }})</small>
                            @if($booking->training)<span class="badge">T</span>@endif
                            @if($booking->event)<span class="badge">E</span>@endif
                            @if($booking->vatger_event)<span class="badge bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300">E</span>@endif
                        </td>
                        <td>{{ $booking->station->ident }}</td>
                        <td>{{ $booking->starts_at->format('d.m. H:i') }}–{{ $booking->ends_at->format('H:i') }}z</td>
                        <td class="whitespace-nowrap text-right">
                            @if(!$booking->vatsim_booking_id)
                                <span class="inline-flex size-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700" title="{{ __('booking.atc.all.external-warning') }}"><i data-feather="info" class="size-4"></i></span>
                            @endif
                            @if($booking->controller_id == Auth::user()?->id)
                                <button wire:click="delete({{ $booking->id }})" wire:confirm="{{ __('booking.atc.all.delete-confirm') }}"
                                        class="inline-flex size-9 items-center justify-center rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-950 dark:text-red-300">
                                    <i data-feather="trash" class="size-4"></i><span class="sr-only">Delete</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-10 text-center text-secondary-500">@lang('pages.common.no-results')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $filtered_bookings->links() }}</div>
</section>
