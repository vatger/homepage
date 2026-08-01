<div class="tab-pane profile-notifications-panel active show p-5 sm:p-8" role="tabpanel" aria-labelledby="notification">
    <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <span class="profile-notifications-heading-icon">
                <i data-feather="bell" class="size-5" aria-hidden="true"></i>
            </span>
            <div>
                <h2 class="text-xl font-bold text-primary-900 dark:text-secondary-50">
                    @lang('profile.profile.notifications.title')
                </h2>
                <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-300">
                    @lang('profile.profile.notifications.description')
                </p>
            </div>
        </div>

        <label class="profile-notifications-filter" for="unread">
            <input wire:model.live="unread" class="form-check-input" type="checkbox" id="unread">
            <span>@lang('profile.profile.notifications.unread-only')</span>
        </label>
    </header>

    <div class="mt-6 grid gap-3">
        @forelse($notifications as $notification)
            <article wire:key="profile-notification-{{ $notification->id }}"
                     class="profile-notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}">
                <span class="profile-notification-status" title="{{ $notification->read_at ? __('profile.profile.notifications.read') : __('profile.profile.notifications.unread') }}">
                    <i data-feather="{{ $notification->read_at ? 'check' : 'mail' }}" class="size-4" aria-hidden="true"></i>
                    <span class="sr-only">{{ $notification->read_at ? __('profile.profile.notifications.read') : __('profile.profile.notifications.unread') }}</span>
                </span>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                        <div class="min-w-0">
                            <p class="profile-notification-source">{{ $notification->data['source_name'] }}</p>
                            <h3 class="mt-1 font-semibold text-primary-900 dark:text-secondary-50">
                                {{ $notification->data['title'] }}
                            </h3>
                        </div>
                        <time class="shrink-0 text-xs text-secondary-500 dark:text-secondary-300"
                              datetime="{{ $notification->created_at->toIso8601String() }}">
                            {{ $notification->created_at->diffForHumans() }}
                        </time>
                    </div>

                    <div class="profile-notification-message mt-3">
                        {!! $notification->data['message'] !!}
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" wire:click="notification_click('{{ $notification->id }}')" class="btn btn-sm btn-light">
                            <i data-feather="{{ $notification->read_at ? 'mail' : 'check' }}" class="size-4" aria-hidden="true"></i>
                            {{ $notification->read_at ? __('profile.profile.notifications.mark-unread') : __('profile.profile.notifications.mark-read') }}
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-secondary-300 p-10 text-center dark:border-secondary-700">
                <i data-feather="inbox" class="mx-auto size-7 text-secondary-400" aria-hidden="true"></i>
                <p class="mt-3 text-sm text-secondary-500 dark:text-secondary-300">
                    @lang('profile.profile.notifications.empty')
                </p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-6 flex justify-center sm:justify-end">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
