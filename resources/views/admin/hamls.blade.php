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
<form method="post" action="{{url('hamls')}}">
{{csrf_field()}}	


<div class="row">
<div class="col-md-12"><h3>Title</h3></div>
<div class="col-md-12"><input  name="title" class="form-control" value=""></div>
</div>

<div class="row">
<div class="col-md-12"><h3>Boxes</h3></div>

<div class="col-md-2">
<input name="b1" class="form-control" value="" required>
</div>
<div class="col-md-2">
<input name="b2" class="form-control" value="" required>
</div>
<div class="col-md-2">
<input name="b3" class="form-control" value="" required>
</div>
<div class="col-md-2">
<input name="b4" class="form-control" value="" required>
</div>
<div class="col-md-2">
<input name="b5" class="form-control" value="" required>
</div>
<div class="col-md-2">
<input name="b6" class="form-control" value="" required>
</div>

</div>

<div class="row">
<div class="col-md-12"><h3>Field 1</h3></div>
<div class="col-md-12"><textarea name="f1" class="form-control" value=""></textarea></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 2</h3></div>
<div class="col-md-12"><textarea name="f2" class="form-control" value=""></textarea></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 3</h3></div>
<div class="col-md-12"><textarea name="f3" class="form-control" value=""></textarea></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 4</h3></div>
<div class="col-md-12"><textarea name="f4" class="form-control" value=""></textarea></div>
</div>


<div class="row">

<div class="col-md-2">
<br>
<input  style=" background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</div>

</div>

</form>
</div>
</div>
</div>
</div>




</div>
</div>

@endsection
