<article class="mb-4 overflow-hidden rounded-xl border border-secondary-200 bg-white shadow-sm dark:border-secondary-700 dark:bg-secondary-900">
    <div class="flex flex-col gap-2 border-b border-secondary-200 bg-secondary-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-secondary-700 dark:bg-secondary-800">
        <h2 class="h5 mb-0">{{ $team->display_title }}</h2>
        <div class="flex flex-col gap-1 text-sm text-muted">
            <span>{{ $team->users->count() }} {{ $team->users->count() === 1 ? 'member' : 'members' }}</span>
            @if($team->display_email)
                <span>{{ $team->display_email }}</span>
            @endif
        </div>
    </div>

    <div class="p-0">
        @if($team->users->isEmpty())
            <p class="mb-0 px-5 py-4 text-muted">No members listed.</p>
        @else
            <ul class="mb-0 list-unstyled">
                @foreach($team->users as $user)
                    <li class="border-b border-secondary-200 px-5 py-3 last:border-b-0 dark:border-secondary-700">
                        @auth
                            <div class="flex flex-col gap-1">
                                <div>
                                    <span class="font-medium">{{ $user->staffDetails?->display_name }}</span>
                                    <span class="text-muted">({{ $user->id }})</span>
                                </div>
                                @if($user->staffDetails?->display_email)
                                    <span class="text-sm text-muted">{{ $user->staffDetails->display_email }}</span>
                                @endif
                            </div>
                        @else
                            <span class="font-medium">{{ $user->id }}</span>
                        @endauth

                        @if($user->membership_title)
                            <small class="d-block mt-1 text-muted">{{ $user->membership_title }}</small>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</article>
