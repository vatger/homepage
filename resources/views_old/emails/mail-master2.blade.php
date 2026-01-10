<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <style>
        .outer {
            box-shadow: 4px 3px 8px 0 #dddddd;
            border: 1px solid #dfdfdf;
            border-radius: 5px;
            margin-left: auto;
            margin-right: auto;
        }

        .container-content {
            padding: 0 2rem 1rem 2rem;
        }

        .image-container {
            padding: 10px 0;
            background-color: #2f55d4;
            border-radius: 5px 5px 0 0;
        }

        footer {
            background-color: #f8f9fc;
            color: #a9a9a9;
            text-align: center;
            font-size: 0.8rem;
            padding: 10px;
            border-radius: 0 0 5px 5px;
        }
    </style>
</head>

<body style="font-family: Inter, sans-serif; font-size: 15px; font-weight: 400; justify-content: center">
<!-- Hero Start -->
<div class="outer" style="max-width: 800px;">
    <div class="image-container">
        <img src="{{ $message->embed(asset('images/vacc_logo_white.png')) }}" height="64px" alt="VATSIM Germany"
             style="vertical-align: middle; margin: 20px auto;display: block">
    </div>

    <div class="container">
        <div class="container-content">
            <h4>{{ $title }}</h4>
            <h6>{{ $source_name }} sendete eine Nachricht:</h6>

            <p>{!! $message_text !!}</p>

            <a href="{{ $link_url }}"
               style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px; background-color: #2f55d4; border: 1px solid #2f55d4; color: #ffffff;">
                {{ $link_text }}
            </a>

            <p style="margin-top: 30px;"> Solltest du noch Fragen haben wende dich bitte an <a href="mailto:support@vatger.de">support@vatger.de</a> oder antworte auf diese Nachricht.</p>

            <div style="width: 100%; height: 1px; background-color: lightgray"></div>


            <p style="margin-top: 40px">{{ $source_name }}</p>

        </div>

        <footer>
            Diese Nachricht wurde automatisch am {{ \Carbon\Carbon::now()->format('d.m.Y') }} generiert.
        </footer>
    </div>

</div>
<!-- Hero End -->
</body>
</html>
