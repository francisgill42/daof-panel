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
<form method="post" action="{{url('awards')}}">
{{csrf_field()}}	
<table class="table slides">
<tbody>
<tr>
<th>Title</th>
<td>
<input name="title" class="form-control" value="">
</td>
</tr>
<tr>
<th>Field 1</th>
<td>
<input name="f1_bold" class="form-control" value="">
</td>
<td>
<input name="f1_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 2</th>
<td>
<input name="f2_bold" class="form-control" value="">
</td>
<td>
<input name="f2_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 3</th>
<td>
<input name="f3_bold" class="form-control" value="">
</td>
<td>
<input name="f3_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 4</th>
<td>
<input name="f4_bold" class="form-control" value="">
</td>
<td>
<input name="f4_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 5</th>
<td>
<input name="f5_bold" class="form-control" value="">
</td>
<td>
<input name="f5_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 6</th>
<td>
<input name="f6_bold" class="form-control" value="">
</td>
<td>
<input name="f6_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 7</th>
<td>
<input name="f7_bold" class="form-control" value="">
</td>
<td>
<input name="f7_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 8</th>
<td>
<input name="f8_bold" class="form-control" value="">
</td>
<td>
<input name="f8_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 9</th>
<td>
<input name="f9_bold" class="form-control" value="">
</td>
<td>
<input name="f9_normal" class="form-control" value="">
</td>
</tr>

<tr>
<th>Field 10</th>
<td>
<input name="f10_bold" class="form-control" value="">
</td>
<td>
<input name="f10_normal" class="form-control" value="">
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

</form>
</div>
</div>
</div>
</div>




</div>
</div>

@endsection
