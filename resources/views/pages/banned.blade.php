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
            Bei Fragen wende dich an <code>support@vatger.de</code>.
        </div>
        <!--end container-->
    </section>
@endsection
