@props([
    'header' => 'PLACE TITLE',
    'subheader' => null,
    'links' => [ route('landing') => config('app.name') ],
    'backgroundurl' => null,
    'backgroundshape' => 'bend'
])


<section class="bg-half-170 bg-light d-table w-100" style='background-image: url("{{$backgroundurl ?? iasset('images/getstarted/getstarted_1.png')}} ")'>
    <div class="bg-overlay" style="background-color: rgb(30 41 58 / 85%)"></div>
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h2 style="color: white">{{ $header }}</h2>
                    <h6 style="color: white">{{ $subheader}}</h6>
                    {{ $slot ?? '' }}
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        @if(!empty($links))
            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        @foreach($links as $href=>$name)
                            @if($loop->last)
                                <li class="breadcrumb-item active">
                                    {{ $name }}
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    @if(empty($href))
                                        {{ $name }}
                                    @else
                                        <a href="{{ $href }}">{{ $name }}</a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
            </div>
        @endif
    </div>
    <!--end container-->
</section>
<!--end section-->
<!-- Hero End -->
