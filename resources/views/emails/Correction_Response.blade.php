@component('mail::message')


Field has been updated.

@component('mail::button', ['url' => 'https://localhost/pro/salesapp/public'])
View Form Fields
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
