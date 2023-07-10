<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
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

    @vite('resources/scss/app.scss')
    @stack('styles')
</head>

<body>
    @include('homepage.partials.header')

    @yield('content')

    @include('homepage.partials.footer')

    <script>
        function deferLoading(method) {
            if (window.jQuery) {
                method();
            } else {
                setTimeout(() => {
                    deferLoading(method)
                }, 100);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@2.3.0/build/global/luxon.min.js"></script>
    <!-- javascript -->
    @vite(['resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie/dist/js.cookie.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
    {{--
    Adjust color scheme based on OS colors.
    This might require a page reload.
 --}}
    <script>
        // code to set the `color_scheme` cookie
        let color_scheme = Cookies.get("color_scheme");

        function get_color_scheme() {
            return (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) ? "dark" : "light";
        }

        function update_color_scheme() {
            Cookies.set("color_scheme", get_color_scheme());
            location.reload();
        }

        // read & compare cookie `color-scheme`
        if ((typeof color_scheme === "undefined") || (get_color_scheme() != color_scheme))
            update_color_scheme();
        // detect changes and change the cookie
        if (window.matchMedia)
            window.matchMedia("(prefers-color-scheme: dark)").addListener(update_color_scheme);
    </script>

    <!-- Global configuration -->
    <script>
        /**
         * Custom config for routes. These can be loaded in native .js files
         */
        const config = {
            routes: {
                api: {
                    events: {
                        'loadEvents': "{{ route('api.loadEvents') }}",
                    },
                    atcfb: {
                        'checkUser': "{{ route('api.user.check') }}"
                    }
                },
                global: {

                }
            },
            tinyMce: {
                'default': {
                    @if (Auth::check() && \Auth::user()->settings->dark_mode)
                        skin: 'oxide-dark',
                        content_css: 'dark',
                    @endif
                    plugins: 'lists',
                    menubar: 'false',
                    toolbar: 'undo redo | styleselect | bold italic | bullist numlist',
                    toolbar_mode: 'floating',
                    selector: 'textarea',
                }
            }
        };

        /**
         * Setup default ajax Settings
         */
        function setAjaxTokenForJQuery() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        deferLoading(setAjaxTokenForJQuery);

        @foreach ($errors->all() as $error)
            new Noty({
                text: '{{ $error }}',
                progressBar: true,
                timeout: 5000,
                layout: 'topRight',
                type: 'error',
            }).show();
        @endforeach

        @if (\Illuminate\Support\Facades\Session::has('success'))
            new Noty({
                text: '{{ \Illuminate\Support\Facades\Session::get('success') }}',
                progressBar: true,
                timeout: 5000,
                layout: 'topRight',
                type: 'success',
            }).show();
        @endif
    </script>

    <!-- Custom utility functions -->
    <script>
        /**
         * Show new noty message with custom (or default) parameters
         * @param message
         * @param type
         * @param timeout
         */
        function showNoty(message, type = 'success', timeout = 2500) {
            new Noty({
                text: message,
                progressBar: true,
                timeout: timeout,
                layout: 'topRight',
                type: type,
            }).show();
        }

        /**
         * Returns the corresponding short ATC rating from its ID
         * @param id
         * @returns {string}
         */
        function convertAtcRating(id) {
            switch (id) {
                case -1:
                    return "INAC";

                case 0:
                    return "SUS";

                case 1:
                    return "OBS";

                case 2:
                    return "S1";

                case 3:
                    return "S2";

                case 4:
                    return "S3";

                case 5:
                    return "C1";

                case 6:
                    return "C2";

                case 7:
                    return "C3";

                case 8:
                    return "I1";

                case 9:
                    return "I2";

                case 10:
                    return "I3";

                case 11:
                    return "SUP";

                case 12:
                    return "ADM";
            }

            return "err";
        }
    </script>

    @stack('scripts')

</body>

</html>
