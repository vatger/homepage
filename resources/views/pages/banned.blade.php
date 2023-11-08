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
            You are banned


            <!--end row-->
        </div>
        <!--end container-->
    </section>
@endsection
