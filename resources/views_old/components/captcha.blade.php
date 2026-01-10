@props(['fieldName' => '', 'helperText' => ''])
<div
        x-data="{}"
        x-init="hcaptcha.render('h-captcha-{{$fieldName}}', {sitekey: '{{ config('hcaptcha.key') }}', callback: (e) => @this.set('{{$fieldName}}', e)})"
        class="space-y-2">
    <div wire:ignore id="h-captcha-{{$fieldName}}"></div>
</div>
