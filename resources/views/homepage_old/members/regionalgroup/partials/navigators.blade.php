<div class="tab-pane fade bg-white p-4 rounded shadow" id="navigation-tab" role="tabpanel" aria-labelledby="navigation">
    <h5>@lang('regionalgroup.regionalgroup.nav-staff-text')</h5>
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
                @if (count($regionalgroup->navigators) > 0)
                    @foreach ($regionalgroup->navigators as $navigator)
                        <tr class="text-center">
                            <td>
                                @if ($navigator->pivot->chief)
                                    Navigation Chief
                                @elseif ($navigator->pivot->deputy)
                                    Navigation Deputy
                                @else
                                    Navigator
                                @endif
                            </td>
                            <td>{{ $navigator->username }}</td>
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
