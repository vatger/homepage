<div>
    <section class="bg-half-170 bg-light d-table w-100 d-print-none">
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
                            {{--
                            <h5 class="card-title">Introduction :</h5>
                            <p class="text-muted">
                            </p>
                            --}}

                            <h5 class="card-title">Policies:</h5>

                            <div class="accordion pt-2">
                                <x-terms-tab ident="gdpr" caption="GDPR" :date="$gdpr_date" :text="$gdpr" />
                                <x-terms-tab ident="imprint" caption="Imprint" :date="$imprint_date" :text="$imprint" />
                                <x-terms-tab ident="termsofuse" caption="Nutzungsbedingungen" :date="$termsofuse_date" :text="$termsofuse" />
                                <x-terms-tab ident="satzung" caption="Satungun" :date="$satzung_date" :text="$satzung" />
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section>
</div>
