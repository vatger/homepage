@extends('layouts.master')

@section('content')

    @component('components.layouts.content',[
        'header' => __('legal.gdpr'),
        'subheader' => \Carbon\Carbon::createFromTimestamp(Storage::lastModified('public/policies/gdpr.html')),
        'links' => [
            route('landing') => config('app.name'),
            'Legal',
            __('legal.gdpr')
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            {!! Storage::get('public/policies/gdpr.html') !!}
        </div>
    </section>
@endsection
