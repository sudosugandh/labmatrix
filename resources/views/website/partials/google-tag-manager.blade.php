{{-- Google Tag Manager — $containerId must be validated (GTM-XXXXX). --}}
@php
    $gtmId = $containerId ?? null;
    if (! is_string($gtmId) || $gtmId === '' || ! preg_match('/^GTM-[A-Z0-9]+$/i', $gtmId)) {
        $gtmId = null;
    } else {
        $gtmId = strtoupper($gtmId);
    }
@endphp
@if($gtmId)
<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtmId }}');
</script>
<!-- End Google Tag Manager -->
@endif
