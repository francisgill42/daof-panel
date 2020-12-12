@component('mail::message')


You recieved some corrections.

@component('mail::button', ['url' => 'https://localhost/pro/salesapp/public'])
View Form Correction
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
