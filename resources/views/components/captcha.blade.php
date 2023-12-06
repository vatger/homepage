@props(['fieldName' => '', 'helperText' => ''])
<div
        x-data="{}"
        x-init="hcaptcha.render('h-captcha-{{$fieldName}}', {sitekey: '{{env('HCAPTCHA_SITEKEY')}}', callback: (e) => @this.set('{{$fieldName}}', e)})"
        class="space-y-2">
    <div wire:ignore id="h-captcha-{{$fieldName}}"></div>
</div>
