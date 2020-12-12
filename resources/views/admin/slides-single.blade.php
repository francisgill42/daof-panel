	@extends('layouts.admin-app')

	@section('content')
	<!-- main content start-->
	<div id="page-wrapper">
	<div class="main-page">
	

	<div class="tables">
	<div class="panel-body widget-shadow">
	<h4></h4>
	<form method="post" action="{{url('slide_update')}}">
	{{csrf_field()}}	
	<table class="table slides">
	<tbody>
    @foreach($slides as $slide)
    <input name="id" type="hidden" class="form-control" value="{{$slide->id}}">
	<tr>
	<th>Sponser Title</th>
	<td>
	<input name="title" class="form-control" value="{{$slide->title}}">
	</td>
	</tr>
	<tr>
	<th>Cotent</th>
	<td>
	<textarea name="content" class="mytextarea form-control">{!!$slide->content!!}</textarea>
	</td>
	</tr>
	@endforeach
	</tbody>
	</table>
	<div class="col-md-2 col-md-offset-4 update_btn" style="margin-left:13%;">
	<input style="background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit"
	</div>
	</form>
	</div>

	</div>

	<div class="clearfix"> </div>


	</div>
	</div>

	@endsection
