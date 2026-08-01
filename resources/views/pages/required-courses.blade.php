<div>
    @component('components.layouts.content',[
        'header' => __('pages.required-courses.title'),
        'links' => [
            route('landing') => config('app.name'),
            __('navigation.lotsen.titel'),
            __('pages.required-courses.title')
            ]
    ])

    @endcomponent
    <section class="section">
        <div class="container">
            <x-controller.staffing-tool-link />

            <div class="alert bg-soft-primary fw-medium" role="alert">
                <i data-feather="info" class="fea fs-5 align-middle me-1"></i>
                @lang('pages.required-courses.text')
            </div>

            <div class="mb-3">
                <table class="table mb-0 table-center">
                    <thead>
                    <tr>
                        <th scope="col" class="border-bottom">@lang('pages.required-courses.station')</th>
                        <th scope="col" class="border-bottom">@lang('pages.required-courses.courses')</th>
                        <th scope="col" class="border-bottom">@lang('pages.required-courses.fir')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $c)
                        <tr>
                            <th scope="row">{{$c->station}}</th>
                            <td>
                                <ul class="list-group">
                                    @foreach($c->courses as $cc)
                                        <li class="list-group">
                                            <a class="link-primary text-primary" href="{{ $cc->link }}">{{ $cc->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{$c->fir}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
