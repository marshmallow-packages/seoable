@if (!config('seo.hide_mr_mallow'))
    <!-- Marshmallow SEO -->
@endif

@include('seoable::google.gtm-body')
@include('seoable::facebook.fb-body')

@if (!config('seo.hide_mr_mallow'))
    <!-- Marshmallow SEO -->
@endif
