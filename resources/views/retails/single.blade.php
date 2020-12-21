@extends('layouts.retail-app')

@section('content')
<!-- main content start-->
<style type="text/css">
input[type='checkbox'] {
-webkit-appearance:none;
width:30px;
height:30px;
background:white;
border-radius:5px;
border:2px solid #555;
}
input[type='checkbox']:checked {
background: #abd;
}

@media print
{
input[type='checkbox']{display: none;}

.dropdown-toggle {display:none!important;}

button.printbtn{display: none!important;}

.btnsiscussion{display: none!important;}

.form-title
{
/*display: none!important;*/
margin-top: -200px;
border: none!important;
}

.form-title h4
{
font-weight: 700;
font-size: 24px!important;
margin-left: -12px!important;
}

.addspecial{display: none!important;}

.to_cbc{display: none!important;}

.bckchanges{display: none!important;}

.textblock{display: none!important;}

.header-section{display:none!important;}

.c_details
{
  margin-top: -80px!important; 
  background-color:#9c9e9b!important;
}

.bank_detail{margin-top: -30px!important;}

.fatca_detail{margin-top:-30px!important;}

.action{margin-top:-30px!important;}

.tables {margin-top:20px!important;}

.tables h4
{
color: #000000!important;
font-weight:600!important;
border: solid #000!important;
}

.alert{padding: 5px!important;}

img{width: 75px!important; height: 75px!important;}

.footer
{margin-top:40px!important;
display:none!important;
}

.tables .table > thead > tr > th, .tables .table > tbody > tr > th, .tables .table > tfoot > tr > th, .tables .table > thead > tr > td, .tables .table > tbody > tr > td, .tables .table > tfoot > tr > td 
{
    border-top: 1px solid #150000!important;
    line-height: 01;
}

.inv_details{margin-top:-30px!important;}

table.table tr td:nth-child(1) {
    display: none;
}

td.td_print{padding-left: 145px!important;}

td.td_print_fatca{padding-right: 200px!important;}

td.td_print_action{padding-left: 130px!important;}

}

</style>
<div id="page-wrapper">

<form method="post" action="{{url('ptc')}}">
{{csrf_field()}}

<input type="hidden" name="form_data" value="{{json_encode($form_data)}}">
<input type="hidden" name="customer_details" value="{{json_encode($customer_details)}}">
<input type="hidden" name="bank_details" value="{{json_encode($bank_details)}}">
<input type="hidden" name="investment_details" value="{{json_encode($investment_details)}}">
<input type="hidden" name="fatca_details" value="{{json_encode($fatca_details)}}">
<input type="hidden" name="other_details" value="{{json_encode($other_details)}}">
<button style="display: none;" type="submit" class="btn btn-danger printbtn" style="">Push to CRM</button>
<br>
</form>

<button style="display: none;" type="submit" class="btn btn-danger printbtn" onclick="window.print()" style="float: right; margin-top: -35px;">Print</button>


<div class="main-page">

  @if (session()->has('msg'))
  <br>
<div class="alert alert-info" role="alert">
<strong></strong> {{session('msg')}}
</div>
@endif

@if (session()->has('err'))
<div class="alert alert-danger" role="alert">
{{session('err')}}
</div>
@endif

<br>



<div class="form-title" style="background:orange;">

<h4 style="color:white;">Form Id # {{$form_id}}
  <button type="button" class="btn btn-danger btnsiscussion" style="float:right;margin-top:-5px;" data-toggle="modal" data-target="#myModal">Discussion</button>
</h4>


</div>


<div class="tables">
<div class="panel-body widget-shadow">
<div id="cbc" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Comments for CBC User</h4>
</div>
<div class="modal-body">
<form method="post" action="{{url('send_to_cbc')}}/{{$form_id}}">
{{csrf_field()}}
<textarea required name="msg" class="form-control" rows="4"></textarea>
<br><button type="submit" class="btn btn-danger" style="">Send To CBC</button>
</form>
</div>

</div>

</div>
</div>
<button type="button" style="float:right;margin:0px;" data-toggle="modal" data-target="#cbc"  class="btn btn-danger to_cbc">
	Send To CBC
</button>


<form  method="post" action="{{url('send_back')}}/{{$form_id}} " enctype="multipart/form-data">

<table class="table">
<tbody>
<button type="submit" class="btn btn-danger bckchanges" style="float:right; margin-right: 5px;">Send Back To Changes</button>
{{csrf_field()}}

<br>
<strong class="addspecial">Add Special Note</strong>
<br>
<textarea required name="msg" class="form-control textblock" rows="4"></textarea>
<br>

<h4 class="alert alert-danger c_details">Customer Details</h4>
<tr>
<td>
<input type="hidden" name="user_id" value="{{$form_data->user_id}}">
<input type="checkbox" name="cd[]" value="name">
</td>
<th>Name</th>
<td>{{$customer_details->name}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="father_name">
</td>
<th>Father Name</th>
<td>{{$customer_details->father_name}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="mother_name">
</td>
<th>Mother Name</th>
<td>{{$customer_details->mother_name}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="dob">
</td>
<th>Date Of Birth</th>
<td>{{$customer_details->dob}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="cnic">
</td>
<th>CNIC</th>
<td>{{$customer_details->cnic}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="cnic_issue_date">
</td>
<th>CNIC Issue Date</th>
<td>{{$customer_details->cnic_issue_date}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="pob_country">
</td>
<th>Place Of Birth (Country)</th>
<td>{{explode('|',$customer_details->pob_country)[1]}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="pob_city">
</td>
<th>Place Of Birth (City)</th>
<td>{{explode('|',$customer_details->pob_city)[1]}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="email">
</td>
<th>Email</th>
<td>{{$customer_details->email}}</td>
</tr>


<tr>
<td>
<input type="checkbox" name="cd[]" value="cell">
</td>
<th>Cell</th>
<td>{{$customer_details->cell}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="occupation">
</td>
<th>Occupation</th>
<td>{{$customer_details->occupation}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="education">
</td>
<th>Education</th>
<td>{{$customer_details->education}}</td>
</tr>

</tbody>
</table>


<table class="table">
<tbody>

<h4 class="alert alert-danger c_details">Attachments</h4>

<tr>
<td>
<input type="checkbox" name="cd[]" value="cnic_attachment">
</td>
<th>CNIC Attachment</th>
<td>
<button id="cnic_attachment" type="button" style="background: none; border:none;" value="{{$customer_details->cnic_attachment}}" class="pop">
@if($customer_details->cnic_attachment)
<img width="150px" height="150px" src="{{url('uploads/cnic_attachment')}}/{{$customer_details->cnic_attachment}}">
@endif
</button>
</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="soi_attachment">
</td>
<th>Source Of Income Attachment</th>
<td>
<button id="soi_attachment" type="button" style="background: none; border:none;" value="{{$customer_details->soi_attachment}}" class="pop">
	@if($customer_details->soi_attachment)
<img width="150px" height="150px" src="{{url('uploads/soi_attachment')}}/{{$customer_details->soi_attachment}}">
@endif
</button>
</td>
</tr>


<tr>
<td>
<input type="checkbox" name="cd[]" value="zakat_certificate">
</td>
<th>Zakat Certificate</th>
<td>
<button id="zc" type="button" style="background: none; border:none;" value="{{$customer_details->zakat_certificate}}" class="pop">
@if($customer_details->soi_attachment)
<img width="150px" height="150px" src="{{url('uploads/zakat_certificates')}}/{{$customer_details->zakat_certificate}}">
@endif
</button>
</td>
</tr> 

</tbody>
</table>

</form>


<!-- Modal -->




</div>

</div>

<div class="clearfix"> </div>


</div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog">



<div class="container">
<div class="row">
<div class="col-md-5">
<div class="panel">
<div class="panel-heading">
<span class="glyphicon glyphicon-comment"></span> Chat
<button type="button" class="close" data-dismiss="modal">&times;</button>
<hr>

</div>
<div class="panel-body">
<ul class="chat">

@foreach($msgs as $msg)
@php 
$role = \DB::table('users')->where('id',$msg->sender_id)->pluck('role_id')[0]; 
$string = ($role == 5) ? 'CBC' : 'S';
@endphp
@if($role == 1 || $role == 5)

<li class="left clearfix" style="list-style-type: none;"><span class="chat-img pull-left">
<img src="http://placehold.it/50/55C1E7/fff&text={{$string}}" alt="User Avatar" class="img-circle" />
</span>
<div class="chat-body clearfix">
<div class="header">
<strong style="margin-left:7px;">{{DB::table('users')->where('id',$msg->sender_id)->first()->name}}</strong>
</div>
<p style="padding-left: 14%;     margin-bottom: 10%;">
{{$msg->msg}}
<br>
@php $d = new DateTime($msg->created_at);
$dates = $d->format('M d, h:i:sa');
@endphp
{{$dates}}
</p>
</div>
</li>
@else
@php
$role = \DB::table('users')->where('id',$msg->sender_id)->pluck('role_id')[0];
$string = ($role == 3) ? 'RA' : 'R';
@endphp
@if($role != 5)

<li class="right clearfix" style="list-style-type: none;"><span class="chat-img pull-right">
<img src="http://placehold.it/50/FA6F57/fff&text={{$string}}" alt="User Avatar" class="img-circle" />
</span>
<div class="chat-body clearfix">
<div class="header text-right">
<strong style="margin-left:7px;">{{DB::table('users')->where('id',$msg->sender_id)->first()->name}}</strong>
</div>
<p style="padding-right: 2%; float:right;    margin-bottom: 10%;">
{{$msg->msg}}
<br>
@php $d = new DateTime($msg->created_at);
$dates = $d->format('M d, h:i:sa');
@endphp
{{$dates}}
</p>
</div>
</li>
@endif
@endif
@endforeach


</ul>
</div>

</div>
</div>
</div>
</div>


</div>
</div>

<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" id="myModalLabel">Image preview</h4>
</div>
<div class="modal-body">
<img src="" id="imagepreview" style="width: 100%; height: auto;" >
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<script type="text/javascript">

$("#zc").on("click", function() {
  
var url = '<?php echo url('uploads/zakat_certificates/');?>';
$('#imagepreview').attr('src',url+'/'+ this.value);
$('#imagemodal').modal('show');
});

$("#id").on("click", function() {
  
  var url = '<?php echo url('uploads/investment_detail_attachments/');?>';
  $('#imagepreview').attr('src',url+'/'+ this.value);
  $('#imagemodal').modal('show');
  });


$("#cnic_attachment").on("click", function() {
  
  var url = '<?php echo url('uploads/cnic_attachment/');?>';
  $('#imagepreview').attr('src',url+'/'+ this.value);
  $('#imagemodal').modal('show');
  });


$("#soi_attachment").on("click", function() {
  
  var url = '<?php echo url('uploads/soi_attachment/');?>';
  $('#imagepreview').attr('src',url+'/'+ this.value);
  $('#imagemodal').modal('show');
  });


</script>
@endsection
