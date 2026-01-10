<!DOCTYPE html>
@php
    $_admin = true;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.head')
    @stack('styles')
</head>
<body>

@include('layouts.admin.admin-content')

@vite(['resources/ts/app.ts'])
@livewireScriptConfig
@include('layouts.noty')
@stack('scripts')
</body>
</html>
