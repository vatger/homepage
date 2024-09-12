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
                                <x-terms-tab ident="gdpr" caption="GDPR" :date="$gdpr_date" :text="$gdpr" :agreed_date="$user_settings?->gdpr_agreed_at" />
                                <x-terms-tab ident="imprint" caption="Imprint" :date="$imprint_date" :text="$imprint" :agreed_date="$user_settings?->imprint_agreed_at" />
                                <x-terms-tab ident="termsofuse" caption="Nutzungsbedingungen" :date="$termsofuse_date" :text="$termsofuse" :agreed_date="$user_settings?->termsofuse_agreed_at" />
                                <x-terms-tab ident="satzung" caption="Satzung" :date="$satzung_date" :text="$satzung" :agreed_date="$user_settings?->satzung_agreed_at" pdf_type="true" />
                            </div>

                            @if(Auth::user()?->settings?->agreed)
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
