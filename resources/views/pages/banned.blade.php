@extends('layouts.master')

@section('content')
    @component('components.layouts.content',[
        'header' => 'Gesperrt',
        'links' => [
            route('landing') => config('app.name'),
            'Members',
            'Banned',
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            Dein VATSIM Germany Account wurde gesperrt. Dies kann verschiedene Gründe haben.<br>
            Your VATSIM Germany account has been blocked. This can have various reasons.<br>
            <!--end row-->
            @if($ban)
                <p><b>Grund:</b> {{ $ban->type }}</p>
                <p>{{ $ban->reason }}</p>
            @endif
            @if($ban->type)
                <p>
                    Durch längere Inaktivität im VATSIM-Netzwerk ist dein Konto (Account) in der zentralen Datenbank von VATSIM automatisch auf 'INACTIVE' gesetzt worden. Wir haben leider keinen
                    Zugriff auf diese Datenbank und können dir daher nicht weiterhelfen.
                    Du kannst deinen Account unter diesem Link reaktivieren: <a href="https://my.vatsim.net/reactivate">my.vatsim.net/reactivate</a>.
                    Wende dich bitte an das VATSIM Membership Department (<a href="https://membership.vatsim.net/">membership.vatsim.net/</a>),
                    sollten Probleme bei dieser Reaktivierung auftauchen, da wir von VATSIM Germany nicht zentral an der Datenbank arbeiten können.
                    Sollte dein Account immer noch als 'INACTIVE' auf der VATSIM Germany Homepage angezeigt werden, klicke <a href="{{ route('member.refresh') }}">hier</a>.
                </p>
                <p>
                    Due to prolonged inactivity on the VATSIM network, your account has been automatically set to 'INACTIVE' in the central VATSIM database. Unfortunately, we do not have access to
                    this database and therefore cannot assist you further.
                    You can reactivate your account using this link: <a href="https://my.vatsim.net/reactivate">my.vatsim.net/reactivate</a>.
                    Please contact the VATSIM Membership Department (<a href="https://membership.vatsim.net/">membership.vatsim.net</a>) if you encounter any issues with this reactivation, as VATSIM
                    Germany does not work centrally with the database.
                    If your account still appears as 'INACTIVE' on the VATSIM Germany homepage, click <a href="{{ route('member.refresh') }}">here</a>.
                </p>
            @else
                Bei Fragen wende dich an <code>support@vatger.de</code>.
            @endif
        </div>
        <!--end container-->
    </section>
@endsection
