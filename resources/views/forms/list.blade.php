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
<th>Change Count</th>
<th>Form Id</th>
<th>Created at</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@foreach($data as $data)
<tr>
  <th>1</th>
<th scope="row">{{$data->form_id}}</th>
<td>{{$data->created_at}}</td>
<td>

  @if($data->status == 0)
<span class="label label-warning">Pending</span>
@elseif($data->status == 1)
<span class="label label-info">Sent for changes</span>
@elseif($data->status == 2)
<span class="label label-success">Changes Done</span>
@elseif($data->status == 3)
<span class="label label-success">Sent to cbc</span>
@elseif($data->status == 4)
<span class="label label-success">Cbc Done</span>
@endif
</td>
<td>
<a href="{{url('forms/'.$data->form_id)}}">View Details</a>


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
