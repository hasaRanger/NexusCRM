@component('mail::message')

# Welcome, {{ $user->name }}!

Thanks for creating an account at {{ config('app.name') }}. Your account has been successfully created — you can now sign in and start using the CRM.

@component('mail::button', ['url' => config('app.url')])
Go to {{ config('app.name') }}
@endcomponent

If you didn't create this account, please contact our support team.

Thanks,
{{ config('app.name') }}

@endcomponent
