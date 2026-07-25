@extends('layouts.master')

@section('content')
    @component('components.layouts.content',[
        'header' => __('pages.banned.title'),
        'links' => [
            route('landing') => config('app.name'),
            __('pages.common.members'),
            __('pages.banned.breadcrumb'),
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            <p>@lang('pages.banned.intro')</p>
            <!--end row-->
            @if($ban)
                <p><b>@lang('pages.banned.reason')</b> {{ $ban->type }}</p>
                <p>{{ $ban->reason }}</p>
            @endif
            @if($ban?->type == \App\Models\Membership\UserBanType::vatsim_inactivity)
                <p>{!! __('pages.banned.inactive') !!}</p>
            @elseif($ban?->type == \App\Models\Membership\UserBanType::pilot_rating_incomplete)
                <p>@lang('pages.banned.orientation-test')</p>
            @else
                <p>{!! __('pages.banned.support') !!}</p>
            @endif
            <p>{!! __('pages.banned.refresh', ['url' => route('member.refresh')]) !!}</p>
        </div>
        <!--end container-->
    </section>
@endsection
