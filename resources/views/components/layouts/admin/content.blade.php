@props([
    'header' => 'PLACE TITLE',
    'links' => [ route('administration.dashboard') => 'Administration' ]
])
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">{{ $header }}</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            @foreach($links as $href=>$name)

                <li class="breadcrumb-item text-capitalize">
                    <a href="{{ $href }}">{{ $name }}</a>
                </li>
            @endforeach
            <li class="breadcrumb-item text-capitalize active" aria-current="page">
                {{ $header }}
            </li>
        </ul>
    </nav>
</div>
