<div class="tab-pane fade bg-white p-4 rounded shadow" id="event-tab" role="tabpanel" aria-labelledby="event">
    <h5>@lang('regionalgroup.regionalgroup.event-staff-text')</h5>
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
                @if (count($regionalgroup->eventler) > 0)
                    @foreach ($regionalgroup->eventler as $eventler)
                        <tr class="text-center">
                            <td>
                                @if ($eventler->pivot->chief)
                                    Event Chief
                                @elseif ($eventler->pivot->deputy)
                                    Event Deputy
                                @else
                                    Eventler
                                @endif
                            </td>
                            <td>{{ $eventler->username }}</td>
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
