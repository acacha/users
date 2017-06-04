@component('mail::message')
# You have been invited to {{ config('app.name') }} app

Somebody has invited you to use {{ config('app.name') }} app.

Please click on the following link to create a user:

@component('mail::button', ['url' => env('APP_URL') . '/management/users/user-invitation-accept?token=' . $invitation->token])
Register
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent