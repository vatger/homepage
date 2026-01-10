<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.head')
    @stack('styles')
</head>
<body>
@include('layouts.header')

@yield('content')
{{ $slot ?? '' }}

@include('layouts.footer')

@vite(['resources/ts/app.ts'])
@livewireScriptConfig
@include('layouts.noty')
@stack('scripts')
</body>
</html>
