@extends('layouts.admin-app')

@section('content')
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">

<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Asset Allocation:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_aa1')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">


<div class="col-md-12">
<?php foreach($aa1 as $aa){ ?>
<input id="id" type="hidden" name="id" value="<?php echo $aa->id;?>"   autofocus="autofocus" class="form-control">
         
<div class="col-sm-3">
<input type="text" name="aa1_key" value="<?php echo $aa->aa1_key;?>"   autofocus="autofocus" class="form-control">
</div>
<div class="col-sm-3">
<input type="text" name="aa1_v1" value="<?php echo $aa->aa1_v1;?>"   autofocus="autofocus" class="form-control">
</div>

<div class="col-sm-3">
<input type="text" name="aa1_v2" value="<?php echo $aa->aa1_v2;?>"   autofocus="autofocus" class="form-control">
</div>

<div class="col-sm-3">
<input type="text" name="aa1_v3" value="<?php echo $aa->aa1_v3;?>"   autofocus="autofocus" class="form-control">
</div>

<br>
<br>

<div class="col-md-12">
<button type="submit" class="btn btn-primary">Submit</button>
<a href="{{url('add_fund1_data')}}/<?php echo $aa->fund_id?>" id="gtf" class="btn btn-primary">Go Back</a>
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


				
				
				
			</div>
		</div>

@endsection
