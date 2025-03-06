<div>
    <section class="bg-half-170 bg-secondary d-table w-100 d-print-none">
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title">Wichtiges</h4>
                    </div>
                </div>  <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow border-0 rounded">
                        <div class="card-body">
                            <h5 class="card-title">Policies:</h5>

                            <div class="accordion pt-2">
                                @foreach($polices as $policy)
                                    <x-terms-tab
                                            :ident="$policy->id"
                                            :caption="$policy->name_de"
                                            :date="\Carbon\Carbon::create($policy->last_update)"
                                            :path="$policy->path_de"
                                            :agreed_date="$user_settings->getAgreedAt($policy->id)"
                                            :type="$policy->type"
                                    />
                                @endforeach
                            </div>

                            @if($user_settings->agreed)
                                <div class="accordion pt-2">
                                    <a href="{{ route('landing') }}" class="btn btn-success mt-2 me-2">Weiter</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section>
</div>
