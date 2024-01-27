@props(['banInformation' => null])

<div wire:ignore.self class="modal fade" id="suspension-modal-view" tabindex="-1" aria-labelledby="LoginForm-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="tsmodal-title">Sperre Ansehen</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal">
                    <i class="uil uil-times fs-4 text-dark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="bg-white px-3 rounded box-shadow">
                    <div wire:loading.class="opacity-5" class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Ende (UTC)</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="calendar" class="fea icon-sm icons"></i>
                                    <input disabled type="text" id="suspension-end" class="form-control disabled ps-5" value="{{$banInformation?->ends_at?->format('d.m.Y H:i') ?? 'Permanent'}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Ausgesprochen Durch</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="calendar" class="fea icon-sm icons"></i>
                                    <input disabled name="until_date" type="text" id="suspension-end" class="form-control disabled ps-5" value="{{$banInformation?->author?->username}} ({{$banInformation?->author?->id}})">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Permanent</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="check-circle" class="fea icon-sm icons"></i>
                                    <input disabled name="until_date" type="text" id="suspension-end" class="form-control disabled ps-5" value="{{$banInformation?->permanent ? 'Ja' : 'Nein'}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-6 pe-1">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Teamspeak</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="check-circle" class="fea icon-sm icons"></i>
                                    <input disabled name="until_date" type="text" id="suspension-end" class="form-control disabled ps-5" value="{{$banInformation?->teamspeak ? 'Ja' : 'Nein'}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-6 ps-1">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Homepage</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="check-circle" class="fea icon-sm icons"></i>
                                    <input disabled name="until_date" type="text" id="suspension-end" class="form-control disabled ps-5" value="{{$banInformation?->homepage ? 'Ja' : 'Nein'}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-6 pe-1">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Forum</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="check-circle" class="fea icon-sm icons"></i>
                                    <input disabled type="text" class="form-control disabled ps-5" value="{{$banInformation?->forum ? 'Ja' : 'Nein'}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-6 ps-1">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Andere Dienste</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="check-circle" class="fea icon-sm icons"></i>
                                    <input disabled type="text" class="form-control disabled ps-5" value="{{$banInformation?->other_services ? 'Ja' : 'Nein'}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="tsmodal-uuid" class="form-label">Grund</label>
                                <div class="form-icon position-relative">
                                    <textarea disabled class="form-control disabled mt-0" rows="4">{{strlen($banInformation?->reason) == 0 ? "Kein Grund angegeben" : $banInformation?->reason}}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Schließen</button>

                @if($banInformation?->type == \App\Models\Membership\User\UserBanType::vatger_ban)
                    <button type="button" class="btn btn-sm btn-danger" wire:loading.class="disabled" data-dismiss="modal" data-bs-dismiss="modal" wire:click="removeBan()">Löschen</button>
                @endif

                @if($banInformation?->isActive && $banInformation?->type == \App\Models\Membership\User\UserBanType::vatger_ban)
                    <button type="button" class="btn btn-sm btn-danger" wire:loading.class="disabled" data-dismiss="modal" data-bs-dismiss="modal" wire:click="endBanNow()">Jetzt Beenden</button>
                @endif
            </div>
        </div>
    </div>
</div>
