	@extends('layouts.admin-app')

	@section('content')
	<!-- main content start-->
	<div id="page-wrapper">
	<div class="main-page">
	
	<br>
	<br>

	<div class="tables">
	<div class="panel-body widget-shadow">
	<h4>Risk Profiles</h4>
	<table class="table">
	<thead>
	<tr>
	<th>Client Name</th>
	<th>Client Email</th>
	<th>Client Number</th>
	<th>Sales Man</th>
	<th>Action</th>
	</tr>
	</thead>
	<tbody>

	@foreach($users as $user)
	<tr>
	<td>{{$user->client_name}}</td>
	<td>{{$user->client_email}}</td>
	<td>{{$user->client_number}}</td>
	<td>{{$user->name}}</td>
	<td>
	<a href="{{url('risk_profile')}}/{{$user->id}}">View Details</a>
	
	</td>
	</tr>
	@endforeach


	</tbody>
	</table>
	</div>

	</div>

	<div class="clearfix"> </div>


	</div>
	</div>

	@endsection
