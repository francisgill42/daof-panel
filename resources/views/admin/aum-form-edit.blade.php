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
    @foreach($aums as $aum)
<form method="post" action="{{url('aum-form-edit')}}">
{{csrf_field()}}	
<table class="table slides">
<tbody>
<tr>
<th>Title</th>
<td>
<input name="id" type="hidden" class="form-control" value="{{$aum->id}}">
<input name="title" class="form-control" value="{{$aum->title}}">
</td>
</tr>
<tr>
<th>Box 1</th>
<td>
<input name="b1" class="form-control" value="{{$aum->b1}}">
</td>
</tr>

<tr>
<th>Box 2</th>
<td>
<input name="b2" class="form-control" value="{{$aum->b2}}">
</td>
</tr>

<tr>
<th>Box 3</th>
<td>
<input name="b3" class="form-control" value="{{$aum->b3}}">
</td>
</tr>

<tr>
<th>Box 4</th>
<td>
<input name="b4" class="form-control" value="{{$aum->b4}}">
</td>
</tr>

<tr>
<th>Box 5</th>
<td>
<input name="b5" class="form-control" value="{{$aum->b5}}">
</td>
</tr>

<tr>
<th>Box 6</th>
<td>
<input name="b6" class="form-control" value="{{$aum->b6}}">
</td>
</tr>



<th>List 1</th>
<td>
<input name="l1" class="form-control" value="{{$aum->l1}}">
</td>
</tr>

<tr>
<th>List 2</th>
<td>
<input name="l2" class="form-control" value="{{$aum->l2}}">
</td>
</tr>

<tr>
<th>List 3</th>
<td>
<input name="l3" class="form-control" value="{{$aum->l3}}">
</td>
</tr>

<tr>
<th>List 4</th>
<td>
<input name="l4" class="form-control" value="{{$aum->l4}}">
</td>
</tr>


@endforeach
<tr>
<th></th>
<td>
<input  style="width:10%; background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
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
