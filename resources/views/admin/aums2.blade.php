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
<form method="post" action="{{url('aums2')}}">
{{csrf_field()}}	


<div class="row">
<div class="col-md-12"><h3>Title</h3></div>
<div class="col-md-12"><input  name="title" class="form-control" value=""></div>
</div>


<div class="row">
<div class="col-md-12"><h3>Field 1</h3></div>
<div class="col-md-12"><textarea name="f1" class="form-control" value=""></textarea></div>
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
