@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Add Slide:</h4>
</div>
<div class="form-body">
@foreach($results as $result)
<form method="post" action="{{url('aums1-edit')}}">
{{csrf_field()}}	


<div class="row">
<div class="col-md-12"><h3>Title</h3></div>
<div class="col-md-12"><input  name="title" class="form-control" value="{{$result->title}}"></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 1</h3></div>
<div class="col-md-12"><textarea name="f1" class="form-control" value="">{{$result->f1}}</textarea></div>
</div>


<div class="row">

<div class="col-md-2">
<br>
<input  style=" background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</div>

</div>
@endforeach
</form>
</div>
</div>
</div>
</div>

<!--chart-->
<div class="row">
<div class="tables">
<div class="panel-body widget-shadow">
<h4>Edit Data For Charts:</h4>
<table class="table">
<thead>
<tr>
<th>Year</th>
<th>Value</th>
<th>Action</th>
</tr>
</thead>
<tbody>

@foreach($charts as $chart)
<tr>
<td>{{$chart->aums_1_key}}</td>
<td>{{$chart->aums_1_value}}</td>
<td>
<a class="" href="{{url('edit-aums1-chart')}}/{{$chart->id}}">Edit</a>
</td>
</tr>
@endforeach


</tbody>
</table>
</div>

</div>
</div>
<div class="clearfix"> </div>


</div>
</div>

@endsection
