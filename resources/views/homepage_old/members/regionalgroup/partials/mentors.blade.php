<div class="tab-pane fade bg-white p-4 rounded shadow" id="mentoring-tab" role="tabpanel" aria-labelledby="mentoring">
    <h5>@lang('regionalgroup.regionalgroup.mentoring-staff-text')</h5>
    <div class="p-4 table-responsive">
        <table class="w-100 table table-center">
            <thead>
                <tr>
                    <th class="text-center fw-bold border-bottom">Position</th>
                    <th class="text-center fw-bold border-bottom">Name</th>
                    <th class="text-center fw-bold border-bottom">Contact</th>
                </tr>
            </thead>
            <tbody>
                @if (count($regionalgroup->mentors) > 0)
                    @foreach ($regionalgroup->mentors as $mentor)
                        <tr class="text-center">
                            <td>
                                @if ($mentor->pivot->chief)
                                    Mentoring Chief
                                @elseif ($mentor->pivot->senior)
                                    Senior Mentor
                                @else
                                    Mentor
                                @endif
                            </td>
                            <td>{{ $mentor->username }}</td>
                            <td>-</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center text-muted">@lang('regionalgroup.table-empty-text')</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!--end tab pane-->
