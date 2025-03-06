<div>

    @component('components.layouts.content',[
        'header' =>  $policy->name_de,
        'subheader' => $policy->last_update,
        'links' => [
            route('landing') => config('app.name'),
            'Policies',
            $policy->name_de
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            @if($policy->type == "html")
                {!! Storage::get($policy->path_de) !!}
            @endif
            @if($policy->type == "pdf")
                <object data="{{ $policy->path_de }}" type="application/pdf" width="100%" height="800px">
                    <p>Unable to display PDF file. <a href="{{ $policy->path_de }}">Download</a> instead.</p>
                </object>
            @endif
        </div>
    </section>

</div>
