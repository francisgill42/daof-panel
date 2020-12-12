@extends('layouts.admin-app')

@section('content')
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
		
            <div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Features and Benefits:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_features_benefits')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">


<div class="col-md-12">
<?php foreach($features_benefits as $fab){ ?>
<input id="id" type="hidden" name="id" value="<?php echo $fab->id;?>"   autofocus="autofocus" class="form-control">
<div class="col-sm-6">
<input type="text" name="fb_bullets_rt" value="<?php echo $fab->fb_bullets_rt;?>"   autofocus="autofocus" class="form-control">
</div>
<div class="col-sm-6">
<input type="text" name="fb_bullets_lt" value="<?php echo $fab->fb_bullets_lt;?>"   autofocus="autofocus" class="form-control">
</div>
<br>
<br>

<div class="col-md-12">
<button type="submit" class="btn btn-primary">Submit</button>
<a href="{{url('add_fund_data')}}/<?php echo $fab->fund_id?>" id="gtf" class="btn btn-primary">Go Back</a>
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
