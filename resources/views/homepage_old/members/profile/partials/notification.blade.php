<div class="tab-pane fade bg-white p-4 rounded shadow" id="notification-tab" role="tabpanel" aria-labelledby="notification">

    <div class="d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Notifications:</h5>
    </div>

    <div class="d-flex border-bottom align-items-center justify-content-between bg-light mt-4 p-3">
        <div class="form-check ps-0">
            <div class="mb-0">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="selectall">
                    <label class="form-check-label" for="selectall">Select all</label>
                </div>
            </div>
        </div>

        <div class="btn-group dropdown-primary me-2 mt-2">
            <button type="button" class="btn btn-sm btn-soft-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                Action
            </button>
            <div class="dropdown-menu">
                <a href="javascript:void(0)" class="dropdown-item"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-eye fea icon-sm">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg> Mark Read</a>
                <a href="javascript:void(0)" class="dropdown-item"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-eye-off fea icon-sm">
                        <path
                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                        </path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg> Mark Unread</a>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trash-2 fea icon-sm">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg> Delete</a>
            </div>
        </div>
    </div>

    <div id="notification-container"></div>

    <div class="d-flex align-items-center justify-content-between mt-4">
        <span class="text-muted h6 mb-0">Showing <span id="notification-current-count"></span> out of <span
                id="notification-total-count"></span></span>
        <a href="javascript:void(0)" class="btn btn-sm btn-soft-primary" id="notification-loadmore-button">Load more</a>
    </div>
</div>

@push('custom-script')
    <script>
        let g_notification_count = 0;
        let g_page = 1;
        const g_url = "{{ route('member.profile.notifications') }}";

        $(document).ready(() => {
            let allSelected = false;

            loadNotifications(g_page);

            $("#notification-loadmore-button").on('click', () => {
                loadNotifications(g_page);
            });

            $("#selectall").on('change', function() {
                allSelected = $(this).prop('checked');

                for (let i = 0; i < g_notification_count; i++) {
                    allSelected ? $(`#notification-checkbox-${i}`).prop('checked', true) : $(
                        `#notification-checkbox-${i}`).prop('checked', false);
                }
            })
        });

        function loadNotifications(pageId) {
            $.ajax({
                url: g_url,
                method: 'GET',
                data: {
                    page: pageId,
                },
                success: (data) => {
                    g_notification_count += data['data'].length;
                    $("#notification-current-count").text(g_notification_count);
                    $("#notification-total-count").text(data['total']);

                    if (data['current_page'] === data['last_page']) $("#notification-loadmore-button").remove();

                    $.each(data['data'], (key, value) => {
                        $("#notification-container").append(`<div class="d-flex border-bottom p-3 ${key % 2 == 1 ? "bg-light" : ""}">
                                                            <div class="form-check ps-0">
                                                                <div class="mb-0">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="${value['id']}" id="notification-checkbox-${key + (10 * (g_page - 1))}">
                                                                        <label class="form-check-label" for="mail1"></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex ms-2">
                                                                <img src="https://cdn.discordapp.com/emojis/851556706542551150.webp?size=80&quality=lossless" class="avatar avatar-md-sm rounded-pill shadow" alt="">
                                                                <div class="flex-1 ms-3">
                                                                    <h6 class="text-dark">${value['data']['title']}</h6>
                                                                    <p class="text-muted mb-0">${value['data']['message']}</p>
                                                                </div>
                                                            </div>
                                                        </div>`);
                    });

                    g_page++;
                },
                error: (data) => {

                }
            });
        }
    </script>
@endpush
