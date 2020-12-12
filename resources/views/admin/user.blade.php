	@extends('layouts.admin-app')

	@section('content')
	<!-- main content start-->
	<div id="page-wrapper">
	<div class="main-page">
	<a href="add-user" class="btn btn-primary">Add User</a>
	<br>
	<br>

	<div class="tables">
	<div class="panel-body widget-shadow">
	<h4>Users</h4>
	<table class="table">
	<thead>
	<tr>
	<th>First Name</th>
	<th>Email</th>
	<th>Role</th>
	<th>Action</th>
	</tr>
	</thead>
	<tbody>

	@foreach($users as $user)
	<tr>
	<td>{{$user->name}}</td>
	<td>{{$user->email}}</td>
	<td>{{$user->role_title}}</td>
	<td>
	<a href="user_edit/{{$user->id}}">Edit</a>|<a href="user_delete/{{$user->id}}">Delete</a>
	</td>
	</tr>

	@endforeach




	</tbody>
	</table>
	{{$users->links()}}
	</div>

	</div>

	<div class="clearfix"> </div>


	</div>
	</div>

	@endsection
