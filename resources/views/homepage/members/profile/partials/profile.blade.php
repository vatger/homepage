<div class="tab-pane fade bg-white p-4 rounded shadow active show" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <div class="pb-4 border-bottom">
        <div class="row">
            <div class="col-md-6">
                <h5>@lang('profile.profile.profile.personal-details-text'):</h5>
                <div class="mt-4">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-mail fea icon-ex-md text-muted me-3">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Email:</h6>
                            <a class="text-muted">{{ $user->email }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Region:</h6>
                            <a class="text-muted">{{ $user->vatsimDetails->region_name }}, {{ $user->vatsimDetails->region_code }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">Division:</h6>
                            <a class="text-muted">{{ $user->vatsimDetails->division_name }}, {{ $user->vatsimDetails->division_code }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-map-pin fea icon-ex-md text-muted me-3">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">vACC:</h6>
                            <a class="text-muted">
                                @if (!empty($user->vatsimDetails->subdivision_code))
                                    {{ $user->vatsimDetails->subdivision_name }}, {{ $user->vatsimDetails->subdivision_code }}
                                @else
                                    -
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-calendar fea icon-ex-md text-muted me-3">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.profile.registered-on'):</h6>
                            <a class="text-muted mb-0">
                                @if (!empty($user->vatgerDetails->registered_at))
                                    {{ $user->vatgerDetails->registered_at->format('d.m.Y') }}
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-radio fea icon-ex-md text-muted me-3">
                            <circle cx="12" cy="12" r="2"></circle>
                            <path
                                d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                            </path>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.atc-rating-text')-Rating:</h6>
                            <a class="text-muted mb-0">{{ $user->vatsimDetails->rating_atc_long }},
                                {{ $user->vatsimDetails->rating_atc_short }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-mic fea icon-ex-md text-muted me-3">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        <div class="flex-1">
                            <h6 class="text-primary mb-0">@lang('profile.profile.pilot-rating-text')-Rating:</h6>
                            <a class="text-muted mb-0">{{ $user->vatsimDetails->rating_pilot_long }},
                                {{ $user->vatsimDetails->rating_pilot_short }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

            <div class="col-md-6 pt-2 pt-sm-0">
                <h5>@lang('profile.profile.profile.description'):</h5>

                <p class="text-muted">
                    {{--
                    @if ($_user->settings->isDescriptionSet())
                        {{ $_user->settings->description }}
                    @else
                        -
                    @endif
                    --}}
                </p>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>

    {{--

    <div class="row mt-4">
        <h5>@lang('profile.profile.profile.regional-group-text'):</h5>

        @if (count($_user->regionalgroups) == 0 && $_user->regionalgroupRequests->count() == 0)
            <div class="col">
                <div class="alert alert-danger mt-3" role="alert">@lang('profile.profile.profile.regionalgroup.no-regionalgroup')</div>
            </div>
        @else
            @foreach ($_user->regionalgroups as $rgconcern)
                <div class="col col-md-6 col-lg-6 col-sm-12 mt-4">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0">
                        <div class="card-body p-0 content">
                            <span class="badge bg-soft-secondary">
                                @if ($rgconcern->pivot->guest)
                                    @lang('profile.profile.profile.regionalgroup.guest-member')
                                @else
                                    @lang('profile.profile.profile.regionalgroup.full-member')
                                @endif
                            </span>
                            <h5 class="text-primary mt-3">{{ $rgconcern->name }}</h5>
                            <p class="para text-muted mb-0">
                                @if ($rgconcern->fir)
                                    {{ $rgconcern->fir->name }}
                                @else
                                    -
                                @endif
                            </p>
                            <br>
                            <a href="{{ route('member.regionalgroup.view', ['regionalgroup' => $rgconcern]) }}"
                                class="mt-4 text-secondary">@lang('profile.profile.profile.regionalgroup.view-button-content')</a>
                        </div>
                    </div>
                </div>
                <!--end col-->
            @endforeach

            @foreach ($_user->regionalgroupRequests as $rgrequest)
                <div class="col col-md-6 col-lg-6 col-sm-12 mt-4">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0">
                        <div class="card-body p-0 content">
                            <span class="badge bg-soft-secondary">Requested</span>
                            <h5 class="text-primary mt-3">{{ $rgrequest->regionalgroup->name }}</h5>
                            <p class="para text-muted mb-0">
                                @if ($rgrequest->regionalgroup->fir)
                                    {{ $rgrequest->regionalgroup->fir->name }}
                                @else
                                    -
                                @endif
                            </p>
                            <br>
                            <form action="{{ route('member.regionalgroup.request') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="requestId" value="{{ $rgrequest->id }}">
                                <button type="submit" class="btn btn-soft-danger btn-sm">Remove request</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end col-->
            @endforeach
        @endif
    </div>

    @if (count($regionalgroups) != count($_user->regionalgroups) + count($_user->regionalgroupRequests))
        <form action="{{ route('member.regionalgroup.request') }}" method="post">
            @csrf
            <div class="row mt-4 pt-4 border-top">
                <h5 class="pb-4">@lang('profile.profile.profile.regionalgroup.join-regionalgroup'):</h5>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Todo: Text RG Select</label>
                        <div class="form-icon position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-map-pin fea icon-sm icons">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <select name="regionalgroup" id="rg-membership-regionalgroup-select" type="text" class="form-control ps-5">
                                @foreach ($regionalgroups as $rg)
                                    @if (!$_user->isMemberOfRegionalGroup($rg) && !$_user->isGuestOfRegionalGroup($rg) && !$_user->hasRegionalgroupRequest($rg))
                                        <option value="{{ $rg->id }}">{{ $rg->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!--end col-->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Membership Type</label>
                        <div class="form-icon position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-settings fea icon-sm icons">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                </path>
                            </svg>
                            <select name="type" id="rg-membership-type-select" type="text" class="form-control ps-5">
                                @if (strtolower(\Illuminate\Support\Facades\Auth::user()->userData->subdivision_code) == 'ger' && \Illuminate\Support\Facades\Auth::user()->regionalgroupRequests->count() == 0)
                                    <option value="member">@lang('general.vatger.full-member')</option>
                                @endif
                                @if (strtolower(\Illuminate\Support\Facades\Auth::user()->userData->subdivision_code) != 'ger' || \Illuminate\Support\Facades\Auth::user()->regionalgroups->count() > 0 || \Illuminate\Support\Facades\Auth::user()->regionalgroupRequests->count() == 1)
                                    <option value="guest" @if ($_user->isMemberOfAnyRegionalGroup()) selected @endif>@lang('general.vatger.guest-member')</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <p class="text-muted" id="rg-membership-info-text"></p>
                <div class="pt-4 pb-4 border-bottom mb-4 border-top" id="rg-membership-requirements-container-parent" style="display: none">
                    <div class="container">
                        <label class="form-label">Guest Requirements for this regionalgroup:</label>
                        <div class="row" id="rg-membership-requirements-container">

                        </div>
                        <!--end row-->
                    </div>
                </div>

                <div class="col-md-12" id="reason-container">
                    <div class="mb-3">
                        <label class="form-label">Reason / Message</label>
                        <div class="form-icon position-relative">
                            <textarea name="reason" id="reason" rows="4" class="form-control ps-5"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-sm btn-soft-primary" type="submit" id="rg-management-submitbutton">Send Request</button>
        </form>
    @endif

    --}}

</div>
<!--end tab pane-->

{{--

@push('custom-script')
    <script src="https://cdn.tiny.cloud/1/f5oxwmdtukvy1qwch4b3ghpazlyw2rzjxsljjdiis3kedxhg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      // Initialize tinymce using global config
      const tinySettings = config.tinyMce.default;
      tinySettings.selector = "#reason";

      tinymce.init(tinySettings);

      //TODO Fix and tidy up
      // Maybe move to modal?
      $(document).ready(() => {
        const isMemberOfRegionalgroup = () => {
          return "1" === "{{ $_user->isMemberOfAnyRegionalGroup() }}";
        };

        const rgMembershipInfoText = $("#rg-membership-info-text");
        const warningMessage = isMemberOfRegionalgroup() ? ("@lang('profile.profile.profile.regionalgroup.text-full-member-change.0')" +
          "{{ $_user->getHomeRegionalgroup() ? $_user->getHomeRegionalgroup()->name : '' }}" +
          "@lang('profile.profile.profile.regionalgroup.text-full-member-change.1')") : "";
        const rgMembershipRequirements = JSON.parse(@JSON(\App\Models\Regionalgroup\RegionalgroupMembershipRequirement::query()->get()->toJson()));

        const checkPassed =
          `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle fea icon-ex-md text-success me-2 mt-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
        const checkFailed =
          `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle fea icon-ex-md text-danger me-2 mt-1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;
        const noCheck =
          `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle fea icon-ex-md text-primary me-2 mt-1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;

        $("#rg-membership-regionalgroup-select").on("change", () => {
          validateRequirements();
        });

        $("#rg-membership-type-select").on("change", function() {
          validateRequirements();
        });

        function validateRequirements() {
          // Enable preemtively
          toggleInputs(true);

          if (isMemberOfRegionalgroup() && $("#rg-membership-type-select").val() === "member") {
            $("#rg-membership-requirements-container-parent").css("display", "none");

            rgMembershipInfoText.text(warningMessage);
            return;
          } else if ($("#rg-membership-type-select").val() === "guest") {
            let regionalgroupId = $("#rg-membership-regionalgroup-select").val();
            let requirementData;

            for (let i = 0; i < rgMembershipRequirements.length; i++) {
              if (rgMembershipRequirements[i]["regionalgroup_id"] === parseInt(regionalgroupId)) {
                requirementData = rgMembershipRequirements[i];
                break;
              }
            }

            if (!requirementData || JSON.parse(requirementData["requirements"]).length === 0) {
              $("#rg-membership-requirements-container").empty();
              $("#rg-membership-requirements-container").append(`<div class="col-md-12 col-12">
                            <div class="d-flex">
                                ${noCheck}
                                <div class="flex-1">
                                    <p class="answer text-muted mb-0">No requirements present for this regionalgroup.</p>
                                </div>
                            </div>
                        </div><!--end col-->`);
              return;
            }

            let requirements = JSON.parse(requirementData["requirements"]);

            for (let i = 0; i < requirements.length; i++) {
              $("#rg-membership-requirements-container-parent").css("display", "block");
              $("#rg-membership-requirements-container").empty();

              let checkResult = checkRequirement(requirements[i]);
              if (checkResult !== -1) {

                $("#rg-membership-requirements-container").append(`<div class="col-md-12 col-12">
                            <div class="d-flex">
                                ${checkResult ? checkPassed : checkFailed}
                                <div class="flex-1">
                                    <p class="answer text-muted mb-0">ATC Rating (Required: ${convertAtcRating(requirements[i]["min_value"])}, Your Rating: ${convertAtcRating({{ \Illuminate\Support\Facades\Auth::user()->userData->rating_atc }})}).</p>
                                </div>
                            </div>
                        </div><!--end col-->`);
                if (!checkResult) {
                  // Disabled inputs
                  toggleInputs();
                }
              }
            }
          }

          rgMembershipInfoText.text("");
        }

        function checkRequirement(checkType) {
          const atcRating = {{ \Illuminate\Support\Facades\Auth::user()->userData->rating_atc }};
          const subdiv =
            "{{ strtolower(\Illuminate\Support\Facades\Auth::user()->userData->subdivision_code) }}";

          switch (checkType["name"]) {
            case "atc_rating_extern":
              if (subdiv !== "ger") {
                return atcRating >= checkType["min_value"];
              }
              break;
            case "atc_rating_vatger":
              if (subdiv === "ger") {
                return atcRating >= checkType["min_value"];
              }
              break;
            default:
              return true;
          }

          return -1;
        }


        let toggleState = true;

        function toggleInputs(force_state) {
          if (force_state)
            toggleState = force_state;

          if (!toggleState) {
            tinymce.activeEditor.setMode("readonly");
            $("#reason-container").css("display", "none");
            $("#rg-management-submitbutton").attr("disabled", true);
          } else {
            tinymce.activeEditor.setMode("design");
            $("#reason-container").css("display", "block");
            $("#rg-management-submitbutton").attr("disabled", false);
          }

          toggleState = !toggleState;
        }
      });
    </script>
@endpush


--}}
