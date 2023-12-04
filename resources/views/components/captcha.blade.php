@props(['fieldName' => '', 'helperText' => ''])
<div
        x-data="{}"
        x-init="hcaptcha.render('h-captcha-{{$fieldName}}', {sitekey: '10000000-ffff-ffff-ffff-000000000001', callback: (e) => @this.set('{{$fieldName}}', e)})"
        class="space-y-2">
        <span class="text-sm font-medium leading-4 text-gray-700">
            CAPTCHA (anti-bot)
            <sup class="font-medium text-danger-700">*</sup>
        </span>
    <div wire:ignore id="h-captcha-{{$fieldName}}"></div>

    <!-- TODO: add your own input error handler -->
    <x-input-error :for="$fieldName"/>
    <p class="text-sm text-gray-600">{{$helperText}}</p>
</div>
