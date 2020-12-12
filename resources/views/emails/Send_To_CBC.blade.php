@component('mail::message')


You Recieved New Form Request For The Call Back Confirmation.

@component('mail::button', ['url' => 'https://localhost/pro/salesapp/public'])
View Form Fields
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
