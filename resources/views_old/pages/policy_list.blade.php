<div>
    @component('components.layouts.content',[
        'header' => 'Policies',
        'links' => [
            route('landing') => config('app.name'),
            'Policies',
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            <ul class="list-group">
                @foreach($policies as $policy)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('policies', ['policy_id' => $policy->id]) }}" target="_self">
                            {{ $en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de }}
                        </a>
                        <span class="text">{{ Carbon\Carbon::parse($policy->last_update)->format('d.m.Y') }}</span>
                    </li>
                @endforeach
            </ul>
        </div><!--end container-->
    </section>
</div>
