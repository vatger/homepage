@extends('layouts.master')

@section('content')

    @component('components.layouts.content',[
        'header' => __('legal.imprint'),
        'subheader' => \Carbon\Carbon::createFromTimestamp(Storage::lastModified('public/policies/imprint.html')),
        'links' => [
            route('landing') => config('app.name'),
            'Legal',
            __('legal.imprint')
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            {!! Storage::get('public/policies/imprint.html') !!}
        </div>
    </section>
@endsection
