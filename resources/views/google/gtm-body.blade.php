@if ($enabled)
    <!-- GTM Body -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $id }}{!! $urlSuffix !!}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- GTM Body END -->
@endif
