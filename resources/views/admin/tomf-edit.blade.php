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

<form method="post" action="{{url('tomf-edit')}}">
{{csrf_field()}}	
<table class="table slides">
<tbody>
<tr>
<th>Title</th>
<td>
<input name="title" class="form-control" value="{{$result->title}}">
</td>
</tr>
<tr>
<th>Sub Heading One</th>
<td>
<input name="sh1" class="form-control" value="{{$result->sh1}}">
</td>
</tr>
<tr>
<th>Field 1</th>
<td>
<textarea name="sh1f1" class="form-control">{{$result->sh1f1}}</textarea>
</td>
</tr>
<tr>
<th>Field 2</th>
<td>
<textarea name="sh1f2" class="form-control">{{$result->sh1f2}}</textarea>
</td>
</tr>

<tr>
<th>Sub Heading Two</th>
<td>
<input name="sh2" class="form-control" value="{{$result->sh2}}">
</td>
</tr>
<tr>
<th>Field 1</th>
<td>
<textarea name="sh2f1" class="form-control">{{$result->sh2f1}}</textarea>
</td>
</tr>
<tr>
<th>Field 2</th>
<td>
<textarea name="sh2f2" class="form-control">{{$result->sh2f2}}</textarea>
</td>
</tr>

<tr>
<th>Field 3</th>
<td>
<textarea name="sh2f3" class="form-control">{{$result->sh2f3}}</textarea>
</td>
</tr>

<tr>
<th>Field 4</th>
<td>
<textarea name="sh2f4" class="form-control">{{$result->sh2f4}}</textarea>
</td>
</tr>

<tr>
<th>Field 5</th>
<td>
<textarea name="sh2f5" class="form-control">{{$result->sh2f5}}</textarea>
</td>
</tr>

<tr>
<th>Field 6</th>
<td>
<textarea name="sh2f6" class="form-control">{{$result->sh2f6}}</textarea>
</td>
</tr>


<tr>
<th>Sub Heading Three</th>
<td>
<input name="sh3" class="form-control" value="{{$result->sh3}}">
</td>
</tr>
<tr>
<th>Field 1</th>
<td>
<textarea name="sh3f1" class="form-control">{{$result->sh3f1}}</textarea>
</td>
</tr>
<tr>
<th>Field 2</th>
<td>
<textarea name="sh3f2" class="form-control">{{$result->sh3f2}}</textarea>
</td>
</tr>
<tr>
<th>Field 3</th>
<td>
<textarea name="sh3f3" class="form-control">{{$result->sh3f3}}</textarea>
</td>
</tr>

<tr>
<th></th>
<td>
<input  style="width:20%; background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
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
