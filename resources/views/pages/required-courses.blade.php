<div>
    @component('components.layouts.content',[
        'header' => 'Required Courses',
        'links' => [
            route('landing') => config('app.name'),'Controllers','Required Courses'
            ]
    ])

    @endcomponent
    <section class="section">
        <div class="container">
            <div class="alert bg-soft-primary fw-medium" role="alert">
                <i data-feather="info" class="fea fs-5 align-middle me-1"></i>
                Welcome to our Moodle Courses and Positions Overview!
            </div>

            <div class="mb-3">
                <table class="table mb-0 table-center">
                    <thead>
                    <tr>
                        <th scope="col" class="border-bottom">Station</th>
                        <th scope="col" class="border-bottom">Courses</th>
                        <th scope="col" class="border-bottom">FIR</th>
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
