<div class="tab-pane profile-survey-panel fade active show p-5 sm:p-8" role="tabpanel" aria-labelledby="surveykeys">
    <h5 class="text-md-start text-center">Survey Keys:</h5>
    <p class="text-muted mb-4">
        Hier siehst du alle dir zugeordneten Keys.
    </p>
    @php
        $keys = Auth::user()->surveyKeys()->get()
    @endphp
    @if($keys->count()>0)
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Token</th>
                <th>Gültig bis</th>
                <th></th>
            </tr>
            </thead>
            @foreach($keys as $k)
                <tr>
                    <td>{{ $k->name }}</td>
                    <td><code>{{ $k->token }}</code></td>
                    <td>{{ $k->valid_till }}</td>
                    <td>
                        <a href="{{ $k->url }}">
                            <button class="btn btn-sm btn-soft-info">
                                <i data-feather="external-link" class="fea icon"></i>
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="text-muted mb-4">
            Aktuell sind deinem Account keine Keys zugeordnet.
        </p>
    @endif
</div>
