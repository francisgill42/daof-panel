	@extends('layouts.admin-app')

	@section('content')
	<!-- main content start-->
	<div id="page-wrapper">
	<div class="main-page">
	<a href="add-slide" class="btn btn-primary">Add Slide</a>
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

	@foreach($slides as $slide)
	<tr>
	<th scope="row">{{$slide->id}}</th>
	<td>{{$slide->title}}</td>
	<td>
	<a href="slide_edit/{{$slide->id}}">Edit</a> | <a href="slide_delete/{{$slide->id}}">Delete</a>
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
