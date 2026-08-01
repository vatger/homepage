<div>
    @component('components.layouts.content',[
        'header' => __('pilot.aerodromes.title'),
        'links' => [
            route('landing') => config('app.name'),
            __('navigation.piloten.titel'),
            route('pilots.aerodromes.viewall') => __('pilot.aerodromes.title')
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 mt-4 col-12">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0">
                        <div class="card-body p-0 content">
                            <div class="mb-3">
                                <label class="form-label">@lang('pilot.aerodromes.search-text')</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="book" class="fea icon-sm icons"></i>
                                    <input wire:model.live="search" name="subject" class="form-control ps-5" type="text"
                                           placeholder="@lang('pilot.aerodromes.search-input-placeholder')">
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <p class="text-muted mb-0" style="display: none" id="search-count-container">
                            {!! __('pages.aerodromes.search-results', ['count' => '<span id="search-count"></span>']) !!}
                        </p>
                    </div>
                </div>
                <!--end col-->
            </div>

            <div class="row mb-1 text-center">
                <div class="row" style="padding-right: 0 !important; left: 5px !important;">
                    @foreach($aerodromes as $aerodrome)
                        <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2 picture-item cursor-pointer" role="button">
                            <a wire:click="aerodrome_select({{ $aerodrome->id }})" class="block cursor-pointer">
                                <div class="card blog border-0 work-container work-primary work-classic shadow rounded-md overflow-hidden">
                                    <div class="card-body">
                                        <div class="content">
                                            <h5><span class="text-dark title">{{ $aerodrome->icao }} {{ $aerodrome->iata ? ' | ' . $aerodrome->iata : '' }} </span></h5>
                                            <p class="text-muted mb-0">{{ $aerodrome->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!--end col-->
                    @endforeach
                </div>
                <div class="row justify-content-center">
                    {{ $aerodromes->links() }}
                </div>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
