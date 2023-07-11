<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('homepage.partials.includes.head')
    @livewireStyles
</head>

<body>
    @include('homepage.partials.includes.navigation')

    @yield('content')

    @include('homepage.partials.includes.footer')

    @include('homepage.partials.includes.scripts')

    <!-- Back to top -->
    <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up"
            class="fea icon-sm icons align-middle"></i></a>
    <!-- Back to top -->
    @livewireScripts
</body>

</html>
