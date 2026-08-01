@props([
    'header' => 'PLACE TITLE',
    'subheader' => null,
    'links' => [route('landing') => config('app.name')],
    'backgroundurl' => null,
    'backgroundshape' => 'bend',
])

<section class="relative isolate flex min-h-80 items-center overflow-visible bg-primary-900 py-20 text-white"
         style="background-image: linear-gradient(rgb(22 34 51 / 82%), rgb(22 34 51 / 88%)), url('{{ $backgroundurl ?? iasset('images/getstarted/getstarted_1.png') }}'); background-position: center; background-size: cover;">
    <div class="site-container text-center">
        <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">{{ $header }}</h1>
        @if($subheader)
            <p class="mx-auto mt-3 max-w-3xl text-base text-secondary-200">{{ $subheader }}</p>
        @endif
        {{ $slot ?? '' }}
    </div>

    @if(!empty($links))
        <nav aria-label="breadcrumb" class="absolute inset-x-0 bottom-0 z-10 translate-y-1/2 px-4">
            <ol class="breadcrumb mx-auto w-fit max-w-[calc(100%-2rem)] text-primary-900 dark:text-secondary-50">
                @foreach($links as $href => $name)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        @if(!$loop->last && !empty($href))
                            <a href="{{ $href }}">{{ $name }}</a>
                        @else
                            {{ $name }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
</section>
