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
<h4>Starting Year</h4>
</div>
<div class="form-body">
<?php foreach($results as $result){?>
<form method="post" action="{{url('update_yr_chart')}}">
{{csrf_field()}}	
<table class="table slides">
<tbody>
<tr>
<td>
<input type="hidden" name="id" class="form-control" value="<?php echo $result->id; ?>">
<input name="yr" class="form-control" value="<?php echo $result->yr; ?>">
</td>
</tr>

<tr>
<td>
<input  style="width:10%; background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
</td>
</tr>

</tbody>

</table>
<?php } ?>
</form>
</div>
</div>
</div>
</div>

<?php 
foreach($banks as $bank){
get_sponsor_chart_div($bank->bank,$bank->id);
}
?>


<!-- bank details -->
<div class="forms">

<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Bank Details:</h4>
</div>

<div class="form-body">
<table class="table">
<thead>
<tr>
<th>Bank</th>
<th>Color Code</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@foreach($banks as $bank)
<tr>
<td>{{$bank->bank}}</td>
<td>{{$bank->bank_color}}</td>
<td>
<a style="width:50%;" class="btn btn-primary" href="{{url('edit-bank-names')}}/{{$bank->id}}">Edit</a>
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
</div>

@endsection





<?php function get_sponsor_chart_div($bank_name,$bank_id){ ?>
<div class="forms">
<div class=" form-grids row form-grids-right">
<div class="widget-shadow " data-example-id="basic-forms"> 
<div class="form-title">
<h4>Edit Data For {{$bank_name}}:</h4>
</div>
<br>
<div class="tables">
<table class="table">

<tbody>

<?php  foreach(\DB::table('sc_yr')->pluck('yr') as $i) {}  
         foreach(\DB::table('sponsor_chart_1')->get() as $chart){
            if($chart->bank_id == $bank_id){ ?>
<tr>
<input type="hidden" value="{{$chart->bank_id}}">
<td><input disabled value="{{$i++}}" autofocus="autofocus" class="form-control"></td>
<td><input disabled name="sc_1_nums"  value="{{$chart->sc_1_nums}}" autofocus="autofocus" class="form-control"></td>
<td>
<a type="submit" style="width:100%;" class="btn btn-primary" href="{{url('edit-scf')}}/{{$chart->id}}">Edit</a>
</td>
</tr>
<?php }}?>


</tbody>

</table>

</div>
</div>
</div>
</div>
<?php
}
?>
