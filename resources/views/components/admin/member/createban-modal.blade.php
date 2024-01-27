<div wire:ignore class="modal fade" id="suspension-modal" tabindex="-1" aria-labelledby="LoginForm-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form wire:submit="saveBan">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Sperre Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal">
                        <i class="uil uil-times fs-4 text-dark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Ende (UTC)</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                                        <input name="until_date" type="datetime-local" id="suspension-end" class="form-control ps-5" wire:model="form.endDate">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Permanent</label>
                                    <div class="form-icon position-relative">
                                        <label for="suspension-permanent"></label>
                                        <input name="permanent" type="checkbox" class="form-check-input" id="suspension-permanent" wire:model="form.permanent">
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Teamspeak</label>
                                    <div class="form-icon position-relative">
                                        <label for="suspension-ts"></label>
                                        <input name="teamspeak" type="checkbox" class="form-check-input" id="suspension-ts" wire:model="form.teamspeak">
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Forum</label>
                                    <div class="form-icon position-relative">
                                        <label for="suspension-forum"></label>
                                        <input name="forum" type="checkbox" class="form-check-input" id="suspension-forum" wire:model="form.forum">
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Homepage</label>
                                    <div class="form-icon position-relative">
                                        <label for="suspension-hp"></label>
                                        <input name="homepage" type="checkbox" class="form-check-input" id="suspension-hp" wire:model="form.homepage">
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Andere Dienste</label>
                                    <div class="form-icon position-relative">
                                        <label for="suspension-other"></label>
                                        <input name="other_services" type="checkbox" class="form-check-input" id="suspension-other" wire:model="form.otherServices">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tsmodal-uuid" class="form-label">Grund</label>
                                    <div class="form-icon position-relative">
                                        <textarea class="form-control mt-0" name="reason" id="suspension-reason" rows="4" wire:model="form.reason"></textarea>
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
                    <button type="submit" class="btn btn-sm btn-danger" data-dismiss="modal" data-bs-dismiss="modal">Hinzufügen</button>
                </div>
            </div>
        </form>
    </div>
</div>
