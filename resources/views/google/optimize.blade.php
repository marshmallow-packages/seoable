@section('comment')
<!--
    Anti-flicker snippet (recommended)

    $container is the Google Optimize Container ID
    if GTM is not used. If GTM is used, the $container
    variable will be filled with the GTM Container ID.
-->
@endsection

<style>.async-hide { opacity: 0 !important} </style>
<script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'async-hide','dataLayer',4000,
{'{{ $container }}':true});</script>

@if ($via == 'container')
    <script src="https://www.googleoptimize.com/optimize.js?id={{ $container }}"></script>
@endif
