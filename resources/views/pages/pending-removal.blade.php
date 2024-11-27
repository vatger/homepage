@extends('layouts.master')

@section('content')
    @component('components.layouts.content',[
        'header' => 'Gesperrt',
        'links' => [
            route('landing') => config('app.name'),
            'Members',
            'Pending Removal',
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            <h2>Deine VATSIM Germany Daten werden grade gelöscht</h2>
            <h2>Your VATSIM Germany data is being deleted</h2>
            <br>
            Du hast die Löschung deiner VATSIM Germany Daten beantragt oder dich nicht in der in der Satzung genannten Frist zurückgemeldet.
            Wir Löschen deshalb zur Zeit alle deine bei VATSIM Germany hinterlegten Daten.
            Wichtig: Wir können nur Daten auf VATGER-Servern löschen, da wir keinen Zugriff auf die VATSIM weite Datenbank haben.
            Möchtest du deinen VATSIM Account löschen lassen, musst du dich an den VATSIM Support unter support.vatsim.net melden.
            Der Löschungsprozess bei uns läuft unabhängig davon und kann einige Zeit in anspruch nehmen.
            Solltest du wieder einen Account bei uns erstellen wollen, kannst du dies unten oder nach Abschluss des Löschprozesses tun.

            <hr>

            You have requested the deletion of your VATSIM Germany data or have not reported back within the deadlines specified in the statutes.
            We are therefore currently deleting all your data stored with VATSIM Germany.
            Important: We can only delete data on VATGER servers, as we do not have access to the VATSIM wide database.
            If you would like to have your VATSIM account deleted, you must contact VATSIM Support at support.vatsim.net.
            The deletion process with us runs independently of this and can take some time.
            If you want to create an account with us again, you can do so below or after the deletion process has been completed.

            <hr>
            <hr>

            <h3>Löschung abbrechen</h3>
            <h3>Cancel deletion</h3>
            <br>
            Wir haben eventuell schon einige Sachen gelöscht nicht desto trotz hast du hier die Möglichkeit die Löschung abzubrechen.
            <hr>
            We may have already deleted some things, but you still have the option of canceling the deletion here.

            <button class="btn-danger" href="{{ route('member.removal-pending.cancel') }}">Löschung abbrechen / Cancel deletion</button>

        </div>
        <!--end container-->
    </section>
@endsection
