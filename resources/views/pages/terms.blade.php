@extends('layouts.master')

@section('content')

    @component('components.layouts.content',[
        'header' => __('legal.terms'),
        'subheader' => \Carbon\Carbon::createFromTimestamp(Storage::lastModified('public/policies/termsofuse.html')),
        'links' => [
            route('landing') => config('app.name'),
            'Legal',
            __('legal.terms')
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            {!! Storage::get('public/policies/termsofuse.html') !!}
        </div>
    </section>
@endsection
