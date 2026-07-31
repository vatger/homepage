@extends('layouts.master')

@section('content')
    @component('components.layouts.content',[
        'header' => __('pages.pending-removal.title'),
        'links' => [
            route('landing') => config('app.name'),
            __('pages.common.members'),
            __('pages.pending-removal.breadcrumb'),
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            <h2>@lang('pages.pending-removal.heading')</h2>
            <p>@lang('pages.pending-removal.text')</p>

            <hr>

            <h5>@lang('pages.pending-removal.cancel-title')</h5>
            <p>@lang('pages.pending-removal.cancel-text')</p>

            <a class="btn btn-sm btn-danger" href="{{ route('member.removal-pending.cancel') }}">
                @lang('pages.pending-removal.cancel-button')
            </a>
        </div>
        <!--end container-->
    </section>
@endsection
