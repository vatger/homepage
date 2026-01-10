<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>

<body style="font-family: Inter, sans-serif; font-size: 15px; font-weight: 400; justify-content: center">
<!-- Hero Start -->
<div style="margin-top: 50px;">
    <table
        style="font-family: Inter, sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
        <thead>
        <tr style="background-color: #2f55d4; padding: 3px 0; line-height: 68px; text-align: center; color: #fff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
            <th scope="col">
                <img src="{{ $message->embed(asset('images/vacc_logo_white.png')) }}" height="48px" alt="VATSIM Germany" style="margin-bottom: 10px">
            </th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td style="padding: 24px 24px;">
                <div
                    style="padding: 8px; color: #000000; background-color: rgba(228, 63, 82, 0.2); border: 1px solid rgba(228, 63, 82, 0.2); border-radius: 6px; text-align: center; font-size: 16px; font-weight: 600;">
                    {{ $title }}
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 24px 15px; color: #8492a6;">
                {{ $source_name }} sendete eine Nachricht:
            </td>
        </tr>
        <tr>
            <td style="padding: 24px 24px 15px; color: #000000;">
                {!! $message_text !!}
            </td>
        </tr>
        @if($link_text && $link_url)
            <tr>
                <td style="padding: 15px 24px;">
                    <a href="{{ $link_url }}"
                       style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px; background-color: #2f55d4; border: 1px solid #2f55d4; color: #ffffff;">
                        {{ $link_text }}
                    </a>
                </td>
            </tr>
        @endif

        <tr>
            <td style="padding: 48px 24px 0; color: #8492a6;">
                Solltest du noch Fragen haben wende dich bitte an <a href="mailto:support@vatger.de">support@vatger.de</a> oder antworte auf diese Nachricht.
            </td>
        </tr>

        <tr>
            <td style="padding: 15px 24px 15px; color: #8492a6;">
                VATSIM Germany <br> Support Team
            </td>
        </tr>

        <tr>
            <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
                Diese automatische Nachricht wurde am {{ \Carbon\Carbon::now()->format('d.m.Y') }} generiert.
            </td>
        </tr>
        </tbody>
    </table>
</div>
<!-- Hero End -->
</body>
</html>
