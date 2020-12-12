@component('mail::message')
# Dear, {{$data['user_name']}}

{{$data['msg']}}
@component('mail::button', ['url' => 'https://localhost/pro/salesapp/public'])
View Form Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
