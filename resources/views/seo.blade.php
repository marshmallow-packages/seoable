<!-- Marshmallow SEO -->
<title>{{ Seo::getSeoTitle() }}</title>
<meta name="description" content="{{ Seo::getSeoDescription() }}" />
<meta name="keywords" content="{{ Seo::getSeoKeywordsAsString() }}" />
<meta property="og:title" content="{{ Seo::getSeoTitle() }}" />
<meta property="og:description" content="{{ Seo::getSeoDescription() }}" />
<meta property="og:image" content="{{ Seo::getSeoImageUrl() }}" />
<meta name="robots" content="{{ Seo::getSeoFollowType() }}" />

@if (Seo::hasSchema())
<script type="application/ld+json">
	{!! Seo::getSchema() !!}
</script>
@endif

@if (config('seo.google.GTM'))
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','{{ config('seo.google.GTM') }}');</script>
  <!-- End Google Tag Manager -->
@endif

@if (config('seo.google.GA'))
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('seo.google.GA') }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('seo.google.GA') }}');
  </script>
@endif
<!-- Marshmallow SEO -->
