<div>
    @component('components.layouts.content',[
        'header' => __('pages.restricted.title'),
        'links' => [
            route('landing') => config('app.name'),
            __('navigation.lotsen.titel'),
            __('pages.restricted.title')
            ]
    ])

    @endcomponent
    <section class="section">
        <div class="container">
            <x-controller.staffing-tool-link />

            <div class="mb-5" style="margin-left:auto;margin-right: 0;width: 40%">
                <div class="form-icon position-relative">
                    <i data-feather="search" class="fea icon-sm icons"></i>
                    <input class="form-control ps-5" wire:model.live="search" type="search"
                           placeholder="{{ __('pages.stations.search-placeholder') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-primary">
                    @lang('pages.restricted.choose-type')<span class="text-danger">*</span>
                </label>
                <select wire:model.live="restriction" class="form-select form-control" aria-label="RestrictionChooser">
                    <option selected></option>
                    @foreach($rests as $r)
                        <option value="{{$r->id}}" @if($restriction == $r->id) selected @endif>{{$r->desc}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="container">
            <div class="mb-3">
                <table class="table mb-0 table-center">
                    <thead>
                    <tr>
                        <th scope="col" class="border-bottom" wire:click="sortBy('ident')">
                            @lang('pages.stations.ident')
                            <i data-feather="{{ $this->getSortIconClasses('ident') }}"></i>
                        </th>
                        <th scope="col" class="border-bottom" wire:click="sortBy('name')">
                            @lang('pages.stations.name')
                            <i data-feather="{{ $this->getSortIconClasses('name') }}"></i>
                        </th>
                        <th scope="col" class="border-bottom" wire:click="sortBy('frequency')">
                            @lang('pages.stations.frequency')
                            <i data-feather="{{ $this->getSortIconClasses('frequency') }}"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stations as $s)
                        <tr>
                            <th scope="row">{{$s->ident}}</th>
                            <td>{{$s->name}}</td>
                            <td>{{$s->fixedFrequency}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
