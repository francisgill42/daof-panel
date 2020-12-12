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
<?php foreach($results as $result){?>
<form method="post" action="{{url('hamls-edit')}}">
{{csrf_field()}}	


<div class="row">
<div class="col-md-12"><h3>Title</h3></div>
<div class="col-md-12"><input  name="title" class="form-control" value="{{$result->title}}"></div>
</div>

<div class="row">
<div class="col-md-12"><h3>Boxes</h3></div>

<div class="col-md-4">
<?php $b1 = str_replace('<br />', '', $result->b1);?>
<textarea style="height:100px;" name="b1" class="form-control" value=""><?php echo $b1; ?></textarea>
</div>
<div class="col-md-4">
<?php $b2 = str_replace('<br />', '', $result->b2);?>
<textarea style="height:100px;" name="b2" class="form-control" value=""><?php echo $b2; ?></textarea>
</div>
<div class="col-md-4">
<?php $b3 = str_replace('<br />', '', $result->b3);?>
<textarea style="height:100px;" name="b3" class="form-control" value=""><?php echo $b3; ?></textarea>

</div>
</div>
<div class="row">
<div class="col-md-4">
<?php $b4 = str_replace('<br />', '', $result->b4);?>
<textarea style="height:100px;" name="b4" class="form-control" value=""><?php echo $b4; ?></textarea>
</div>
<div class="col-md-4">
<?php $b5 = str_replace('<br />', '', $result->b5);?>
<textarea style="height:100px;" name="b5" class="form-control" value=""><?php echo $b5; ?></textarea>
</div>
<div class="col-md-4">
<?php $b6 = str_replace('<br />', '', $result->b6);?>
<textarea style="height:100px;" name="b6" class="form-control" value=""><?php echo $b6; ?></textarea>
</div>

</div>

<div class="row">
<div class="col-md-12"><h3>Field 1</h3></div>
<div class="col-md-12"><textarea name="f1" class="form-control"><?php echo $result->f1; ?></textarea></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 2</h3></div>
<div class="col-md-12"><textarea name="f2" class="form-control"><?php echo $result->f2; ?></textarea></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 3</h3></div>
<div class="col-md-12"><textarea name="f3" class="form-control"><?php echo $result->f3; ?></textarea></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 4</h3></div>
<div class="col-md-12"><textarea name="f4" class="form-control"><?php echo $result->f4; ?></textarea></div>
</div>


<div class="row">

<div class="col-md-2">
<br>
<input  style=" background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</div>

</div>
<?php } ?>
</form>
</div>
</div>
</div>
</div>




</div>
</div>

@endsection
