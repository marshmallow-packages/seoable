@if (config('seo.hotjar.active'))
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:{{ config('seo.hotjar.id') }},hjsv:{{ config('seo.hotjar.version') }}};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async={{ config('seo.hotjar.async') }};
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
@endif