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

	<tbody>
	@foreach($profiles as $user)


	<tr>
	<th>Client ID</th>
	<td>{{$user->id}}</td>
	</tr>
	<tr>
	<th>Client Name</th>
	<td>{{$user->client_name}}</td>
	</tr>
	<tr>
	<th>Client Email</th>
	<td>{{$user->client_email}}</td>
	</tr>
	<tr>
	<th>Client Number</th>
	<td>{{$user->client_number}}</td>
	</tr>
	<tr>
	<th>Location</th>
	<td>
	{{$user->location}}
	@empty($user->location)
	{{'Not Available'}}
	@endempty
</td>
	</tr>
	<tr>
	<th>Choosen Fund</th>
	<td>
		{{$user->choosen_fund}}
		@empty($user->choosen_fund)
		{{'Not Available'}}
		@endempty
	</td>
	</tr>

	<tr>
	<th>Recommended Islamic Fund</th>
	<td>
		{{$user->irf}}
		@empty($user->irf)
		{{'Not Available'}}
		@endempty
	</td>
	</tr>

	<tr>
	<th>Recommended Conventional Fund</th>
	<td>
		{{$user->crf}}
		@empty($user->crf)
		{{'Not Available'}}
		@endempty
	</td>
	</tr>


	<tr>
	<th>Seller</th>
	<td>
		{{DB::table('users')->where('id',$user->user_id )->pluck('name')[0]}}
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
