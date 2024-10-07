<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>

<body>
<!-- Hero Start -->
<div style="margin-left: 50px; display: flex; justify-content: center;">
    <table>
        <thead style="background-color: #2f55d4; padding: 32px; border: none; border-radius: 20px;">
        <tr>
            <th scope="col" style="padding: 32px;">
                <img src="{{ $message->embed(public_path('images/vacclogowhite.png.png')) }}" alt="VATSIM Germany">
            </th>
        </tr>
        </thead>

        <tbody style="font-family: 'Inter', sans-serif; font-size: 15px; font-weight: 400; max-width: 400px;  overflow: hidden; background-color: #ffffff; box-shadow: 0 4px
        12px rgba(0, 0, 0, 0.1);">
        <tr>
            <td style="padding: 24px; text-align: center;">
                <div
                    style="padding: 12px; color: #e43f52; background-color: #fce4e6; border-radius: 6px; font-size: 16px; font-weight: 600;">
                    {{ $title }}
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 24px 10px; color: #8492a6; text-align: center;">
                {{ $source_name }} hat dir eine Nachricht gesendet:
            </td>
        </tr>
        <tr>
            <td style="padding: 24px; color: #333333; line-height: 1.6; text-align: left;">
                {!! $message_text !!}
            </td>
        </tr>
        @if($link_text && $link_url)
            <tr>
                <td style="padding: 15px 24px; text-align: center;">
                    <a href="{{ $link_url }}"
                       style="padding: 10px 20px; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 6px; background-color: #2f55d4; color: #ffffff; display: inline-block; transition: background-color 0.3s ease;">
                        {{ $link_text }}
                    </a>
                </td>
            </tr>
        @endif

        <tr>
            <td style="padding: 48px 24px 0; color: #8492a6; font-size: 14px; text-align: center;">
                Bei Fragen erreichst du uns unter <a href="mailto:support@vatger.de" style="color: #2f55d4;">support@vatger.de</a> oder antworte einfach auf diese Nachricht.
            </td>
        </tr>

        <tr>
            <td style="padding: 15px 24px; color: #8492a6; font-size: 14px; text-align: center;">
                VATSIM Germany <br> Support Team
            </td>
        </tr>

        <tr>
            <td style="padding: 16px; color: #8492a6; background-color: #f8f9fc; text-align: center; font-size: 12px;">
                Diese automatische Nachricht wurde am {{ \Carbon\Carbon::now()->format('d.m.Y') }} erstellt.
            </td>
        </tr>
        </tbody>
    </table>
</div>
<!-- Hero End -->
</body>
</html>
