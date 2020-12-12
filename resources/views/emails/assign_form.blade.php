@component('mail::message')


Admin has been assigned the form to you.

@component('mail::button', ['url' => 'https://localhost/pro/salesapp/public'])
View Form Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
