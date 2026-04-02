@component('mail::message')
# New message from your website

**Lab:** {{ $labName }}

**Name:** {{ $inquiry->name }}

**Phone:** {{ $inquiry->phone }}

**Message:**

{{ $inquiry->message }}

@component('mail::button', ['url' => config('app.url')])
Open admin
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
