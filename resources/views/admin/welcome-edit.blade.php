@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Date:</h4>
</div>
<div class="form-body">
@foreach($results as $result)
<form method="post" action="{{url('welcome-edit')}}">
{{csrf_field()}}	
<table class="table slides">
<tbody>
<tr>
<th>Date</th>
<td>
<input type="hidden" name="id" class="form-control" value="{{$result->id}}">
<input name="date" class="form-control" value="{{$result->date}}">
</td>
</tr>
<tr>



<tr>
<th></th>
<td>
<input  style="width:10%; background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</td>
</tr>

</tbody>

</table>
@endforeach
</form>
</div>
</div>
</div>
</div>




</div>
</div>

@endsection
