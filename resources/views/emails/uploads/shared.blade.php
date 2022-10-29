<x-mail::message>
# {{ $upload->name }}

{{ $upload->sender }} has shared a file with you.
Click on the link below to download the file.

<x-mail::button :url="$upload->url">
Download file
</x-mail::button>

@if ($password)
<x-mail::panel>
    The password to download the file is <code>{{ $password }}</code>.
</x-mail::panel>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
