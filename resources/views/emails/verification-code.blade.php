@component('mail::message')
# University Management System

## Hello {{ $name }}!

Thank you for registering with us. Please use the verification code below to verify your email address.

Your verification code is:

@component('mail::panel')
<div style="font-size: 24px; font-weight: bold; text-align: center; letter-spacing: 5px;">
{{ $code }}
</div>
@endcomponent

This code will expire in 30 minutes.

If you did not create an account, no further action is required.

Regards,<br>
University Management System

@endcomponent
