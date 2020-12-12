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
<?php foreach($results as $result){?>
<form method="post" action="{{url('sponsers-edit')}}">
{{csrf_field()}}	
<table class="table slides">
<tbody>
<tr>
<th>Title</th>
<td>
<input name="title" class="form-control" value="<?php echo $result->title; ?>">
</td>
</tr>
<tr>

<th>Field 1</th>
<td>
<input name="f1" class="form-control" value="<?php echo $result->f1; ?>">
</td>
</tr>

<tr>
<th>Field 2</th>
<td>
<input name="f2" class="form-control" value="<?php echo $result->f2; ?>">
</td>
</tr>

<tr>
<th>Field 3</th>
<td>
<input name="f3" class="form-control" value="<?php echo $result->f3; ?>">
</td>
</tr>

<tr>
<th>Field 4</th>
<td>
<?php $f4 = str_replace('<br />', '', $result->f4);?>
<textarea style="height:100px;" name="f4" class="form-control" value=""><?php echo $f4; ?></textarea>
</td>
</tr>

<tr>
<th>Field 5</th>
<td>
<input name="f5" class="form-control" value="<?php echo $result->f5; ?>">
</td>
</tr>

<tr>
<th>Field 6</th>
<td>
<input name="f6" class="form-control" value="<?php echo $result->f6; ?>">
</td>
</tr>


<tr>
<th></th>
<td>
<input  style="width:10%; background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</td>
</tr>

</tbody>

</table>
<?php } ?>
</form>
</div>
</div>
</div>
</div>

<!-- chart list -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Charts:</h4>
</div>

<div class="form-body">
<table class="table">
<tbody>

<tr>
<td style="width:90%;">Chart 1</td>
<td>
<a class="btn btn-link" href="{{url('edit-sc-chart')}}/1">Edit Data</a>
</td>
</tr>


<tr>
<td style="width:90%;">Chart 2</td>
<td>
<a class="btn btn-link" href="{{url('edit-sc-chart')}}/2">Edit Data</a>
</td>
</tr>


</tbody>
</table>

</div>
</div>
</div>
</div>


</div>
</div>
@endsection