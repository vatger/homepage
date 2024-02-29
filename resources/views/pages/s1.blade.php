<div>
    @component('components.layouts.content',[
        'header' => 'S1 Tower',
        'links' => [
            route('landing') => config('app.name'),'Controllers','S1 Tower'
            ]
    ])

    @endcomponent
    <section class="section">
        <div class="container">
            <div class="mb-3">
                <table class="table mb-0 table-center">
                    <thead>
                    <tr>
                        <th scope="col" class="border-bottom">Ident</th>
                        <th scope="col" class="border-bottom">Name</th>
                        <th scope="col" class="border-bottom">Frequency</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($s1stations as $s)
                    <tr>
                        <th scope="row">{{$s->ident}}</th>
                        <td>{{$s->name}}</td>
                        <td>{{$s->frequency}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>



