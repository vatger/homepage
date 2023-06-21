<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VATSIM Germany API</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@latest/swagger-ui.css">
    <script src="https://unpkg.com/swagger-ui-dist@latest/swagger-ui-bundle.js"></script>
</head>

<body>
    <header>
        <a class="logo" href="{{ route('landing') }}">
            <img src="{{ asset('images/vacc_logo.png') }}" height="32" class="logo" alt="">
        </a>
    </header>
    <div class="container">
        <div id="swagger-ui"></div>
    </div>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: '/openapi',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                ],
                layout: 'BaseLayout',
            });
        };
    </script>
</body>

</html>
