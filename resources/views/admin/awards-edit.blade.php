@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Slide:</h4>
</div>
<div class="form-body">
@foreach($results as $result)
<form method="post" action="{{url('awards-edit')}}">
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
<th>Field 1</th>
<td>
<input name="f1_bold" class="form-control" value="{{$result->f1_bold}}">
</td>
<td>
<input name="f1_normal" class="form-control" value="{{$result->f1_normal}}">
</td>
</tr>

<tr>
<th>Field 2</th>
<td>
<input name="f2_bold" class="form-control" value="{{$result->f2_bold}}">
</td>
<td>
<input name="f2_normal" class="form-control" value="{{$result->f2_normal}}">
</td>
</tr>

<tr>
<th>Field 3</th>
<td>
<input name="f3_bold" class="form-control" value="{{$result->f3_bold}}">
</td>
<td>
<input name="f3_normal" class="form-control" value="{{$result->f3_normal}}">
</td>
</tr>

<tr>
<th>Field 4</th>
<td>
<input name="f4_bold" class="form-control" value="{{$result->f4_bold}}">
</td>
<td>
<input name="f4_normal" class="form-control" value="{{$result->f4_normal}}">
</td>
</tr>

<tr>
<th>Field 5</th>
<td>
<input name="f5_bold" class="form-control" value="{{$result->f5_bold}}">
</td>
<td>
<input name="f5_normal" class="form-control" value="{{$result->f5_normal}}">
</td>
</tr>

<tr>
<th>Field 6</th>
<td>
<input name="f6_bold" class="form-control" value="{{$result->f6_bold}}">
</td>
<td>
<input name="f6_normal" class="form-control" value="{{$result->f6_normal}}">
</td>
</tr>

<tr>
<th>Field 7</th>
<td>
<input name="f7_bold" class="form-control" value="{{$result->f7_bold}}">
</td>
<td>
<input name="f7_normal" class="form-control" value="{{$result->f7_normal}}">
</td>
</tr>

<tr>
<th>Field 8</th>
<td>
<input name="f8_bold" class="form-control" value="{{$result->f8_bold}}">
</td>
<td>
<input name="f8_normal" class="form-control" value="{{$result->f8_normal}}">
</td>
</tr>

<tr>
<th>Field 9</th>
<td>
<input name="f9_bold" class="form-control" value="{{$result->f9_bold}}">
</td>
<td>
<input name="f9_normal" class="form-control" value="{{$result->f9_normal}}">
</td>
</tr>

<tr>
<th>Field 10</th>
<td>
<input name="f10_bold" class="form-control" value="{{$result->f10_bold}}">
</td>
<td>
<input name="f10_normal" class="form-control" value="{{$result->f10_normal}}">
</td>
</tr>

<tr>
<th></th>
<td>
<input  style="width:20%; background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</td>
</tr>

</tbody>
@endforeach

</table>

</form>
</div>
</div>
</div>
</div>




</div>
</div>

@endsection
