@php
    $gtmId = $containerId ?? null;
    if (! is_string($gtmId) || $gtmId === '' || ! preg_match('/^GTM-[A-Z0-9]+$/i', $gtmId)) {
        $gtmId = null;
    } else {
        $gtmId = strtoupper($gtmId);
    }
@endphp
@if($gtmId)
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
