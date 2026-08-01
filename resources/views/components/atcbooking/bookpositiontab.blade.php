<section class="surface p-5 sm:p-7 lg:sticky lg:top-24">
    <h2 class="text-center text-2xl font-bold text-primary-900 dark:text-secondary-50">@lang('booking.atc.create.title')</h2>

    <div class="mt-7 grid gap-5">
        <div>
            <label class="form-label" for="date-select">@lang('booking.atc.create.date-text')</label>
            <input wire:model="selected_date" id="date-select" type="date" class="form-control" min="{{ now()->format('Y-m-d') }}">
            @error('selected_date')<div class="alert alert-danger mt-2">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="form-label" for="start-time-select">@lang('booking.atc.create.start-time-text')</label>
            <input wire:model="selected_start_at" id="start-time-select" type="number" class="form-control" placeholder="1900">
            @error('selected_start_at')<div class="alert alert-danger mt-2">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="form-label" for="end-time-select">@lang('booking.atc.create.end-time-text')</label>
            <input wire:model="selected_end_at" id="end-time-select" type="number" class="form-control" placeholder="2130">
            @error('selected_end_at')<div class="alert alert-danger mt-2">{{ $message }}</div>@enderror
        </div>
        <div class="relative">
            <label class="form-label" for="station-search">@lang('booking.atc.create.station-text')</label>
            @if(!$selected_station)
                <input wire:model.live="station_search" id="station-search" type="search" class="form-control">
                @if(!empty($station_search))
                    <div class="absolute inset-x-0 z-20 mt-1 max-h-64 overflow-y-auto rounded-lg border border-secondary-200 bg-white shadow-xl dark:border-secondary-700 dark:bg-secondary-800">
                        @foreach($station_suggestions as $s)
                            <button type="button" wire:click="station_select({{ $s->id }})" class="grid w-full grid-cols-[auto_1fr_auto] gap-3 border-b border-secondary-100 px-3 py-2 text-left text-sm hover:bg-secondary-50 dark:border-secondary-700 dark:hover:bg-secondary-700">
                                <strong>{{ $s->ident }}</strong><span>{{ $s->name }}</span><span>{{ $s->fixed_frequency }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            @else
                <button type="button" wire:click="station_select(-1)" class="flex min-h-11 w-full items-center justify-between rounded-lg border border-secondary-300 bg-white px-3 text-left dark:border-secondary-600 dark:bg-secondary-800">
                    <span><strong>{{ $selected_station->ident }}</strong> · {{ $selected_station->name }} · {{ $selected_station->fixed_frequency }}</span>
                    <i data-feather="x" class="size-4"></i>
                </button>
            @endif
            @error('selected_station')<div class="alert alert-danger mt-2">{{ $message }}</div>@enderror
        </div>

        @if(!$selected_vatger_event)
            <input wire:model="selected_voice" type="checkbox" id="voice-selector" name="voice" hidden>
            <label class="form-check"><input wire:model="selected_event" class="form-check-input" type="checkbox"><span class="form-check-label">@lang('booking.atc.create.event-text')</span></label>
        @endif
        @if($can_vatger_event)
            <label class="form-check"><input wire:model="selected_vatger_event" class="form-check-input" type="checkbox"><span class="form-check-label">@lang('booking.atc.create.vatger-event-text')</span></label>
        @endif
        @if(!$selected_vatger_event)
            <label class="form-check"><input wire:model="selected_training" class="form-check-input" type="checkbox"><span class="form-check-label">@lang('booking.atc.create.training-text')</span></label>
        @endif

        <button type="button" wire:click="book" class="btn btn-primary w-full">@lang('booking.atc.create.save-button-text')</button>

        @if(!$selected_vatger_event)
            <a href="{{ route('policies', ['policy_id' => 'pol.booking']) }}" class="alert alert-info flex items-center justify-center gap-2 font-medium">
                <i data-feather="info" class="size-5"></i>@lang('booking.atc.create.rules-text')
            </a>
        @endif
    </div>
</section>
