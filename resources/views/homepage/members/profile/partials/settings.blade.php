<div class="tab-pane fade bg-white p-4 rounded shadow" id="settings-tab" role="tabpanel" aria-labelledby="settings">
    <h5 class="text-md-start text-center">@lang('profile.profile.profile.language-appearance'):</h5>
    <form class="border-bottom">
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="p-4 pb-0">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">@lang('profile.profile.profile.language')</h6>
                        <div class="form-check" style="min-width: 30%;">
                            <form id="language-form">
                                <select class="form-select form-control" aria-label="Default select example" id="languageSelector"
                                    name="language-select">
                                    <option @if (Auth::user()->settings->language == 'de') selected @endif value="de">@lang('profile.profile.languages.german')</option>
                                    <option @if (Auth::user()->settings->language == 'en') selected @endif value="en">@lang('profile.profile.languages.english')</option>
                                </select>
                                <label class="form-check-label" for="dark-mode-selector"></label>
                            </form>
                        </div>
                    </div>
                    <form id="appearance-form">
                        <div class="d-flex justify-content-between border-top pt-4">
                            <h6 class="mb-0">@lang('profile.profile.settings.color-text')</h6>
                            <div class="form-check" style="min-width: 30%;">
                                <select class="form-select form-control" aria-label="Default select example" id="color-mode-selector"
                                    name="color-select">
                                    <option @if (Auth::user()->settings->color == 'default') selected @endif value="default">@lang('profile.profile.settings.color.dark-blue')</option>
                                    <option @if (Auth::user()->settings->color == 'cyan') selected @endif value="cyan">@lang('profile.profile.settings.color.cyan')</option>
                                    <option @if (Auth::user()->settings->color == 'red') selected @endif value="red">@lang('profile.profile.settings.color.red')</option>
                                    <option @if (Auth::user()->settings->color == 'green') selected @endif value="green">@lang('profile.profile.settings.color.green')</option>
                                    <option @if (Auth::user()->settings->color == 'purple') selected @endif value="purple">@lang('profile.profile.settings.color.purple')</option>
                                    <option @if (Auth::user()->settings->color == 'slateblue') selected @endif value="slateblue">@lang('profile.profile.settings.color.slateblue')</option>
                                    <option @if (Auth::user()->settings->color == 'skobleoff') selected @endif value="skobleoff">@lang('profile.profile.settings.color.skobleoff')</option>
                                    <option @if (Auth::user()->settings->color == 'yellow') selected @endif value="yellow">@lang('profile.profile.settings.color.yellow')</option>
                                </select>
                                <label class="form-check-label" for="noti2"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top py-4">
                            <h6 class="mb-0">@lang('profile.profile.settings.dark-mode-text')</h6>
                            <div class="form-check">
                                <input type="hidden" name="dark-mode-select" value="false" />
                                <input class="form-check-input" type="checkbox"
                                    @if (Auth::user()->settings->dark_mode) checked value="true" @else value="false" @endif id="dark-mode-selector"
                                    name="dark-mode-select">
                                <label class="form-check-label" for="dark-mode-selector"></label>
                            </div>
                        </div>
                    </form>
                    <p class="text-muted small" style="display: none" id="languageChangedMessage">For your language changes to take
                        effect, please reload the page.</p>
                </div>
            </div>
        </div>
        <!--end row-->
    </form>
    <!--end form-->

    <div class="row">
        <div class="col-lg-12 mt-4 pt-2">
            <h5>@lang('profile.profile.settings.change-local-password-title'):</h5>
            <form class="pb-4 border-bottom">
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">@lang('profile.profile.settings.old-password-text'):</label>
                            <div class="form-icon position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-key fea icon-sm icons">
                                    <path
                                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                    </path>
                                </svg>
                                <input type="password" class="form-control ps-5" placeholder="@lang('profile.profile.settings.old-password-text')" required="">
                            </div>
                        </div>
                    </div>
                    <!--end col-->

                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">@lang('profile.profile.settings.new-password-text'):</label>
                            <div class="form-icon position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-key fea icon-sm icons">
                                    <path
                                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                    </path>
                                </svg>
                                <input type="password" class="form-control ps-5" placeholder="@lang('profile.profile.settings.new-password-text')" required="">
                            </div>
                        </div>
                    </div>
                    <!--end col-->

                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">@lang('profile.profile.settings.retype-new-password-text'):</label>
                            <div class="form-icon position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-key fea icon-sm icons">
                                    <path
                                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                    </path>
                                </svg>
                                <input type="password" class="form-control ps-5" placeholder="@lang('profile.profile.settings.retype-new-password-text')" required="">
                            </div>
                        </div>
                    </div>
                    <!--end col-->

                    <div class="col-lg-12 mt-2 mb-0">
                        <button class="btn btn-soft-primary">@lang('profile.profile.settings.save-changes-button-content')</button>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </form>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="p-4 pb-0">
        <h5 class="mb-0 text-danger">Delete Account :</h5>
    </div>

    <div class="p-4 pb-0">
        <h6 class="mb-0"></h6>
        <div class="mt-1">
            <p>To delete your account, send an E-Mail to support@vatsim-germany.org</p>
        </div>
        <!--end col-->
    </div>
</div>
<!--end tab pane-->

@push('custom-script')
    <script>
        $(document).ready(function() {
            let darkModeCheckbox = $("#dark-mode-selector");
            let colorModeSelector = $("#color-mode-selector");
            let languageSelector = $("#languageSelector");
            let themeOpt = $("#theme-opt");
            let colorOpt = $("#color-opt");

            // Create Color-Array to store CSS-URLs
            let colorArray = {
                'default': '{{ asset('css/colors/default.css') }}',
                'cyan': '{{ asset('css/colors/cyan.css') }}',
                'red': '{{ asset('css/colors/red.css') }}',
                'green': '{{ asset('css/colors/green.css') }}',
                'purple': '{{ asset('css/colors/purple.css') }}',
                'slateblue': '{{ asset('css/colors/slateblue.css') }}',
                'skobleoff': '{{ asset('css/colors/skobleoff.css') }}',
                'yellow': '{{ asset('css/colors/yellow.css') }}',
            };

            // Fires, when the dark-mode setting has been changed
            darkModeCheckbox.change(function() {
                if (this.checked) {
                    themeOpt.prop('href', '{{ asset('css/app-dark.css') }}')
                    darkModeCheckbox.prop('value', 'true');
                } else {
                    themeOpt.prop('href', '{{ asset('css/app.css') }}')
                    darkModeCheckbox.prop('value', 'false');
                }

                submitAppearance();
            });

            // Fires, when the color setting has been changed
            colorModeSelector.change(function() {
                switch (this.value) {
                    case 'default':
                        colorOpt.prop('href', colorArray.default);
                        break;

                    case 'cyan':
                        colorOpt.prop('href', colorArray.cyan);
                        break;

                    case 'red':
                        colorOpt.prop('href', colorArray.red);
                        break;

                    case 'green':
                        colorOpt.prop('href', colorArray.green);
                        break;

                    case 'purple':
                        colorOpt.prop('href', colorArray.purple);
                        break;

                    case 'slateblue':
                        colorOpt.prop('href', colorArray.slateblue);
                        break;

                    case 'skobleoff':
                        colorOpt.prop('href', colorArray.skobleoff);
                        break;

                    case 'yellow':
                        colorOpt.prop('href', colorArray.yellow);
                        break;
                }

                submitAppearance();
            });

            // Fires, when the language setting has been changed
            languageSelector.change(function() {
                submitLanguage();
            });
        });

        // Submit the current appearance setting.
        function submitAppearance() {
            let actionUrl = '{{ route('api.membership.settings.submitappearance') }}';
            let data = $("#appearance-form").serialize();

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: data,
                success: function(data) {
                    new Noty({
                        text: '@lang('profile.profile.notifications.settings-saved-successfully')',
                        progressBar: true,
                        modal: false,
                        maxVisible: 1,
                        timeout: 2000,
                        layout: 'topRight',
                        type: 'success',
                    }).show();
                },
                error: function(xhr, http, error) {
                    new Noty({
                        text: '@lang('profile.profile.notifications.settings-saved-error')',
                        progressBar: true,
                        modal: false,
                        maxVisible: 1,
                        timeout: 2000,
                        layout: 'topRight',
                        type: 'error',
                    }).show();
                }
            })
        }

        function submitLanguage() {
            let actionUrl = '{{ route('api.membership.settings.setLanguage') }}';

            $.ajax({
                type: 'PUT',
                url: actionUrl,
                data: {
                    "lang": $("#languageSelector").val()
                },
                success: function(data) {
                    new Noty({
                        text: '@lang('profile.profile.notifications.settings-saved-successfully')',
                        progressBar: true,
                        modal: false,
                        maxVisible: 1,
                        timeout: 2000,
                        layout: 'topRight',
                        type: 'success',
                    }).show();

                    if ($("#languageSelector").val() === 'de')
                        $("#languageChangedMessage").text(
                            "Du hast deine Spracheinstellungen geändert. Lade die Seite neu, damit deine Änderungen übernommen werden."
                        );
                    else
                        $("#languageChangedMessage").text(
                            "You have changed your language settings. For these changes to take effect, please reload the page."
                        )

                    $("#languageChangedMessage").css('display', 'block');

                },
                error: function(xhr, http, error) {
                    new Noty({
                        text: '@lang('profile.profile.notifications.settings-saved-error')',
                        progressBar: true,
                        modal: false,
                        maxVisible: 1,
                        timeout: 2000,
                        layout: 'topRight',
                        type: 'error',
                    }).show();
                }
            })
        }
    </script>
@endpush
