<div>
    <section class="section pt-5">
        <div class="container">
            <div class="mb-5 text-center">
                <h1 class="mb-2">Staff</h1>
                <p class="text-muted mb-0">The people supporting vatger.</p>
            </div>

            <div>
                @if($teams->isEmpty())
                    <div>
                        <p class="text-center text-muted">No staff teams are currently published.</p>
                    </div>
                @else
                    @if($staffTeams->isNotEmpty())
                        <h2 class="h4 mb-3">Staff</h2>
                    @endif

                    @foreach($staffTeams as $team)
                        @include('pages.partials.staff-team', ['team' => $team])
                    @endforeach

                    @if($extendedStaffTeams->isNotEmpty())
                        <h2 class="h4 mb-3 mt-5 border-top border-secondary-200 pt-5 dark:border-secondary-700">Extended Staff</h2>

                        @foreach($extendedStaffTeams as $team)
                            @include('pages.partials.staff-team', ['team' => $team])
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </section>
</div>
