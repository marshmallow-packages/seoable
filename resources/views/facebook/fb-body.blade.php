@if (config('seo.facebook.pixel_id'))
<noscript><img height="1" width="1" src="https://www.facebook.com/tr?id={{ config('seo.facebook.pixel_id') }}&ev=PageView&noscript=1" /></noscript>
@endif

@if (config('seo.facebook.app_id'))
<div id="fb-root"></div>
@endif
