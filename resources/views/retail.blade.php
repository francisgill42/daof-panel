@extends('layouts.retail-app')

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

@if('retail_admin' == Auth::user()->role->role)
<div class="tables">
<div class="panel-body widget-shadow">
<h4></h4>
<table class="table" id="">
<thead>
<tr>
<!-- <th>Change Count</th> -->
<th>Form Id</th>
<th>Assigned User</th>
<th>Channel</th>
<th>Created at</th>
<th>Status</th>
<th>Action</th>
<th>  <div class="col-sm-6">
  Assign To
</div>
</th>
</tr>

<!-- <tr>
<th>Id</th>
<th>Name</th>
<th>Email</th>
<th>Created At</th>
<th>Updated At</th>
</tr> -->
</thead>
  <tbody>
  @foreach($data as $data)
  <form  action="{{url('assign')}}/{{$data->form_id}}" method="post">
  {{csrf_field()}}

  <tr>
<!--     <th>1</th> -->

  <th scope="row">{{$data->form_id}}</th>
  <th scope="row">
    <?php $user_exist = \DB::table('users')->where('id',$data->assigned_to)->exists();
    if($user_exist){
      echo  \DB::table('users')->where('id',$data->assigned_to)->pluck('name')[0];
    }
    else{
      echo '<strong style="color:red;">Not Assigned</strong>';
    }


    $test = new DateTime($data->created_at);
    $nd = $test->format('M d');
  ?>
  </th>
  <td>{{$data->channel}}</td>
  <td>{{$nd}}</td>
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
  <a href="{{url('forms')}}/{{$data->form_id}}">View Details</a>
  </td>
  <td width="">
    <div class="col-sm-6">
    <select class="form-control " name="user_id">
    <option value="">Select</option>
    <?php $retail_users = \DB::table('users')->where('role_id',4)->get(); ?>
    @foreach($retail_users as $ra)
    <option value="{{$ra->id}}">{{$ra->name}}</option>
    @endforeach
    </select>

    </div>
  <button type="submit" class="btn btn-sm">Submit</button>
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



@if('retail_user' == Auth::user()->role->role)
<div class="tables">
<div class="panel-body widget-shadow">
<h4></h4>
<table class="table">
<thead>
<tr>
<th>Change Count</th>
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
  <th>1</th>

<th scope="row">{{$data->form_id}}</th>
<td>{{$data->channel}}</td>
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
<a href="{{url('forms')}}/{{$data->form_id}}">View Details</a>
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
