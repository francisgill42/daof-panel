@component('mail::message')


Change done by CBC user.

@component('mail::button', ['url' => 'https://localhost/pro/salesapp/public'])
View Form Changes
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
