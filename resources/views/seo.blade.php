@if (!config('seo.hide_mr_mallow'))
    <x-mr-mallow-ascii />
    <!-- Marshmallow SEO - HEADER -->
@endif

{{ Seo::googleOptimize() }}
@if (config('seo.fields.title'))
    <title>{{ Seo::getSeoTitle() }}</title>
@endif
@if (config('seo.fields.description'))
    <meta name="description" content="{{ Seo::getSeoDescription() }}" />
@endif
@if (config('seo.fields.keywords') && Seo::getSeoKeywordsAsString())
    <meta name="keywords" content="{{ Seo::getSeoKeywordsAsString() }}" />
@endif
@if (config('seo.fields.follow_type'))
    <meta name="robots" content="{{ Seo::getSeoFollowType() }}" />
@endif
<meta name="author" content="{{ config('seo.defaults.author') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@if (config('seo.fields.title'))
    <meta property="og:title" content="{{ Seo::getSeoTitle() }}" />
@endif
@if (config('seo.fields.description'))
    <meta property="og:description" content="{{ Seo::getSeoDescription() }}" />
@endif
@if (config('seo.fields.image'))
    <meta property="og:image" content="{{ Seo::getSeoImageUrl() }}" />
@endif

@if (config('seo.twitter.site'))
    <meta name="twitter:card" content="{{ config('seo.twitter.card') }}" />
    <meta name="twitter:site" content="{{ config('seo.twitter.site') }}" />
    @if (config('seo.fields.title'))
        <meta property="twitter:title" content="{{ Seo::getSeoTitle() }}" />
    @endif
    @if (config('seo.fields.description'))
        <meta property="twitter:description" content="{{ Seo::getSeoDescription() }}" />
    @endif
    @if (config('seo.fields.image'))
        <meta property="twitter:image" content="{{ Seo::getSeoImageUrl() }}" />
    @endif
@endif

<meta property="og:url" content="{{ Seo::getSeoCanonicalUrl() }}" />
<meta property="og:site_name" content="{{ config('seo.defaults.sitename') }}" />
<meta property="og:locale" content="{{ Seo::getSeoLocale() }}" />

@if ($canonical = Seo::getSeoCanonicalUrl())
    <link rel="canonical" href="{{ $canonical }}" />
@endif

@if ($hrefs = Seo::getHrefLang())
    @foreach ($hrefs as $lang => $route)
        <link rel="alternate" href="{{ $route }}" hreflang="{{ $lang }}" />
    @endforeach
@endif

@if (config('seo.fields.page_type') && ($type = Seo::getSeoPageType()))
    <meta property="og:type" content="{{ $type }}" />
@endif

@if (config('seo.twitter.creator'))
    <meta name="twitter:creator" content="{{ config('seo.twitter.creator') }}" />
@endif

@if (Seo::hasSchema())
    <script type="application/ld+json">
        {!! Seo::getSchema() !!}
    </script>
@endif

@include('seoable::google.gtm-head')
@include('seoable::google.ga-head')
@include('seoable::facebook.fb-head')
@include('seoable::microsoft.head')
@include('seoable::hotjar.hotjar')

@if (!config('seo.hide_mr_mallow'))
    <!-- Marshmallow SEO - HEADER END -->
@endif
