@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">
@if (session()->has('msg'))
<div class="alert alert-success" role="alert">
<strong>Well done!</strong> {{session('msg')}}
</div>
@endif
<?php 
$count = \DB::table('risks')->where('user_id',\Auth::user()->id)->where('pushed',0)->count();
if($count > 0){
?>
<a href="{{url('generate_leads')}}" class="btn btn-primary pull-right">Push to CRM</a>
<br><br><br>
<?php }?>

<div class="tables">
<div class="panel-body widget-shadow">
<h4>Risk Profiles <?php //echo \Auth::user()->id;?></h4>
<table class="table">
<thead>
<tr>
<th>#</th>
<th>Client Name</th>
<th>Client Email</th>
<th>Client Number</th>

<th>Action</th>
</tr>
</thead>
<tbody>

@foreach($my_risk_profiles as $my_risk_profile)
@if($my_risk_profile->pushed == 0)
<tr>
<th scope="row">{{$my_risk_profile->id}}</th>
<td>{{$my_risk_profile->client_name}}</td>
<td>{{$my_risk_profile->client_email}}</td>
<td>{{$my_risk_profile->client_number}}</td>
<td>
<a href="{{url('my-risk-profile-single')}}/{{$my_risk_profile->id}}">View Details</a>

</td>
</tr>
@endif
@endforeach


</tbody>
</table>
</div>

</div>

<div class="clearfix"> </div>


</div>
</div>


@endsection