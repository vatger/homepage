<div class="js-cookie-consent cookie-consent position-fixed bottom-0 mx-auto w-100 bg-info">
	<div class="flex align-items-center justify-content-between flex-wrap">
		<div class="hidden">
			<p class="mt-3 text-dark text-center cookie-consent__message">
				{!! trans('cookie-consent::texts.message') !!}
			</p>
		</div>
		<div class="mt-2 w-100">
			<button
				class="js-cookie-consent-agree cookie-consent__agree cursor-pointer px-4 py-2 btn-sm btn-secondary btn-block w-100">
				{{ trans('cookie-consent::texts.agree') }}
			</button>
		</div>
	</div>
</div>
