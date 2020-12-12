@extends('layouts.admin-app')

@section('content')
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">

<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For MMSF:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_mmsf_data')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">


<div class="col-md-12">
<div class="row">
<div class="col-sm-4">
<input disabled value="Year/Days"   autofocus="autofocus" class="form-control">
</div>

<div class="col-sm-4">
<input disabled value="Value"   autofocus="autofocus" class="form-control">
</div>

<div class="col-sm-4">
<input disabled value="Returns %"   autofocus="autofocus" class="form-control">
</div>

</div>

@foreach($caps as $aa)
<div class="row">
<input id="id" type="hidden" name="id" value="{{$aa->id}}"   autofocus="autofocus" class="form-control">
<div class="col-sm-4">
<input type="text" name="year" value="{{$aa->year}}"   autofocus="autofocus" class="form-control">
</div>
<div class="col-sm-4">
<input type="text" name="val" value="{{$aa->val}}"   autofocus="autofocus" class="form-control">
</div>
<div class="col-sm-4">
<input type="text" name="ret" value="{{$aa->ret}}"   autofocus="autofocus" class="form-control">
</div>
</div>
<br>
<br>

<div class="col-md-12">
<button type="submit" class="btn btn-primary">Submit</button>
<a href="{{url('add_fund1_data')}}/{{$aa->fund_id}}"  class="btn btn-primary">Go Back</a>
</div>
@endforeach

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
