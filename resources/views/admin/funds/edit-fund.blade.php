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


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Charts:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_chart_field')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">


<div class="col-md-12">


<div class="row">
<div class="col-md-4">
<input value="Year" disabled   autofocus="autofocus" class="form-control">
</div>
<div class="col-md-4">
<input value="Value" disabled   autofocus="autofocus" class="form-control">
</div>

<div class="col-md-4">
<input value="Return %" disabled   autofocus="autofocus" class="form-control">
</div>

</div>
<?php foreach($ch_fields as $ch){ ?>
<div class="row">

<div class="col-md-4">
<input type="hidden" name="id" value="<?php echo $ch->id?>"   autofocus="autofocus" class="form-control">
<input type="hidden" id="fund_id" value="<?php echo $f_id = $ch->fund_id?>"   autofocus="autofocus" class="form-control">
<input name="year" value="<?php echo $ch->year?>"   autofocus="autofocus" class="form-control">
</div>

<div class="col-md-4">
<input name="val" value="<?php echo $ch->val?>"   autofocus="autofocus" class="form-control">
</div>

<div class="col-md-4">
<input name="ret" value="<?php echo $ch->ret?>"   autofocus="autofocus" class="form-control">
</div>


</div>
<div class="row">     
<div class="col-md-1">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
<div class="col-md-2">
<a href="{{url('add_fund_data')}}/<?php echo $ch->fund_id?>" id="gtf" class="btn btn-primary">Go Back</a>
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
