<div class="tab-pane fade bg-white p-4 rounded shadow active show" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <h5 class="text-md-start text-center">@lang('profile.profile.profile.language-appearance'):</h5>
    <form class="">
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="p-4 pb-0">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">@lang('profile.profile.profile.language')</h6>
                        <div class="form-check" style="min-width: 30%;">
                            <form id="language-form">
                                <select wire:model.live="language" class="form-select form-control" id="language-selector"
                                        name="language-select">
                                    <option value="de">@lang('profile.profile.languages.german')</option>
                                    <option value="en">@lang('profile.profile.languages.english')</option>
                                </select>
                                <label class="form-check-label" for="language-selector"></label>
                            </form>
                        </div>
                    </div>
                    <form id="appearance-form">
                        <div class="d-flex justify-content-between border-top pt-4">
                            <h6 class="mb-0">@lang('profile.profile.settings.color-text')</h6>
                            <div class="form-check" style="min-width: 30%;">
                                <select wire:model.live="color" class="form-select form-control" id="color-mode-selector" name="color-select">
                                    <option value="default">@lang('profile.profile.settings.color.dark-blue')</option>
                                    <option value="cyan">@lang('profile.profile.settings.color.cyan')</option>
                                    <option value="red">@lang('profile.profile.settings.color.red')</option>
                                    <option value="green">@lang('profile.profile.settings.color.green')</option>
                                    <option value="purple">@lang('profile.profile.settings.color.purple')</option>
                                    <!--<option value="slateblue">@lang('profile.profile.settings.color.slateblue')</option>-->
                                    <option value="skobleoff">@lang('profile.profile.settings.color.skobleoff')</option>
                                    <option value="yellow">@lang('profile.profile.settings.color.yellow')</option>
                                </select>
                                <label class="form-check-label" for="color-mode-selector"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top py-4">
                            <h6 class="mb-0">@lang('profile.profile.settings.dark-mode-text')</h6>
                            <div class="form-check">
                                <input wire:model.live="darkmode" class="form-check-input" type="checkbox" id="dark-mode-selector" name="dark-mode-select">
                                <label class="form-check-label" for="dark-mode-selector"></label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end row-->
    </form>
    <!--end form-->

    <h5 class="text-md-start text-center mt-4">Settings:</h5>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="p-4 pb-0">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-0">Ical URL:</h6>
                    <div class="form-check" style="min-width: 30%;">
                        <code>{{ $ical }}</code>
                        <br>
                        <button wire:click="new_ical_token" class="btn btn-icon btn-primary"><i data-feather="refresh-cw" class="fea icon-sm"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

</div>
