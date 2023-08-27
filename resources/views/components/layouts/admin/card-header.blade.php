@props([
    'position' => 'left',
    'title',
    'subtitle',
    'icon',
])
@if($position == 'left')
    <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
        <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
            <div class="d-flex align-items-center">
                <div class="icon text-center rounded-pill">
                    <i data-feather="{{ $icon ?? 'feather' }}" class="fea fs-4 mb-0"></i>
                </div>
                <div class="flex-1 ms-3">
                    <h6 class="mb-0 text-muted">{{ $title }}</h6>
                    <p class="fs-5 text-dark fw-bold mb-0">
                        {{ $subtitle }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
        {{ $slot }}
    </div>
@endif
