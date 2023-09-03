<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="{{ config('app.name') }}">
    <meta name="author" content="vACC Germany">
    <meta name="description" content="">
    <link rel="canonical" href="https://vatsim-germany.org">
    <meta name="lang" content="{{ app()->getLocale() }}">

    <!-- Font -->
    @vite('resources/scss/mail.scss')
</head>

<body style="font-family: Inter, sans-serif; font-size: 15px; font-weight: 400;">


<!-- Hero Start -->
<div style="margin-top: 50px;">
    <table cellpadding="0" cellspacing="0"
           style="font-family: Inter, sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
        <thead>
        <tr style="background-color: #2f55d4; padding: 3px 0; line-height: 68px; text-align: center; color: #fff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
            <th scope="col"><img src="{{ asset('images/vacc_logo.png') }}" height="24" alt=""></th>
        </tr>
        </thead>

        <tbody>
        {{ $slot ?? '' }}
        <tr>
            <td style="padding: 24px 24px;">
                <div style="padding: 8px; color: #e43f52; background-color: rgba(228, 63, 82, 0.2); border: 1px solid rgba(228, 63, 82, 0.2); border-radius: 6px; text-align: center; font-size: 16px; font-weight: 600;">
                    Warning: You're approaching your limit. Please upgrade.
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 24px 15px; color: #8492a6;">
                Add your credit card / debit card now to upgrade your account to a premium plan to ensure you don't miss out on any reports.
            </td>
        </tr>

        <tr>
            <td style="padding: 15px 24px;">
                <a href="javascript:void(0)"
                   style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px; background-color: #2f55d4; border: 1px solid #2f55d4; color: #ffffff;">Upgrade
                    Account</a>
            </td>
        </tr>

        <tr>
            <td style="padding: 15px 24px 0; color: #8492a6;">
                Thanks for choosing Landrick Template.
            </td>
        </tr>

        <tr>
            <td style="padding: 15px 24px 15px; color: #8492a6;">
                Landrick <br> Support Team
            </td>
        </tr>

        <tr>
            <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
                ©
                <script>document.write(new Date().getFullYear());</script>
                Landrick.
            </td>
        </tr>
        </tbody>
    </table>
</div>
<!-- Hero End -->
</body>
</html>
