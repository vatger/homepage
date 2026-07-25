<div>

    @component('components.layouts.content',[
         'header' => __('sdp.text-header'),
         'links' => [
             route('landing') => config('app.name'),
             __('pages.common.members'),
             __('sdp.text-header'),
             ]
     ])
    @endcomponent

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="alert alert-outline-primary mb-0" role="alert">
                    <h4 class="alert-heading">@lang('sdp.text-welcome-header')</h4>
                    <p>@lang('sdp.text-welcome-hedaer-sub')</p>
                    <p class="mb-0 border-top pt-3">@lang('sdp.text-sdp')</p>
                </div>
            </div>
            <div class="container">
                <div class="row" >
                    <br>
                </div>
            <div class="row" >
                <a href="#" class="btn btn-pills btn-primary" wire:click="accept()" wire:loading.attr="disabled">@lang('sdp.text-accept')</a>
            </div>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
