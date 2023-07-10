<div class="tab-pane fade bg-white p-4 rounded shadow" id="contact-tab" role="tabpanel" aria-labelledby="contact">
    <h5>@lang('regionalgroup.regionalgroup.contact-text')</h5>
    <div class="p-4 table-responsive pb-4">
        <table class="w-100 table table-center">
            <thead>
                <tr>
                    <th class="text-center fw-bold border-bottom">Position</th>
                    <th class="text-center fw-bold border-bottom">Name</th>
                    <th class="text-center fw-bold border-bottom">Contact</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>RG Chief</td>
                    <td>{{ $regionalgroup->chief != null ? $regionalgroup->chief->username : 'VACANT' }}</td>
                    <td>{{ $regionalgroup->email }}</td>
                </tr>
                <tr class="text-center" style="border-bottom-style: double !important;">
                    <td>RG Deputy</td>
                    <td>{{ $regionalgroup->deputy ? $regionalgroup->deputy->username : 'VACANT' }}</td>
                    <td>{{ $regionalgroup->email }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--end tab pane-->
