<div class="row pb-4">
    <div class="col-12 col-lg-5 col-md-12 col-sm-12">
        <button class="btn btn-sm btn-soft-primary w-100" type="button" id="open-note-modal-btn" data-bs-toggle="modal"
            data-bs-target="#addNote-modal">Notiz Erstellen | TODO</button>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-9">
        @if ($user->membershipNotes && count($user->membershipNotes) != 0)
            <div class="timeline-page pt-2 position-relative">
                @foreach ($user->membershipNotes->sortByDesc('created_at') as $note)
                    @if ($loop->index % 2 == 0)
                        <div class="timeline-item mt-4">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="duration date-label-left border rounded p-2 px-4 position-relative shadow">
                                        {{ $note->created_at->format('d.m.Y H:i') }}
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="card event event-description-right rounded shadow border-0 overflow-hidden float-start">
                                        <div class="card-body">
                                            <h5 class="mb-0 text-capitalize">{{ $note->author->username }}</h5>
                                            <p class="mt-3 mb-0 text-muted">{!! $note->note !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end timeline item-->
                    @else
                        <div class="timeline-item mt-4">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 order-sm-1 order-2">
                                    <div class="card event event-description-left rounded shadow border-0 overflow-hidden float-end">
                                        <div class="card-body">
                                            <h5 class="mb-0 text-capitalize">{{ $note->author->username }}</h5>
                                            <p class="mt-3 mb-0 text-muted">{!! $note->note !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6 col-md-6 col-sm-6 order-sm-2 order-1">
                                    <div class="duration duration-right rounded border p-2 px-4 position-relative shadow text-start">
                                        {{ $note->created_at->format('d.m.Y H:i') }}
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end timeline item-->
                    @endif
                @endforeach
            </div>
            <!--end timeline page-->
        @else
            <div class="alert alert-danger mt-3 text-center" role="alert">Keine Notiz für diesen Benutzer gefunden.</div>
        @endif
        <!-- TIMELINE END -->
    </div>
    <!--end col-->
</div>

<div class="modal fade" id="addNote-modal" tabindex="-1" aria-labelledby="createRunwayModalLabel" style="display: none;" aria-hidden="true"
    role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="tsmodal-title">Notiz Erstellen</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                        class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <div class="modal-body">
                <div class="bg-white px-3 rounded box-shadow">
                    <form id="runway-form">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">User</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-hash fea icon-sm icons">
                                            <line x1="4" y1="9" x2="20" y2="9"></line>
                                            <line x1="4" y1="15" x2="20" y2="15"></line>
                                            <line x1="10" y1="3" x2="8" y2="21"></line>
                                            <line x1="16" y1="3" x2="14" y2="21"></line>
                                        </svg>
                                        <input name="for-user" id="addnote-modal-cid-input" class="form-control ps-5" disabled
                                            value="{{ $user->id }}">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Notiz</label>
                                    <div class="form-icon position-relative">
                                        <textarea name="note" id="addnote-modal-text-input" placeholder="Notiz" data-form-type="other"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal"
                    data-form-type="other">Schließen</button>
                <button type="button" class="btn btn-sm btn-soft-primary" id="createnote-button">Erstellen</button>
            </div>
        </div>
    </div>
</div>

@push('custom-script')
    <script src="https://cdn.tiny.cloud/1/f5oxwmdtukvy1qwch4b3ghpazlyw2rzjxsljjdiis3kedxhg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Set the initial note count
        let noteCount = {{ $user->membershipNotes->count() }};

        // Initialize tinymce using global config
        const tinySettings = config.tinyMce.admin_reduced;
        tinySettings.selector = "#addnote-modal-text-input";

        tinymce.init(tinySettings);

        $(document).ready(() => {
            $("#open-note-modal-btn").on('click', function() {
                console.log(tinymce.activeEditor.setContent(""));
            });
        });
    </script>
@endpush
