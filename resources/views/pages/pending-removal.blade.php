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
            Deine VATSIM Germany Daten werden grade gelöscht. <br>
            Your VATSIM Germany data is being deleted.<br>
        </div>
        <!--end container-->
    </section>
@endsection
