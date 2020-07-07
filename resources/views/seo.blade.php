	<x-mr-mallow-ascii/>

	<!-- Marshmallow SEO -->
	<title>{{ Seo::getSeoTitle() }}</title>
	<meta name="description" content="{{ Seo::getSeoDescription() }}" />
	<meta name="keywords" content="{{ Seo::getSeoKeywordsAsString() }}" />
	<meta property="og:title" content="{{ Seo::getSeoTitle() }}" />
	<meta property="og:description" content="{{ Seo::getSeoDescription() }}" />
	<meta property="og:image" content="{{ Seo::getSeoImageUrl() }}" />
	<meta name="robots" content="{{ Seo::getSeoFollowType() }}" />
	<meta name="author" content="Marshmallow.dev">

	@if (Seo::hasSchema())
	<script type="application/ld+json">
		{!! Seo::getSchema() !!}
	</script>
	@endif

	@include('seoable::google.gtm-head')
	@include('seoable::google.ga-head')

	<!-- Marshmallow SEO -->
