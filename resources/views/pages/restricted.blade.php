<div>
    @component('components.layouts.content',[
        'header' => 'Restricted Stations',
        'links' => [
            route('landing') => config('app.name'),'Controllers','Restricted stations'
            ]
    ])

    @endcomponent
    <section class="section">
        <div class="container">
            <div class="mb-3">
                <label class="form-label text-primary">Choose restriction type<span class="text-danger">*</span></label>
                <select wire:model.live="restriction" class="form-select form-control" aria-label="RestrictionChooser">
                    <option selected></option>
                    @foreach($rests as $r )
                        <option value="{{$r->id}}" @if($restriction == $r->id) selected @endif>{{$r->desc}}</option>
                    @endforeach
                </select>
            </div>
        </div>
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
                    @foreach($fstations as $s)
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



