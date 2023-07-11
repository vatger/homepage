<div class="row px-4 table-responsive">
    <table class="table table-center bg-white mb-0">
        <thead>
            <tr class="text-center">
                <th class="border-bottom p-3" style="width: 40%">UUID</th>
                <th class="border-bottom p-3" style="width: 35%">Zuletzt Genutzt</th>
                <th class="border-bottom p-3" style="width: 15%">Aktion</th>
            </tr>

        </thead>
        <tbody id="member-list-content">
            @if (count($user->teamspeakRegistrations) == 0)
                <td colspan="3" class="text-center text-muted">Keine Registrierungen</td>
            @else
                @foreach ($user->teamspeakRegistrations as $reg)
                    <tr class="text-center">
                        <td>{{ $reg->uid }}</td>
                        <td>{{ $reg->last_login->format('d.m.Y H:i') }}z</td>
                        <td>
                            <button type="button" class="teamspeak-registrations-view-button btn btn-sm btn-soft-primary"
                                data-regid="{{ $reg->id }}">Anzeigen</button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <p class="text-muted mt-2" id="dataset-length"></p>

    <ul class="pagination mb-0 mt-4" style="display: none">

    </ul>
</div>

@push('custom-script')
    <script>
        $(document).ready(() => {
            const uuidfield = $("#tsmodal-uuid");
            const lastosfield = $("#tsmodal-lastos");
            const regipfield = $("#tsmodal-regip");
            const lastipfield = $("#tsmodal-lastip");
            const lastloginfield = $("#tsmodal-lastlogin");
            const regdatefield = $("#tsmodal-regdate");

            $(".teamspeak-registrations-view-button").on('click', function() {
                let regid = $(this).data('regid');

                if (!regid) {
                    showNoty("Ein Fehler ist aufgetreten. Versuche es bitte erneut", "error");
                }

                uuidfield.val("Laden...");
                lastosfield.val("Laden...");
                regipfield.val("Laden...");
                lastipfield.val("Laden...");
                lastloginfield.val("Laden...");
                regdatefield.val("Laden...");

                $("#tsmodal-title").text(`Teamspeak Informationen`);
                $("#teamspeak-modal").modal('toggle');

                //2022-05-02T14:12:33.000
                //2015-03-04T00:00:00.000Z

                $.ajax({
                    url: "{{ route('api.administration.membership.member.teamspeak') }}",
                    method: "GET",
                    data: {
                        tsid: regid
                    },
                    success: (data) => {
                        console.log(data);
                        uuidfield.val(data['uid']);
                        lastosfield.val(data['last_os'] ? data['last_os'] : "N/A");
                        regipfield.val(data['registration_ip']);
                        lastipfield.val(data['last_ip']);

                        let created_at = data['created_at'];
                        let lastLoginDate = formatDate(data['last_login']);
                        let registrationDate = created_at ? formatDate(data['created_at']
                            .toString().substring(0, 23) + "Z") : null;

                        lastLoginDate ? lastloginfield.val(`${lastLoginDate}`) : lastloginfield
                            .val("-");
                        registrationDate ? regdatefield.val(`${registrationDate}`) : regdatefield
                            .val("-");
                    },
                    error: (data) => {
                        showNoty(`Ein Fehler ist aufgetreten. Versuche es bitte erneut.`, "error");
                        setTimeout(() => {
                            $("#teamspeak-modal").modal('hide');
                        }, 500);
                    }
                });
            });
        });
    </script>
@endpush
