<!-- Footer Start -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-py-60" style="padding-bottom: 0">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                            <img src="{{ iasset('images/vacc_logo_white.png', 300) }}" width="55%">

                            <p class="mt-4">Controlling The Virtual German Airspace With Passion!</p>

                            <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">
                                <li class="list-inline-item"><a href="https://www.facebook.com/vatger/" class="rounded" target="_blank"><i
                                            data-feather="facebook" class="fea icon-sm fea-social"></i></a></li>
                                <li class="list-inline-item"><a href="https://twitter.com/vatger" class="rounded" target="_blank"><i
                                            data-feather="twitter" class="fea icon-sm fea-social"></i></a>
                                </li>
                                <li class="list-inline-item"><a href="https://www.instagram.com/vatger/" class="rounded" target="_blank"><i
                                            data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                                <li class="list-inline-item"><a href="https://www.twitch.tv/vatsimgermany" class="rounded" target="_blank"><i
                                            data-feather="twitch" class="fea icon-sm fea-social"></i></a></li>
                                <li class="list-inline-item"><a href="https://www.youtube.com/user/vatsimgermany" class="rounded" target="_blank"><i
                                            data-feather="youtube" class="fea icon-sm fea-social"></i></a></li>
                            </ul>
                            <!--end icon-->
                        </div>
                        <!--end col-->

                        <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">VATGER</h5>
                            <ul class="list-unstyled footer-list mt-4">
                                <li><a href="{{ route('gdpr') }}" class="text-foot">@lang('general.footer.data-protection')</a></li>
                                <li><a href="{{ route('imprint') }}" class="text-foot">@lang('general.footer.imprint')</a></li>
                                <li><a href="{{ route('terms') }}" class="text-foot">@lang('general.footer.terms')</a></li>
                                <li><a href="{{ route('satzung') }}" class="text-foot">@lang('general.footer.satzung')</a></li>
                            </ul>
                        </div>
                        <!--end col-->

                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">@lang('general.footer.helpful-links')</h5>
                            <ul class="list-unstyled footer-list mt-4">
                                <li><a href="https://vatger-fv.de/" target="_blank" class="text-foot">VATGER Förderverein</a></li>
                                <li><a href="https://aip.dfs.de/BasicIFR/" target="_blank" class="text-foot">DFS AIP - Basic</a></li>
                            </ul>
                        </div>
                        <!--end col-->

                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head"></h5>
                            <a href="https://vatsim.net"><img class="mb-4" src="{{ iasset('images/vatsim/VATSIM_Logo_White_500px.png', 300) }}" width="80%"></a>
                            <a href="https://vateud.net"><img src="{{ iasset('images/vateud.png',300) }}" width="80%"></a>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->

    <div class="footer-py-30 footer-bar">
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="text-sm-start">
                        <p class="mb-0">&copy; {{ \Carbon\Carbon::now()->year }} VATSIM Germany</p>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </div>
</footer>
<!--end footer-->
<!-- Footer End -->
