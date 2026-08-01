<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{{ $title }}</title>
    <style>
        :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
        }

        @media only screen and (max-width: 620px) {
            .email-card {
                width: 100% !important;
            }

            .email-padding {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }

        @media (prefers-color-scheme: dark) {
            .email-bg {
                background-color: #282d35 !important;
            }

            .email-card {
                background-color: #343941 !important;
                border-color: #414751 !important;
            }

            .email-copy,
            .email-signature {
                color: #f0f1f3 !important;
            }

            .email-muted {
                color: #b8bec7 !important;
            }

            .email-divider {
                border-color: #414751 !important;
            }

            .email-title {
                background-color: #282d35 !important;
                border-color: #414751 !important;
                color: #f0f1f3 !important;
            }

            .email-button {
                background-color: #2b3f55 !important;
                border-color: #2b3f55 !important;
                color: #ffffff !important;
            }

            .email-header,
            .email-footer {
                background-color: #202b37 !important;
                background-image: linear-gradient(#202b37, #202b37) !important;
            }
        }

        [data-ogsc] .email-bg {
            background-color: #282d35 !important;
        }

        [data-ogsc] .email-card {
            background-color: #343941 !important;
            border-color: #414751 !important;
        }

        [data-ogsc] .email-copy,
        [data-ogsc] .email-signature {
            color: #f0f1f3 !important;
        }

        [data-ogsc] .email-muted {
            color: #b8bec7 !important;
        }

        [data-ogsc] .email-divider {
            border-color: #414751 !important;
        }

        [data-ogsc] .email-title {
            background-color: #282d35 !important;
            border-color: #414751 !important;
            color: #f0f1f3 !important;
        }

        [data-ogsc] .email-button {
            background-color: #2b3f55 !important;
            border-color: #2b3f55 !important;
            color: #ffffff !important;
        }

        [data-ogsc] .email-header,
        [data-ogsc] .email-footer {
            background-color: #202b37 !important;
            background-image: linear-gradient(#202b37, #202b37) !important;
        }
    </style>
    <!--[if mso]>
    <style>
        .email-card {
            width: 600px !important;
        }
    </style>
    <![endif]-->
</head>

<body class="email-bg" style="margin: 0; padding: 0; background-color: #f0f1f3; color: #2b3f55; font-family: 'Segoe UI', Arial, sans-serif;">
<!-- Hero Start -->
<div style="padding: 40px 12px;">
    <table role="presentation" width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="email-card"
           style="width: 600px; max-width: 100%; margin: 0 auto; border-collapse: separate; border-spacing: 0; overflow: hidden; background-color: #ffffff; border: 1px solid #d2d5dc; border-radius: 16px; box-shadow: 0 8px 30px rgba(43, 63, 85, 0.12);">
        <thead>
        <tr bgcolor="#202b37" class="email-header"
            style="background-color: #202b37; background-image: linear-gradient(#202b37, #202b37);">
            <th scope="col" style="padding: 28px 32px; text-align: left;">
                <img src="{{ $message->embed(public_path('images/brand/logo-email-dark.png')) }}"
                     width="208" height="38" alt="VATSIM Germany"
                     style="display: block; width: 208px; height: 38px; max-width: 100%; border: 0;">
            </th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="email-padding" style="padding: 32px 32px 16px; text-align: center;">
                <div
                    class="email-title"
                    style="padding: 13px 18px; color: #2b3f55; background-color: #f0f1f3; border: 1px solid #d2d5dc; border-radius: 16px; font-size: 18px; line-height: 1.4; font-weight: 700;">
                    {{ $title }}
                </div>
            </td>
        </tr>
        <tr>
            <td class="email-padding email-muted" style="padding: 0 32px 12px; color: #8690a0; text-align: center; font-size: 14px; line-height: 1.5;">
                {{ $source_name }} hat dir eine Nachricht gesendet:
            </td>
        </tr>
        <tr>
            <td class="email-padding email-copy" style="padding: 20px 32px; color: #2b3f55; font-size: 15px; line-height: 1.7; text-align: left;">
                {!! $message_text !!}
            </td>
        </tr>
        @if($link_text && $link_url)
            <tr>
                <td class="email-padding" style="padding: 12px 32px 20px; text-align: center;">
                    <a href="{{ $link_url }}"
                       class="email-button"
                       style="display: inline-block; padding: 12px 24px; text-decoration: none; font-size: 16px; line-height: 1.2; font-weight: 700; border-radius: 16px; background-color: #2b3f55; border: 1px solid #2b3f55; color: #ffffff;">
                        {{ $link_text }}
                    </a>
                </td>
            </tr>
        @endif

        <tr>
            <td class="email-padding email-muted email-divider" style="padding: 32px 32px 0; color: #8690a0; font-size: 14px; line-height: 1.6; text-align: center; border-top: 1px solid #e7e9ed;">
                Bei Fragen erreichst du uns unter <a href="mailto:support@vatger.de" style="color: #ea5763; font-weight: 600; text-decoration: none;">support@vatger.de</a> oder antworte einfach auf diese Nachricht. Deine VATSIM-ID ist {{
                $user_id }}.
            </td>
        </tr>

        <tr>
            <td class="email-padding email-signature" style="padding: 18px 32px 28px; color: #2b3f55; font-size: 14px; line-height: 1.5; text-align: center; font-weight: 600;">
                VATSIM Germany<br>
                <span class="email-muted" style="color: #8690a0; font-weight: 400;">Support Team</span>
            </td>
        </tr>

        <tr>
            <td bgcolor="#202b37" class="email-footer"
                style="padding: 16px 24px; color: #b8bec7; background-color: #202b37; background-image: linear-gradient(#202b37, #202b37); text-align: center; font-size: 12px; line-height: 1.5;">
                Diese automatische Nachricht wurde am {{ \Carbon\Carbon::now()->format('d.m.Y') }} erstellt.
            </td>
        </tr>
        </tbody>
    </table>
</div>
<!-- Hero End -->
</body>
</html>
