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

<!-- Features And Benefits -->
<div class="forms">
    <div class=" form-grids row form-grids-right">
        <div class="widget-shadow " data-example-id="basic-forms"> 
            <div class="form-title">
                <h4>Edit Data For Features And Benefits:</h4>
            </div>
            <div class="form-body">
                <div class="tables">
                    <table class="table">
                        <tbody>
                        @foreach($features_benefits as $fb)
                        <tr>
                        <td>{{$fb->fb_bullets_rt}}</td>
                        <td>{{$fb->fb_bullets_lt}}</td>
                        <td>
                        <a class="" href="{{url('edit-features_benefits')}}/{{$fb->id}}">Edit</a>
                        </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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
                <form method="POST" action="{{url('edit_fi_data')}}" class="form-horizontal">
                    {{csrf_field()}}								
                    <div class="form-group">

<?php
foreach($fi as $fab){ ?>
<div class="col-md-12">
<input id="id" type="hidden" name="fund_id" value="<?php echo $fab->fund_id;?>"   autofocus="autofocus" class="form-control">
<div class="col-md-8">
<input type="text" name="fi_k_1" value="<?php echo $fab->fi_k_1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-4">
<input type="text" name="fi_v_1" value="<?php echo $fab->fi_v_1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-8">
<input type="text" name="fi_k_2" value="<?php echo $fab->fi_k_2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-4">
<input type="text" name="fi_v_2" value="<?php echo $fab->fi_v_2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-8">
<input type="text" name="fi_k_3" value="<?php echo $fab->fi_k_3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-4">
<input type="text" name="fi_v_3" value="<?php echo $fab->fi_v_3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-8">
<input type="text" name="fi_k_4" value="<?php echo $fab->fi_k_4;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-4">
<input type="text" name="fi_v_4" value="<?php echo $fab->fi_v_4;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-8">
<input type="text" name="fi_k_5" value="<?php echo $fab->fi_k_5;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>

<div class="col-md-4">
<input type="text" name="fi_v_5" value="<?php echo $fab->fi_v_5;?>"   autofocus="autofocus" class="form-control">
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
<div id="aa" class="modal fade" role="dialog">

<div class="modal-dialog">

<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Asset Allocation: <button class="btn btn-primary" style="background-color: #286090;margin-top:-5px;float:right;" type="button" data-dismiss="modal">X</button></h4>
</div>
<div class="form-body">

<form style="" method="POST" action="{{url('add_asset_allocation')}}" class="form-horizontal">
{{csrf_field()}}
<input type="hidden" name="fund_id" value="{{$fund->id}}">
<div class="row">
<div class="col-sm-12">
Key
<input type="text" name="aa_keys" value=""   autofocus="autofocus" class="form-control">
</div>
</div>

<div class="row">
<div class="col-sm-12">
Value
<input type="text" name="aa_values" value=""   autofocus="autofocus" class="form-control">
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
<form style="display:none;" method="POST" action="{{url('add_asset_allocation')}}" class="form-horizontal">
{{csrf_field()}}
<input type="hidden" name="fund_id" value="{{$fund->id}}">
<div class="row">
<div class="col-sm-5">
<input type="text" name="aa_keys" value=""   autofocus="autofocus" class="form-control">
</div>
<div class="col-sm-5">
<input type="text" name="aa_values" value=""   autofocus="autofocus" class="form-control">
</div>

<div class="col-sm-2">
<button type="submit" class="btn btn-primary">Add Field</button>
</div>
</div>


</form>
<br>
<div class="tables">
<table class="table">
<tbody>

<form method="post">

@foreach($asset_allocation as $aa)
<tr>
<td>{{$aa->aa_keys}}</td>
<td>{{$aa->aa_values}}</td>
<td>
<a href="{{url('edit_asset_allocation')}}/{{$aa->id}}">Edit</a>
|
<a href="{{url('/delete_asset_allocation')}}/{{$aa->id}}">Delete</a>
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



<!-- fund returns -->


<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For Fund Returns:</h4>
</div>
<div class="form-body">

<form method="POST" action="{{url('edit_fr_data')}}" class="form-horizontal">
{{csrf_field()}}								
<div class="form-group">

<?php
foreach($fr as $fab){ ?>
<div class="col-md-4">
<input id="id" type="hidden" name="fund_id" value="<?php echo $fab->fund_id;?>"   autofocus="autofocus" class="form-control">

<input type="text"  name="k1" value="<?php echo $fab->k1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text"  name="k2" value="<?php echo $fab->k2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text"  name="k3" value="<?php echo $fab->k3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>



<div class="col-md-4">
<input type="text" name="k1v1" value="<?php echo $fab->k1v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k2v1" value="<?php echo $fab->k2v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k3v1" value="<?php echo $fab->k3v1;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>



<div class="col-md-4">
<input type="text" name="k1v2" value="<?php echo $fab->k1v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k2v2" value="<?php echo $fab->k2v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k3v2" value="<?php echo $fab->k3v2;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k1v3" value="<?php echo $fab->k1v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k2v3" value="<?php echo $fab->k2v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k3v3" value="<?php echo $fab->k3v3;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>




<div class="col-md-4">
<input type="text" name="k1v4" value="<?php echo $fab->k1v4;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k2v4" value="<?php echo $fab->k2v4;?>"   autofocus="autofocus" class="form-control">
<br>   
</div>


<div class="col-md-4">
<input type="text" name="k3v4" value="<?php echo $fab->k3v4;?>"   autofocus="autofocus" class="form-control">
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

<form style="" method="POST" action="{{url('add_investment_avenues')}}" class="form-horizontal">
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
<a href="{{url('edit_investment_avenues')}}/{{$aa->id}}">Edit</a>
|
<a href="{{url('/delete_investment_avenues')}}/{{$aa->id}}">Delete</a>
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

<!--chart-->
<div class="row">
<div class="tables">
<div class="panel-body widget-shadow">
<h4>Edit Data For Charts:</h4>
<table class="table">
<thead>
<tr>
<th>Year</th>
<th>Value</th>
<th>Return %</th>
<th>Action</th>
</tr>
</thead>
<tbody>

@foreach($charts as $chart)
<tr>
<td>{{$chart->year}}</td>
<td>{{$chart->val}}</td>
<td>{{$chart->ret}}</td>
<td>
<a class="" href="{{url('edit-chart-field')}}/{{$chart->id}}">Edit</a>
</td>
</tr>
@endforeach


</tbody>
</table>
</div>

</div>
</div>
<div class="clearfix"> </div>





</div>
</div>

@endsection
