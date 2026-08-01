@props([
    'header' => 'PLACE TITLE',
    'links' => [ route('administration.dashboard') => 'Administration' ]
])
<div class="admin-page-heading">
    <h1>{{ $header }}</h1>

    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
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
