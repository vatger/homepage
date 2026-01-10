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
        <div class="">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    @foreach($polices as $policy)
                        <div class="card shadow border-0 rounded mb-4">
                            <div class="card-body">
                                <div class="accordion pt-2">
                                    <x-terms-tab
                                            :ident="$policy->id"
                                            :caption="$en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de"
                                            :date="\Carbon\Carbon::create($policy->last_update)"
                                            :path="$en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de"
                                            :agreed_date="$user_settings->getAgreedAt($policy->id)"
                                            :type="$policy->type"
                                            :changelog="$en && !empty($policy->path_changelog_en) ? $policy->path_changelog_en : $policy->path_changelog_de ?? null"
                                    />
                                </div>

                                @if($user_settings->agreed)
                                    <div class="accordion pt-2">
                                        <button wire:click="continue" class="btn btn-success mt-2 me-2">Weiter</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section>
</div>
