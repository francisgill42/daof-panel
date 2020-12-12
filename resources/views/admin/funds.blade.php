	@extends('layouts.admin-app')

	@section('content')
	<!-- main content start-->
	<div id="page-wrapper">
	<div class="main-page">
	<a href="add-fund" class="btn btn-primary">Add Fund</a>
	<br>
	<br>

	<div class="tables">
	<div class="panel-body widget-shadow">
	<h4>Users</h4>
	<table class="table">
	<thead>
	<tr>
	<th>#</th>
	<th>Title</th>
	<th>Action</th>
	</tr>
	</thead>
	<tbody>

	@foreach($funds as $fund)
	<tr>
	<th scope="row">{{$fund->id}}</th>
	<td>{{$fund->title}}</td>
	
	<td>
	<a href="fund_edit/{{$fund->id}}">Edit</a></a>
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
