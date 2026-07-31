<div>
    @component('components.layouts.content',[
       'header' => __('pages.getting-started.title'),
       'links' => [
           route('landing') => config('app.name'),
       __('pages.getting-started.title'),
       ],
    ])
    @endcomponent

    <div class="container mt-100 pb-5">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 d-lg-block d-none">
                <div class="sidebar sticky-bar p-4 rounded shadow">
                    <div class="widget border-bottom pb-4">
                        <div class="progress-box mt-2">
                            <p class="title text-muted mb-2">
                                {{ __('pages.getting-started.completed', ['completed' => $steps_completed, 'total' => $steps_total]) }}
                            </p>

                            <div class="progress">
                                <div class="progress-bar position-relative bg-primary" style="width: {{$steps_completed / $steps_total * 100}}%;"></div>
                            </div>
                        </div><!--end process box-->
                    </div>

                    <div class="widget mt-2">
                        <ul class="list-unstyled sidebar-nav mb-0">
                            <li class="navbar-item px-0">
                                <a wire:click="setStep(1)" class="{{$completed1 ? 'text-success':'text-muted'}} {{$step ==1 ? 'bg-soft-success':''}} d-flex rounded shadow align-items-center py-2
                                px-2">
                                    <h6 class="mb-0 ms-2">1. @lang('pages.getting-started.steps.vatsim-registration')</h6>
                                </a>
                            </li>

                            <!-- Class text-success if <a> is completed -->

                            <li class="navbar-item  account-menu px-0 mt-2">
                                <a wire:click="setStep(2)" class="{{$completed2 ? 'text-success':'text-muted'}} {{$step ==2 ? 'bg-soft-success':''}} d-flex rounded shadow
                                align-items-center py-2 px-2">
                                    <h6 class="mb-0 ms-2">2. @lang('pages.getting-started.steps.vatger-registration')</h6>
                                </a>
                            </li>

                            <li class="navbar-item account-menu px-0 mt-2">
                                <a wire:click="setStep(3)" class="{{$completed3 ? 'text-success':'text-muted'}} {{$step ==3 ? 'bg-soft-success':''}} d-flex rounded shadow align-items-center py-2
                                px-2">
                                    <h6 class="mb-0 ms-2">3. @lang('pages.getting-started.steps.orientation-test')</h6>
                                </a>
                            </li>

                            <li class="navbar-item account-menu px-0 mt-2">
                                <a wire:click="setStep(4)" class="{{$completed4 ? 'text-success':'text-muted'}} {{$step ==4 ? 'bg-soft-success':''}} d-flex rounded shadow align-items-center py-2
                                px-2">
                                    <h6 class="mb-0 ms-2">4. @lang('pages.getting-started.steps.assignment')</h6>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-8 col-12">
                @switch($this->step)
                    @case(1)
                        @component('components.getting-started.vatsim-registration')@endcomponent
                        @break
                    @case(2)
                        @component('components.getting-started.vatger-registration')@endcomponent
                        @break
                    @default
                        <div class="alert bg-soft-primary">@lang('pages.getting-started.not-available')</div>
                @endswitch

            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
