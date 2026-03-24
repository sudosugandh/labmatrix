@component('mail::message')
# Your report is ready

Hello **{{ $patientName }}**,

Your diagnostic report **{{ $reportTitle }}** from **{{ $labName }}** is available to download securely.

@component('mail::panel')
Open the button below on a **private device** you trust. This link points to your official PDF on our patient portal.
@endcomponent

@component('mail::button', ['url' => $downloadUrl, 'color' => 'primary'])
Download your report (PDF)
@endcomponent

@component('mail::subcopy')
**Link not working?** Copy and paste this URL into your browser:

{{ $downloadUrl }}
@endcomponent

Thanks for choosing **{{ $labName }}**.

@if(!empty($brandTagline))
<span style="color:#6b7280;font-size:14px;">{{ $brandTagline }}</span>

@endif
---

<small style="color:#6b7280;">
This link is intended only for you. If you did not request this email, you can ignore it—your information stays protected.
</small>

@if(!empty($labPhone) || !empty($labEmail) || !empty($labAddress))
@component('mail::panel')
**Contact {{ $labName }}**  
@if(!empty($labPhone))Phone: {{ $labPhone }}@endif
@if(!empty($labPhone) && (!empty($labEmail) || !empty($labAddress))) · @endif
@if(!empty($labEmail)){{ $labEmail }}@endif
@if(!empty($labAddress))

{{ $labAddress }}
@endif
@endcomponent
@endif
@endcomponent
