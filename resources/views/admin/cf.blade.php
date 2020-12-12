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
<?php foreach($all as $all){ ?>


<form method="post" action="{{url('admin-cf-update')}}">
{{csrf_field()}}	

<b>Title:</b><input type="hidden" name="id" class="form-control" value="<?php echo $all->id; ?>"><br>
<input name="title" class="form-control" value="<?php echo $all->title; ?>">
<br><b>Feature and Benefits:</b>
<textarea id="fab" style="min-height: 150px;" placeholder=" " name="fab" class="form-control">
<?php echo str_replace('<br />',' ',$all->fab); ?>
</textarea>
<br>

<table class="table slides">
<tbody>
    
<tr>
<th><b>Fund Info:</b>
</th>
<td>
</td>
</tr>
<tr>
<td>
<input name="fi_k_1" class="form-control" value="<?php echo $all->fi_k_1; ?>">
</td>
<td>
<input name="fi_v_1" class="form-control" value="<?php echo $all->fi_v_1; ?>">
</td>
</tr>

<tr>
<td>
<input name="fi_k_2" class="form-control" value="<?php echo $all->fi_k_2; ?>">
</td>
<td>
<input name="fi_v_2" class="form-control" value="<?php echo $all->fi_v_2; ?>">
</td>
</tr>

<tr>
<td>
<input name="fi_k_3" class="form-control" value="<?php echo $all->fi_k_3; ?>">
</td>
<td>
<input name="fi_v_3" class="form-control" value="<?php echo $all->fi_v_3; ?>">
</td>
</tr>

<tr>
<td>
<input name="fi_k_4" class="form-control" value="<?php echo $all->fi_k_4; ?>">
</td>
<td>
<input name="fi_v_4" class="form-control" value="<?php echo $all->fi_v_4; ?>">
</td>
</tr>

<tr>
<td>
<input name="fi_k_5" class="form-control" value="<?php echo $all->fi_k_5; ?>">
</td>
<td>
<input name="fi_v_5" class="form-control" value="<?php echo $all->fi_v_5; ?>">
</td>
</tr>




</tbody>

</table>

<table class="table slides">
<tbody>
    
<tr>
<th><b>Asset Allocation:</b>
</th>
<td>
</td>
</tr>

<tr>
<td>
<input name="aa_k_1" class="form-control" value="<?php echo $all->aa_k_1; ?>">
</td>
<td>
<input name="aa_v_1" class="form-control" value="<?php echo $all->aa_v_1; ?>">
</td>
</tr>

<tr>
<td>
<input name="aa_k_2" class="form-control" value="<?php echo $all->aa_k_2; ?>">
</td>
<td>
<input name="aa_v_2" class="form-control" value="<?php echo $all->aa_v_2; ?>">
</td>
</tr>

<tr>
<td>
<input name="aa_k_3" class="form-control" value="<?php echo $all->aa_k_3; ?>">
</td>
<td>
<input name="aa_v_3" class="form-control" value="<?php echo $all->aa_v_3; ?>">
</td>
</tr>

<tr>
<td>
<input name="aa_k_4" class="form-control" value="<?php echo $all->aa_k_4; ?>">
</td>
<td>
<input name="aa_v_4" class="form-control" value="<?php echo $all->aa_v_4; ?>">
</td>
</tr>

<tr>
<td>
<input name="aa_k_5" class="form-control" value="<?php echo $all->aa_k_5; ?>">
</td>
<td>
<input name="aa_v_5" class="form-control" value="<?php echo $all->aa_v_5; ?>">
</td>
</tr>


<tr>
<th><input style="width:20%; background-color: #00a997;border: none;" class="form-control btn btn-danger" type="submit">
</th>
<td>
</td>
</tr>



</tbody>

</table>

</form>

<?php } ?>

</div>
</div>
</div>
</div>




</div>
</div>

@endsection
