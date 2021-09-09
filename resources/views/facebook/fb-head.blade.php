@if (config('seo.facebook.admins'))
<meta property="fb:admins" content="{{ config('seo.facebook.admins') }}" />
@endif
@if (config('seo.facebook.app_id'))
<meta property="fb:app_id" content="{{ config('seo.facebook.app_id') }}" />
@endif

@if (config('seo.facebook.pixel_id'))
<script>
    !function(f,b,e,v,n,t,s) {if (f.fbq) return;n = f.fbq = function() {n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)};if (!f._fbq) f._fbq = n;n.push = n;n.loaded = !0;n.version = '2.0';n.queue = [];t = b.createElement(e);t.async = !0;t.src = v;s = b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '{{ config('seo.facebook.pixel_id') }}');fbq('track','PageView');
</script>
@endif

@if (config('seo.facebook.app_id'))
<script>
    (function(d,s,id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "//connect.facebook.net/nl_NL/all.js#xfbml=1&appId={{ config('seo.facebook.app_id') }}";fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'));
</script>
@endif
