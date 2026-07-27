<x-mail::message>
# New {{ ucfirst($lead->type) }} Inquiry

**Name:** {{ $lead->name }}

**Email:** {{ $lead->email }}

@if ($lead->phone)
**Phone:** {{ $lead->phone }}
@endif

@if ($lead->subject)
**Subject:** {{ $lead->subject }}
@endif

@if ($lead->message)
**Message:**

{{ $lead->message }}
@endif

<x-mail::button :url="route('admin.dashboard')">
View in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
