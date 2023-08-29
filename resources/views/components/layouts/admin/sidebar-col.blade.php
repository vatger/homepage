@props([
    'position',
    'title',
    'items' => []
])

@if($position == 'left')
    <div class="col-lg-4 col-md-12 col-sm-12 col-12 mt-4 order-1">
        <div class="card border-0 rounded shadow p-4">
            <h5 class="mb-0">{{ $title }}:</h5>
            <div class="mt-4">

                @foreach($items as $item)
                    <div class="d-flex align-items-center">
                        <i data-feather="{{ $item[2] ?? 'feather' }}" class="fea icon-ex-md text-muted me-3"></i>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">{{ $item[0] }}:</h6>
                            <p class="text-muted">{{ $item[1] }}</p>
                        </div>
                    </div>
                @endforeach

                {{ $slot ?? '' }}

            </div>
        </div>
    </div>
@endif
@if($position == 'right')
    <div class="col-lg-8 col-md-12  order-2">
        <div class="px-4 pb-4">
            {{ $slot }}
        </div>
    </div>
@endif
