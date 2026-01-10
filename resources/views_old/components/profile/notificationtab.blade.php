<div class="tab-pane fade bg-white p-4 rounded shadow active show" role="tabpanel" aria-labelledby="notification">
    <div>
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Notifications:</h5>
        </div>

        <div class="d-flex border-bottom align-items-center justify-content-between bg-light mt-4 p-3">

            <div class="form-check ps-0">
                <div class="mb-0">
                    <div class="form-check">
                        <input wire:model.live="unread" class="form-check-input" type="checkbox" id="unread">
                        <label class="form-check-label" for="unread">Unread only</label>
                    </div>
                </div>
            </div>
            {{--

                        <div class="btn-group dropdown-primary me-2 mt-2">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a href="javascript:void(0)" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-eye-off fea icon-sm">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                    Mark Unread</a>
                                <a href="javascript:void(0)" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-corner-up-left fea icon-sm">
                                        <polyline points="9 14 4 9 9 4"></polyline>
                                        <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                                    </svg>
                                    Reply</a>
                                <a href="javascript:void(0)" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-corner-up-right fea icon-sm">
                                        <polyline points="15 14 20 9 15 4"></polyline>
                                        <path d="M4 20v-7a4 4 0 0 1 4-4h12"></path>
                                    </svg>
                                    Forward</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" class="dropdown-item text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-trash-2 fea icon-sm">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                    Delete</a>
                            </div>
                        </div>
                        --}}
        </div>

        @foreach($notifications as $notification)
            <div class="d-flex border-bottom p-3 {{ $loop->even ?  'bg-light' : ''}}">
                <div class="form-check ps-0">
                    <div class="mb-0">
                        <i data-feather="{{ $notification->read_at ? 'check-circle' : 'circle' }}" class="fea text-muted me-3"></i>
                    </div>
                </div>
                <div style="width: 100%">
                    <div class="d-flex ms-2">
                        {{--<img src="assets/images/client/01.jpg" class="avatar avatar-md-sm rounded-pill shadow" alt="">--}}
                        <div class="flex-1 ms-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h8 class="text-dark">{{ $notification->data['source_name'] }}:</h8>
                                    <h6 class="text-dark mt-1">{{ $notification->data['title'] }}</h6>
                                </div>
                                <div>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    <br>
                                    <button wire:click="notification_click('{{ $notification->id }}')" class="btn btn-sm btn-soft-light">mark {{ $notification->read_at ? 'unread' : 'read' }}</button>
                                </div>
                            </div>

                            <p class="text-muted mb-0">{!!$notification->data['message'] !!}</p>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex align-items-center justify-content-between mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
