@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">
@foreach($funds as $fund)
@endforeach


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



<ol style="background: #00a997; font-size: 18px; color: #fff !important;" class="breadcrumb">
<li>{{$fund->title}}</li>
</ol>
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Features And Benefits:</h4>
</div>
<div class="tables">
	<div class="panel-body widget-shadow">
	<table class="table">
	<tbody>

	@foreach($features_benefits as $fb)
	<tr>
	<td>{{$fb->fb_bullets_rt}}</td>
	<td>{{$fb->fb_bullets_lt}}</td>
	<td>
	<a class="" href="{{url('edit-fab')}}/{{$fb->id}}">Edit</a>
	</td>
	</tr>
	@endforeach


	</tbody>
	</table>

	</div>
	</div>
	<div class="clearfix"> </div></div>
</div>
</div>
<div class="clearfix"> </div>
<!--chart-->


<!-- ASSET ALLOCATION -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For ASSET ALLOCATION:</h4>
</div>
<br>
<div class="col-sm-2">
<button type="button" data-toggle="modal" data-target="#aa" class="btn btn-primary">Add Field</button>
</div>
<br>
<div id="aa" class="modal fade" role="dialog">

<div class="modal-dialog">

<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Asset Allocation: <button class="btn btn-primary" style="background-color: #286090;margin-top:-5px;float:right;" type="button" data-dismiss="modal">X</button></h4>
</div>
<div class="form-body">

<form style="" method="POST" action="{{url('add_aa1')}}" class="form-horizontal">
{{csrf_field()}}
<input type="hidden" name="fund_id" value="{{$fund->id}}">
<div class="row">
<div class="col-sm-12">
Key
<input type="text" name="aa1_key" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
@if($fund->id == 23 ||  $fund->id == 24) CAP @endif @if($fund->id == 25 ||  $fund->id == 26) MMSF @endif
<input type="text" name="aa1_v1" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
@if($fund->id == 23 ||  $fund->id == 24) AAP @endif @if($fund->id == 25 ||  $fund->id == 26) DSF @endif
<input type="text" name="aa1_v2" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
@if($fund->id == 23 ||  $fund->id == 24) SAP @endif @if($fund->id == 25 ||  $fund->id == 26) ESF @endif
<input type="text" name="aa1_v3" value=""   autofocus="autofocus" class="form-control">
</div>
</div>


<div class="row">
<div class="col-sm-2">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</div>




</form>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>
<!--chart-->
</div>
</div>
<br>
<div class="tables">
<table class="table">
<tbody>

<form method="post">
<tr>
<td><input disabled value=""  autofocus="autofocus" class="form-control"></td>

<td><input disabled value="@if($fund->id == 23 ||  $fund->id == 24) CAP @endif @if($fund->id == 25 ||  $fund->id == 26) MMSF @endif"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="@if($fund->id == 23 ||  $fund->id == 24) AAP @endif @if($fund->id == 25 ||  $fund->id == 26) DSF @endif"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="@if($fund->id == 23 ||  $fund->id == 24) SAP @endif @if($fund->id == 25 ||  $fund->id == 26) ESF @endif"  autofocus="autofocus" class="form-control"></td>

<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($asset_allocation as $aa)

<tr>
<td>{{$aa->aa1_key}}</td>
<td>{{$aa->aa1_v1}}</td>
<td>{{$aa->aa1_v2}}</td>
<td>{{$aa->aa1_v3}}</td>
<td>
<a href="{{url('edit_aa1')}}/{{$aa->id}}">Edit</a>
|
<a href="{{url('/delete_aa1')}}/{{$aa->id}}">Delete</a>
</td>
</tr>
@endforeach
</form>
</tbody>
</table>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>

<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For FUND INFORMATION:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_fi1_data')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">

<?php
foreach($fi1 as $fab){ ?>
<div class="col-md-12">
<input id="id" type="hidden" name="fund_id" value="<?php echo $fab->fund_id;?>"   autofocus="autofocus" class="form-control">

<div class="col-md-3">
<input disabled value="Fund Information"  autofocus="autofocus" class="form-control">
<br>   
</div>
@if($fund->id == 23 ||  $fund->id == 24)
<div class="col-md-3">
<input disabled value="CAP"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input disabled value="AAP"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input disabled value="SAP"  autofocus="autofocus" class="form-control">
<br>   
</div>
@endif
@if($fund->id == 25 ||  $fund->id == 26)
<div class="col-md-3">
<input disabled value="MMSF"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input disabled value="DSF"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input disabled value="ESF"  autofocus="autofocus" class="form-control">
<br>   
</div>
@endif

<div class="col-md-3">
<input type="text" name="fi_k1" value="<?php echo $fab->fi_k1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k1v1" value="<?php echo $fab->fi_k1v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k1v2" value="<?php echo $fab->fi_k1v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k1v3" value="<?php echo $fab->fi_k1v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-3">
<input type="text" name="fi_k2" value="<?php echo $fab->fi_k2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k2v1" value="<?php echo $fab->fi_k2v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k2v2" value="<?php echo $fab->fi_k2v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k2v3" value="<?php echo $fab->fi_k2v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-3">
<input type="text" name="fi_k3" value="<?php echo $fab->fi_k3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k3v1" value="<?php echo $fab->fi_k3v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k3v2" value="<?php echo $fab->fi_k3v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k3v3" value="<?php echo $fab->fi_k3v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-3">
<input type="text" name="fi_k4" value="<?php echo $fab->fi_k4;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k4v1" value="<?php echo $fab->fi_k4v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k4v2" value="<?php echo $fab->fi_k4v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k4v3" value="<?php echo $fab->fi_k4v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
    
<div class="col-md-3">
<input type="text" name="fi_k5" value="<?php echo $fab->fi_k5;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k5v1" value="<?php echo $fab->fi_k5v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k5v2" value="<?php echo $fab->fi_k5v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>
<div class="col-md-3">
<input type="text" name="fi_k5v3" value="<?php echo $fab->fi_k5v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>   
<?php } ?>
           
<div class="col-md-2">
<button type="submit" class="btn btn-primary">Submit</button>
<br>   
</div>
</div>
</div> 	

</form>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>



<!-- ///////////////////////// -->



<!-- INVESTMENT AVENUES -->
<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For INVESTMENT AVENUES:</h4>
</div>
<br>
<div class="col-sm-2">
<button type="button" data-toggle="modal" data-target="#ia" class="btn btn-primary">Add Field</button>
</div>
<br>
<div id="ia" class="modal fade" role="dialog">

<div class="modal-dialog">

<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>INVESTMENT AVENUES: <button class="btn btn-primary" style="background-color: #286090;margin-top:-5px;float:right;" type="button" data-dismiss="modal">X</button></h4>
</div>
<div class="form-body">

<form style="" method="POST" action="{{url('add_ia')}}" class="form-horizontal">
{{csrf_field()}}
<div class="row">
<div class="col-sm-12">
<input type="hidden" name="fund_id" value="{{$fund->id}}">
Key
<input type="text" name="ai_key" value=""   autofocus="autofocus" class="form-control">
</div>
</div>




<div class="row">
<div class="col-sm-2">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</div>




</form>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>
<!--chart-->
</div>
</div>
<br>
<div class="tables">
<table class="table">
<tbody>

<form method="post">

@foreach($investment_avenues as $aa)
<tr>
<td>{{$aa->ai_key}}</td>
<td>
<a href="{{url('edit_ia')}}/{{$aa->id}}">Edit</a>
|
<a href="{{url('/delete_ia')}}/{{$aa->id}}">Delete</a>
</td>
</tr>
@endforeach
</form>
</tbody>
</table>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>


<!-- Fund Returns -->

<?php foreach($funds as $fund){

	if($fund->id == 25 || $fund->id == 26){
		?>


<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Fund Returns:</h4>
</div>
<br>
<div class="col-sm-2">
<button type="button" data-toggle="modal" data-target="#fr" class="btn btn-primary">Add Field</button>
</div>
<br>
<div id="fr" class="modal fade" role="dialog">

<div class="modal-dialog">

<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Fund Returns: <button class="btn btn-primary" style="background-color: #286090;margin-top:-5px;float:right;" type="button" data-dismiss="modal">X</button></h4>
</div>
<div class="form-body">

<form style="" method="POST" action="{{url('add_fr1')}}" class="form-horizontal">
{{csrf_field()}}
<input type="hidden" name="fund_id" value="{{$fund->id}}">

<div class="row">
<div class="col-sm-12">
<input type="text" name="fr1_v1" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<input type="text" name="fr1_v2" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<input type="text" name="fr1_v3" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<input type="text" name="fr1_v4" value=""   autofocus="autofocus" class="form-control">
</div>
</div>


<div class="row">
<div class="col-sm-2">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</div>




</form>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>

<!--chart-->
</div>
</div>
<br>
<div class="tables">
<table class="table">
<tbody>

<form method="post">
<tr>
<td><input disabled value="Tenure"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="MMSF"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="DSF"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="ESF"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($fr1 as $aa)

<tr>
<td>{{$aa->fr1_v1}}</td>
<td>{{$aa->fr1_v2}}</td>
<td>{{$aa->fr1_v3}}</td>
<td>{{$aa->fr1_v4}}</td>
<td>
<a href="{{url('edit_fr1')}}/{{$aa->id}}">Edit</a>
|
<a href="{{url('/delete_fr1')}}/{{$aa->id}}">Delete</a>
</td>
</tr>
@endforeach
</form>
</tbody>
</table>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>


<?php }} ?>


@if($fund->id == 23 ||  $fund->id == 24)
<!-- cap -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For CAP:</h4>
</div>
<br>

<div class="tables">
<form method="POST" action="{{url('edit_cap_data')}}" class="form-horizontal">
{{csrf_field()}}	
<table class="table">

<tbody>

<tr>
<td><input disabled value="Year/Days"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Values"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Returns"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($charts as $chart)
@if($chart->plan === 'cap')
<tr>
<td><input disabled name="year" value="{{$chart->year}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="val" value="{{$chart->val}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="ret" value="{{$chart->ret}}"   autofocus="autofocus" class="form-control"></td>
<td><a style="width:100%;" class="btn btn-primary" href="{{url('edit_cap_data')}}/{{$chart->id}}">Edit</a></td>

</tr>
@endif
@endforeach

</tbody>

</table>
</form>
</div>
</div>
</div>
</div>
<div class="clearfix"> </div>


<!-- aap -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For AAP:</h4>
</div>
<br>

<div class="tables">
<table class="table">

<tbody>

<tr>
<td><input disabled value="Year/Days"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Values"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Returns"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($charts as $chart)
@if($chart->plan === 'aap')
<tr>
<td><input disabled name="year" value="{{$chart->year}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="val" value="{{$chart->val}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="ret" value="{{$chart->ret}}"   autofocus="autofocus" class="form-control"></td>
<td><a style="width:100%;" class="btn btn-primary" href="{{url('edit_aap_data')}}/{{$chart->id}}">Edit</a></td>

</tr>
@endif
@endforeach

</tbody>

</table>
</div>
</div>
</div>
</div>
<div class="clearfix"> </div>

<!-- sap -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For SAP:</h4>
</div>
<br>

<div class="tables">
<table class="table">

<tbody>

<tr>
<td><input disabled value="Year/Days"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Values"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Returns"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($charts as $chart)
@if($chart->plan === 'sap')
<tr>
<td><input disabled name="year" value="{{$chart->year}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="val" value="{{$chart->val}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="ret" value="{{$chart->ret}}"   autofocus="autofocus" class="form-control"></td>
<td><a style="width:100%;" class="btn btn-primary" href="{{url('edit_sap_data')}}/{{$chart->id}}">Edit</a></td>

</tr>
@endif
@endforeach

</tbody>

</table>
</div>
</div>
</div>
</div>
<div class="clearfix"> </div>
@endif


@if($fund->id == 25 ||  $fund->id == 26)
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For MMSF:</h4>
</div>
<br>

<div class="tables">
<form method="POST" action="{{url('edit_mmsf_data')}}" class="form-horizontal">
{{csrf_field()}}	
<table class="table">

<tbody>

<tr>
<td><input disabled value="Year/Days"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Values"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Returns"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($charts as $chart)
@if($chart->plan === 'mmsf')
<tr>
<td><input disabled name="year" value="{{$chart->year}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="val" value="{{$chart->val}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="ret" value="{{$chart->ret}}"   autofocus="autofocus" class="form-control"></td>
<td><a style="width:100%;" class="btn btn-primary" href="{{url('edit_mmsf_data')}}/{{$chart->id}}">Edit</a></td>

</tr>
@endif
@endforeach

</tbody>

</table>
</form>
</div>
</div>
</div>
</div>
<div class="clearfix"> </div>


<!-- aap -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For DSF:</h4>
</div>
<br>

<div class="tables">
<table class="table">

<tbody>

<tr>
<td><input disabled value="Year/Days"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Values"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Returns"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($charts as $chart)
@if($chart->plan === 'dsf')
<tr>
<td><input disabled name="year" value="{{$chart->year}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="val" value="{{$chart->val}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="ret" value="{{$chart->ret}}"   autofocus="autofocus" class="form-control"></td>
<td><a style="width:100%;" class="btn btn-primary" href="{{url('edit_dsf_data')}}/{{$chart->id}}">Edit</a></td>

</tr>
@endif
@endforeach

</tbody>

</table>
</div>
</div>
</div>
</div>
<div class="clearfix"> </div>

<!-- sap -->
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For ESF:</h4>
</div>
<br>

<div class="tables">
<table class="table">

<tbody>

<tr>
<td><input disabled value="Year/Days"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Values"   autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Returns"  autofocus="autofocus" class="form-control"></td>
<td><input disabled value="Action"  autofocus="autofocus" class="form-control"></td>
</tr>

@foreach($charts as $chart)
@if($chart->plan === 'esf')
<tr>
<td><input disabled name="year" value="{{$chart->year}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="val" value="{{$chart->val}}"   autofocus="autofocus" class="form-control"></td>
<td><input disabled name="ret" value="{{$chart->ret}}"   autofocus="autofocus" class="form-control"></td>
<td><a style="width:100%;" class="btn btn-primary" href="{{url('edit_esf_data')}}/{{$chart->id}}">Edit</a></td>

</tr>
@endif
@endforeach

</tbody>

</table>
</div>
</div>
</div>
</div>
<div class="clearfix"> </div>
@endif


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Explanation:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_exp_data')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">

<?php
foreach($exp as $fab){ ?>

<input id="id" type="hidden" name="fund_id" value="<?php echo $fab->fund_id;?>"   autofocus="autofocus" class="form-control">
<div class="row">
<div class="col-md-6">
<input type="text" name="shd" value="<?php echo $fab->shd;?>"   autofocus="autofocus" class="form-control">
</div>
</div> 
<div class="row">
<div class="col-md-6">
<input type="text" name="title" value="<?php echo $fab->title;?>"   autofocus="autofocus" class="form-control">
</div>
</div>    
<div class="row">
<div class="col-md-12">
<textarea name="desc" autofocus="autofocus" class="form-control"><?php echo $fab->desc;?></textarea>	
</div>
</div>    
<?php } ?>
<br>             
</div> 	
<button type="submit" class="btn btn-primary">Submit</button>

</form>

</div>
</div>
</div>
</div>
<div class="clearfix"> </div>





</div>
</div>

@endsection
