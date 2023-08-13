<x-mail::message>
# Verification Email

Click the button below to verify your email address and complete your registration.
<x-mail::button :url="$url">
Verify Email
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
