<?php //dd(Auth::user()->id);?>
@extends('layouts.cbc-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">
@if (session()->has('msg'))
<div class="alert alert-success" role="alert">
<strong>Well done!</strong> {{session('msg')}}
</div>
@endif

@if (session()->has('err'))
<div class="alert alert-danger" role="alert">
{{session('err')}}
</div>
@endif


@if('cbc' == Auth::user()->role->role)
<div class="tables">
<div class="panel-body widget-shadow">
<h4></h4>
<table class="table">
<thead>
<tr>
<th>Form Id</th>
<th>Channel</th>
<th>Created at</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@foreach($data as $data)
<form  action="{{url('assign')}}/{{$data->form_id}}" method="post">
{{csrf_field()}}

<tr>
<th scope="row">{{$data->form_id}}</th>
<td>{{$data->channel}}</td>
<td>{{$data->created_at}}</td>
<td>
@if($data->status == 4)
<span class="label label-success">Cbc Done</span>
@else
<span class="label label-warning">cbc pending</span>
@endif
</td>
<td>

<a href="{{url('cbc/'.$data->form_id.'/edit')}}">View Details</a>
</td>
</tr>
</form>
@endforeach

</tbody>
</table>
</div>

</div>


<div class="clearfix"> </div>

@endif
</div>
</div>


@endsection
