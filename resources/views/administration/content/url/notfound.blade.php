<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('administration.partials.head')
</head>

<body>

    <!-- ERROR PAGE -->
    <section class="bg-home d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 text-center">
                    <img src="" class="img-fluid" alt="">
                    @if ($exp)
                        <div class="text-uppercase mt-4 display-4 border-bottom pb-4 text-muted">URL Expired</div>
                        <div class="text-dark mb-5 mt-4 error-page display-7"><span
                                class="text-muted">{{ \Illuminate\Support\Facades\URL::to('/r/') }}</span>/{{ strlen($req) > 40 ? substr(strtolower($req), 0, 40) . '...' : strtolower($req) }}
                        </div>
                        <p class="text-muted para-desc mx-auto">The requested URL has expired and is no longer available.</p>
                    @else
                        <div class="text-uppercase mt-4 display-4 border-bottom pb-4 text-muted">URL Not Found</div>
                        <div class="text-dark mb-5 mt-4 error-page display-7"><span
                                class="text-muted">{{ \Illuminate\Support\Facades\URL::to('/r/') }}</span>/{{ strlen($req) > 40 ? substr(strtolower($req), 0, 40) . '...' : strtolower($req) }}
                        </div>
                        <p class="text-muted para-desc mx-auto">Check the link you entered is valid and try again, or go back to the
                            homepage.</p>
                    @endif
                    <a href="{{ route('landing') }}" class="btn btn-primary mt-3 ms-2">Homepage</a>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- ERROR PAGE -->
</body>

<style>
    .display-7 {
        font-size: calc(1rem + 1.3vw);
        font-weight: 300;
        line-height: 1.2;
    }

    @media (min-width: 1200px) {
        .display-7 {
            font-size: 2rem;
        }
    }
</style>
