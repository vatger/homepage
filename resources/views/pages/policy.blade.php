<div>

    @component('components.layouts.content',[
        'header' =>  $en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de,
        'subheader' => $policy->last_update,
        'links' => [
            route('landing') => config('app.name'),
            'Policies',
            $en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            @if (!empty($policy->path_changelog_de))
                <div class="container">
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Änderungsprotokoll:</h4>
                        {!! Storage::get($en && !empty($policy->path_changelog_en) ? $policy->path_changelog_en : $policy->path_changelog_de) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($policy->type == "html")
                {!! Storage::get($en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de) !!}
            @endif
            @if($policy->type == "pdf")
                <object data="{{ $en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de }}" type="application/pdf" width="100%" height="1080px">
                    <p>Unable to display PDF file. <a href="{{ $en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de }}">Download</a> instead.</p>
                </object>
            @endif
        </div>
    </section>
</div>
