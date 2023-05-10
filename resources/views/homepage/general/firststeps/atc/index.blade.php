@extends('homepage.general.firststeps.partial.hero')

@section('hero-img-src')
    {{ asset('images/hero-banners/hero_1.png') }}
@endsection

@section('title')
    @lang('first-steps.become-atco.title')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('getting-started') }}">Getting Started</a></li>
    <li class="breadcrumb-item">ATC</li>
    <li class="breadcrumb-item active" aria-current="page">@lang('first-steps.become-atco.breadcrumb')</li>
@endsection

@section('links')
    <div class="d-flex align-items-center mt-3">
        <div class="flex-1">
            <a href="#" target="_blank">
                <button type="button" class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">@lang('general.faq')</button>
            </a>
        </div>
    </div>
@endsection

@section('navigation')
    @for ($i = 0; $i < 5; $i++)
        <div class="mt-2">
            <div class="d-flex align-items-center mt-3">
                <div class="flex-1">
                    <a>
                        <button type="button" class="btn btn-soft-primary navigation-button" id="navigation-{{ $i + 1 }}"
                            style="width: 90%; margin-left: 5%" data-id="{{ $i + 1 }}">@lang('first-steps.become-atco.content.' . $i . '.title')</button>
                    </a>
                </div>
            </div>
        </div>
    @endfor
@endsection

@section('blog-content')
    <h5>@lang('first-steps.become-atco.title')</h5>
    <div id="getting-started-container">
        @for ($i = 0; $i < 5; $i++)
            <div class="accordion mt-2 pt-2">
                <div class="accordion-item rounded shadow bg-white mt-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button border-0 bg-light @if ($i != 0) collapsed @endif" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $i + 1 }}" data-id="{{ $i + 1 }}"
                            aria-expanded="@if ($i == 0) true @else false @endif" aria-controls="collapse-{{ $i }}">
                            {{ $i + 1 }}. @lang('first-steps.become-atco.content.' . $i . '.title')
                        </button>
                    </h2>
                    <div id="collapse-{{ $i + 1 }}" class="accordion-collapse border-0 collapse @if ($i == 0) show @endif"
                        aria-labelledby="collapse-{{ $i + 1 }}" data-bs-parent="#getting-started-container" style="">
                        <div class="accordion-body text-muted">
                            @lang('first-steps.become-atco.content.' . $i . '.content.0')
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@endsection

@push('custom-script')
    <script>
        $(document).ready(() => {
            // Required, since the attr. gets overriden on load
            $("#navigation-1").attr('disabled', true);
        })

        $(".navigation-button").click(function() {
            let buttonId = $(this).data('id');

            enableAllButtons();

            $(`#collapse-${buttonId}`).collapse('toggle');
            $(this).attr('disabled', true);
        });

        $(".accordion-button").click(function() {
            let accordionId = $(this).data('id');

            enableAllButtons();

            if (!$(this).hasClass('collapsed'))
                $(`#navigation-${accordionId}`).attr('disabled', true);
        });

        function enableAllButtons() {
            for (let i = 0; i < 6; i++) {
                $(`#navigation-${i}`).attr('disabled', false);
            }
        }
    </script>
@endpush
