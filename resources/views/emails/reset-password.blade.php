<x-mail::message>
# Forget Password

to reset your password, click the button below

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
