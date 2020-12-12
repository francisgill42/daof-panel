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



@if (session()->has('err'))
<div class="alert alert-danger" role="alert">
{{session('err')}}
</div>
@endif


<div class="tables">
<div class="panel-body widget-shadow">
<h4></h4>
<table class="table">
<thead>
<tr>
<th>Form Id</th>
<th>Created at</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@foreach($data as $data)
<tr>
<th scope="row">{{$data->form_id}}</th>
<td>{{$data->created_at}}</td>
<td>
  @if($data->status == 'unchecked')
  <span class="label label-warning">{{$data->status}}</span>
  @else
  <span class="label label-success">{{$data->status}}</span>
  @endif
</td>
<td>
<a href="{{url('fields/'.$data->form_id.'/edit')}}">View Details</a>

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
