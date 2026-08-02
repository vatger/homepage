<div class="tab-pane active show p-5 sm:p-8" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <section class="profile-setting-section">
        <h2 class="text-xl font-bold text-primary-900 dark:text-secondary-50">
            @lang('profile.profile.profile.language-appearance')
        </h2>

        <div class="mt-5">
            <div class="profile-setting-row">
                <div>
                    <h3 class="font-semibold text-primary-900 dark:text-secondary-50">@lang('profile.profile.profile.language')</h3>
                </div>
                <div class="profile-setting-actions sm:min-w-64">
                    <label class="sr-only" for="language-selector">@lang('profile.profile.profile.language')</label>
                    <select wire:model.live="language" class="form-select" id="language-selector" name="language-select">
                        <option value="de">@lang('profile.profile.languages.german')</option>
                        <option value="en">@lang('profile.profile.languages.english')</option>
                    </select>
                </div>
            </div>

            <div class="profile-setting-row">
                <h3 class="font-semibold text-primary-900 dark:text-secondary-50">@lang('profile.profile.settings.dark-mode-text')</h3>
                <div class="profile-setting-actions">
                    <input wire:model.live="darkmode" class="form-check-input size-5" type="checkbox" id="dark-mode-selector" name="dark-mode-select">
                    <label class="form-check-label" for="dark-mode-selector">@lang('profile.profile.settings.dark-mode-text')</label>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-setting-section">
        <h2 class="text-xl font-bold text-primary-900 dark:text-secondary-50">Settings</h2>
        <div class="mt-5">
            <div class="profile-setting-row">
                <div>
                    <h3 class="font-semibold text-primary-900 dark:text-secondary-50">iCalendar Link for bookings</h3>
                </div>
                <div class="profile-setting-actions">
                    @if(!empty($ical))
                        <a href="{{ $ical }}" class="btn btn-light">iCalendar URL</a>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $ical }}');" class="btn btn-primary">
                            <i data-feather="clipboard" class="size-4"></i> Copy
                        </button>
                        <button type="button" wire:click="new_ical_token" class="btn border-danger-200 bg-danger-50 text-danger-800 hover:bg-danger-100 dark:border-danger-800 dark:bg-danger-950 dark:text-danger-200">
                            <i data-feather="refresh-cw" class="size-4"></i> Renew
                        </button>
                    @else
                        <button type="button" wire:click="new_ical_token" class="btn btn-primary">
                            <i data-feather="calendar" class="size-4"></i> Generate link
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="profile-setting-section">
        <h2 class="text-xl font-bold text-primary-900 dark:text-secondary-50">Accounts</h2>
        <div class="mt-5">
            <div class="profile-setting-row">
                <div>
                    <h3 class="font-semibold text-primary-900 dark:text-secondary-50">VATSIM Account</h3>
                    <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-300"><strong>ID</strong> {{ $user->id }}</p>
                </div>
                <div class="profile-setting-actions">
                    <a href="https://my.vatsim.net/profile" class="btn btn-light"><i data-feather="user" class="size-4"></i> Manage</a>
                </div>
            </div>

            <div class="profile-setting-row">
                <div>
                    <h3 class="font-semibold text-primary-900 dark:text-secondary-50">Forum Account</h3>
                    <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-300">
                        <strong>Username</strong> {{ $board_username ?: 'not set' }}
                    </p>
                </div>
                <div class="profile-setting-actions">
                    @if(!empty($board_username))
                        <a href="https://board.vatsim-germany.org/account/account-details" class="btn btn-light"><i data-feather="user" class="size-4"></i> View</a>
                    @else
                        <a href="https://board.vatsim-germany.org/oauth" class="btn btn-light"><i data-feather="user-plus" class="size-4"></i> Create</a>
                    @endif
                </div>
            </div>

            <div class="profile-setting-row">
                <div>
                    <h3 class="font-semibold text-primary-900 dark:text-secondary-50">GitHub Account</h3>
                    <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-300">
                        <strong>Username</strong> {{ $user->settings->github_username ?: 'not set' }}
                    </p>
                </div>
                <div class="profile-setting-actions">
                    <a href="{{ route('github.oauth.link') }}" class="btn btn-light"><i data-feather="github" class="size-4"></i> Link</a>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-setting-section border-danger-200 bg-danger-50 dark:border-danger-900 dark:bg-danger-950/40">
        <h2 class="text-xl font-bold text-danger-900 dark:text-danger-200">My Account</h2>
        <div class="mt-5">
            <div class="profile-setting-row border-danger-200 dark:border-danger-900">
                <div>
                    <h3 class="font-semibold text-danger-900 dark:text-danger-200">vatger Account</h3>
                </div>
                <div class="profile-setting-actions">
                    <button type="button" wire:click="call_delete_me" wire:confirm="I want to delete my vatger account!"
                        class="btn bg-danger-700 text-white hover:bg-danger-800">
                        <i data-feather="trash-2" class="size-4"></i> Delete account
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
