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

								
<div class="form-group">


<?php foreach($data as $ch){ ?>
<form method="POST" action="{{url('edit-bank-names')}}" class="form-horizontal">
{{csrf_field()}}

<div class="row">


<div class="col-md-4">
<input name="bank" value="<?php echo $ch->bank;?>"   autofocus="autofocus" class="form-control">
</div>  

<div class="col-md-4">
<input name="id" value="<?php echo $ch->id;?>" type="hidden">
<input name="bank_color" value="<?php echo $ch->bank_color;?>"   autofocus="autofocus" class="form-control">
</div>  

</div>



<?php } ?>
<div class="row">     

<div class="col-md-1">
<button type="submit" class="btn btn-primary">Submit</button>
</div>

<div class="col-md-1">
<a href="{{url('sponsers-edit')}}" class="btn btn-primary">Go Back</a>
</div>
</div>   
</form>

</div>
</div> 	



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
