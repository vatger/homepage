<div class="support-page font-sans">
    @component('components.layouts.content', [
        'header' => 'Support',
        'links' => [
            route('landing') => config('app.name'),
            __('navigation.hilfe.titel'),
            __('navigation.hilfe.support'),
        ],
    ])
    @endcomponent

    <script src="https://js.hcaptcha.com/1/api.js?hl={{ app()->getLocale() }}"></script>

    <main
        class="bg-secondary-50 px-4 py-16 text-primary-900 sm:px-6 lg:px-8 lg:py-20 dark:bg-secondary-900 dark:text-secondary-50">
        <div class="mx-auto max-w-5xl">
            <form wire:submit="send"
                class="rounded-2xl border border-secondary-200 bg-white p-6 shadow-[0_18px_50px_rgba(43,63,85,0.08)] sm:p-8 lg:p-10 dark:border-secondary-800 dark:bg-secondary-800">
                <header class="mb-8 max-w-2xl">
                    <p
                        class="mb-2 text-sm font-semibold uppercase tracking-[0.14em] text-secondary-500 dark:text-secondary-400">
                        @lang('navigation.hilfe.titel')
                    </p>
                    <h2
                        class="m-0 text-2xl font-semibold tracking-tight text-primary-900 sm:text-3xl dark:text-secondary-50">
                        @lang('navigation.hilfe.support')
                    </h2>
                </header>

                @if ($selected_type && ($selected_type->system == 'V' || property_exists($selected_type, 'public_url')))
                    <div class="mb-6 grid gap-3">
                        <div class="flex items-start gap-3 rounded-xl border border-danger-200 bg-danger-50 p-4 text-sm text-danger-700"
                            role="alert">
                            <i data-feather="alert-triangle" class="mt-0.5 size-5 shrink-0"
                                aria-hidden="true"></i>
                            <span>@lang('support.text-no-credentials')</span>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm text-warning-900"
                            role="alert">
                            <i data-feather="alert-triangle" class="mt-0.5 size-5 shrink-0"
                                aria-hidden="true"></i>
                            <span>@lang('support.text-no-reply')</span>
                        </div>

                        @if ($selected_type->public_url)
                            <div class="flex items-start gap-3 rounded-xl border border-secondary-200 bg-secondary-50 p-4 text-sm text-primary-900 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50"
                                role="alert">
                                <i data-feather="info" class="mt-0.5 size-5 shrink-0 text-accent-500"
                                    aria-hidden="true"></i>
                                <span>
                                    @lang('support.text-check-board')
                                    <a target="_blank" rel="noopener noreferrer"
                                        class="font-semibold text-accent-500 underline-offset-4 hover:underline"
                                        href="{{ $selected_type->public_url }}">
                                        @lang('support.text-here')
                                    </a>.
                                </span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="grid gap-6">
                    <div>
                        <label for="support-area"
                            class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                            @lang('support.text-choose-area') <span class="text-danger-700">*</span>
                        </label>
                        <select wire:model.live="chosen_area" id="support-area" aria-required="true"
                            class="block min-h-11 w-full rounded-lg border border-secondary-200 bg-secondary-50 px-3.5 py-2.5 text-sm text-primary-900 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 disabled:cursor-not-allowed disabled:opacity-60 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25">
                            <option value="0" @selected($chosen_area == 0)></option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($chosen_area != 0)
                        <div>
                            <label for="support-category"
                                class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                                @lang('support.text-choose-cat') <span class="text-danger-700">*</span>
                            </label>
                            <select wire:model.live="chosen_sup_type" id="support-category" aria-required="true"
                                class="block min-h-11 w-full rounded-lg border border-secondary-200 bg-secondary-50 px-3.5 py-2.5 text-sm text-primary-900 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 disabled:cursor-not-allowed disabled:opacity-60 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25">
                                <option value="0" @selected($chosen_sup_type == 0)></option>
                                @if ($selected_area != null)
                                    @foreach ($selected_area->types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="name"
                                class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                                @lang('support.text-name') <span class="text-danger-700">*</span>
                            </label>
                            <div class="relative">
                                <i data-feather="user"
                                    class="pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-secondary-500"
                                    aria-hidden="true"></i>
                                <input wire:model="name" name="name" id="name" type="text"
                                    aria-required="true"
                                    class="block min-h-11 w-full rounded-lg border border-secondary-200 bg-secondary-50 py-2.5 pr-3.5 pl-11 text-sm text-primary-900 placeholder:text-secondary-400 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 disabled:cursor-not-allowed disabled:bg-secondary-100 disabled:text-secondary-500 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25 dark:disabled:opacity-60"
                                    @disabled($user) placeholder="Max Mustermann">
                            </div>
                        </div>

                        <div>
                            <label for="email"
                                class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                                @lang('support.text-mail') <span class="text-danger-700">*</span>
                            </label>
                            <div class="relative">
                                <i data-feather="mail"
                                    class="pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-secondary-500"
                                    aria-hidden="true"></i>
                                <input wire:model="mail" name="email" id="email" type="email"
                                    aria-required="true"
                                    class="block min-h-11 w-full rounded-lg border border-secondary-200 bg-secondary-50 py-2.5 pr-3.5 pl-11 text-sm text-primary-900 placeholder:text-secondary-400 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25"
                                    placeholder="mail@me.de">
                            </div>
                        </div>

                        <div>
                            <label for="id"
                                class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                                @lang('support.text-cid')
                            </label>
                            <input wire:model="cid" name="id" id="id" type="text"
                                class="block min-h-11 w-full rounded-lg border border-secondary-200 bg-secondary-50 px-3.5 py-2.5 text-sm text-primary-900 placeholder:text-secondary-400 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 disabled:cursor-not-allowed disabled:bg-secondary-100 disabled:text-secondary-500 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25 dark:disabled:opacity-60"
                                @disabled($user) placeholder="1000001">
                        </div>

                        <div class="md:col-span-2">
                            <label for="subject"
                                class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                                @lang('support.text-subject')
                            </label>
                            <div class="relative">
                                <i data-feather="book"
                                    class="pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-secondary-500"
                                    aria-hidden="true"></i>
                                <input wire:model="subject" name="subject" id="subject" type="text"
                                    class="block min-h-11 w-full rounded-lg border border-secondary-200 bg-secondary-50 py-2.5 pr-3.5 pl-11 text-sm text-primary-900 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="comments"
                                class="mb-2 block text-sm font-semibold text-primary-900 dark:text-secondary-50">
                                @lang('support.text-content')
                            </label>
                            <div class="relative">
                                <i data-feather="message-circle"
                                    class="pointer-events-none absolute top-3.5 left-3.5 size-5 text-secondary-500"
                                    aria-hidden="true"></i>
                                <textarea wire:model="content" name="comments" id="comments" rows="6"
                                    class="block w-full resize-y rounded-lg border border-secondary-200 bg-secondary-50 py-3 pr-3.5 pl-11 text-sm text-primary-900 transition focus:border-primary-900 focus:ring-2 focus:ring-primary-900/20 dark:border-secondary-800 dark:bg-secondary-900 dark:text-secondary-50 dark:focus:border-primary-400 dark:focus:ring-primary-400/25"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg">
                        <x-captcha fieldName="token" />
                    </div>

                    <div
                        class="flex justify-end border-secondary-200 border-t pt-6 dark:border-secondary-800">
                        <button type="submit" wire:loading.attr="disabled" wire:target="send"
                            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border-0 bg-primary-900 px-5 py-2.5 text-sm font-semibold text-secondary-50 shadow-sm transition hover:bg-primary-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent-500 disabled:cursor-not-allowed disabled:opacity-60">
                            <i data-feather="send" class="size-4" aria-hidden="true"></i>
                            <span>@lang('support.text-send')</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
