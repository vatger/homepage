<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Primary Meta Tags -->
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="title" content="{{ config('app.name') }}">
<meta name="author" content="vACC Germany">
<meta name="description" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="canonical" href="https://vatsim-germany.org">
<meta name="lang" content="{{ app()->getLocale() }}">

<!-- favicon -->
<link rel="shortcut icon" href="/favicon.ico">

<!-- Main Css -->
@auth
    <link href="@if (\Auth::user()->settings->dark_mode) {{ asset('css/app-dark.css') }} @else {{ asset('css/app.css') }} @endif" rel="stylesheet"
        type="text/css" id="theme-opt" />

    <link href="{{ asset('css/colors/' . \Auth::user()->settings->color . '.css') }}" rel="stylesheet" id="color-opt">
@else
    @if (isset($_COOKIE['color_scheme']) && $_COOKIE['color_scheme'] == 'dark')
        <link href="{{ asset('css/app-dark.css') }}" rel="stylesheet" type="text/css" id="theme-opt" />
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" id="theme-opt" />
    @endif

    <!-- Default color option, irrespective of color_scheme -->
    <link href="{{ asset('css/colors/default.css') }}" rel="stylesheet" id="color-opt">
@endauth

<!-- tobii css -->
<link href="{{ asset('css/vendor/tobii.min.css') }}" rel="stylesheet" type="text/css" />

<!- Icons -->
    <link href="{{ asset('css/vendor/materialdesignicons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Slider -->
    <link rel="stylesheet" href="{{ asset('css/vendor/tiny-slider.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/vendor/noty.css') }}">

    @stack('styles')
