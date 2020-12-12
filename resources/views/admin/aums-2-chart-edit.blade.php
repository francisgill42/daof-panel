@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">

@if (session()->has('msg'))
<div class="alert alert-success" role="alert">
<strong>Well done!</strong> {{session('msg')}}
</div>
@endif

@if (session()->has('err'))
<div class="alert alert-danger" role="alert">
{{session('err')}}
</div>
@endif


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Charts:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit-aums2-chart ')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">


<div class="col-md-12">


<div class="row">
<div class="col-md-6">
<input value="Year/Date" disabled   autofocus="autofocus" class="form-control">
</div>
<div class="col-md-6">
<input value="Value" disabled   autofocus="autofocus" class="form-control">
</div>



</div>
<?php foreach($ch_fields as $ch){ ?>
<div class="row">

<div class="col-md-6">
<input type="hidden" name="id" value="<?php echo $ch->id?>"   autofocus="autofocus" class="form-control">
<input name="aums_2_key" value="<?php echo $ch->aums_2_key?>"   autofocus="autofocus" class="form-control">
</div>

<div class="col-md-6">
<input name="aums_2_value" value="<?php echo $ch->aums_2_value?>"   autofocus="autofocus" class="form-control">
</div>




</div>
<div class="row">     
<div class="col-md-1">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
<div class="col-md-2">
<a href="{{url('aums2-edit')}}" class="btn btn-primary">Go Back</a>
</div>
</div>   
<?php } ?>

</div>
</div> 	

</form>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>


<script>
//alert($('#gtf').val($('#fund_id').val()));
</script>
</div>
</div>

@endsection
