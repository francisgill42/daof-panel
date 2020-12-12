<?php //dd($msgs);?>
@extends('layouts.admin-app')

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
</style>
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

<br>
<div class="form-title">
<h4 style="color:white;">Form Id # {{$form_id}}
<button type="button" class="btn btn-success" style="float:right;margin-top:-5px;" data-toggle="modal" data-target="#myModal">Discussion</button>
</h4>
</div>


<div class="tables">
<div class="panel-body widget-shadow">


<table class="table">
<tbody>

<h4 class="alert alert-success">Customer Details</h4>
<tr>



<th>Name</th>
<td>
<input type="hidden" name="user_id" value="{{$form_data->user_id}}">
{{$customer_details->name}}</td>
</tr>

<tr>
<th>Father Name</th>
<td>{{$customer_details->father_name}}</td>
</tr>

<tr>
<th>Mother Name</th>
<td>{{$customer_details->mother_name}}</td>
</tr>

<tr>
<th>Date Of Birth</th>
<td>{{$customer_details->dob}}</td>
</tr>

<tr>
<th>CNIC</th>
<td>{{$customer_details->cnic}}</td>
</tr>

<tr>
<th>CNIC Issue Date</th>
<td>{{$customer_details->cnic_issue_date}}</td>
</tr>

<tr>
<th>Place Of Birth (Country)</th>
<td>{{$customer_details->pob_country}}</td>
</tr>

<tr>
<th>Place Of Birth (City)</th>
<td>{{$customer_details->pob_city}}</td>
</tr>

<tr>
<th>Email</th>
<td>{{$customer_details->email}}</td>
</tr>


<tr>
<th>Cell</th>
<td>{{$customer_details->cell}}</td>
</tr>

<tr>
<th>occupation</th>
<td>{{$customer_details->occupation}}</td>
</tr>

<tr>
<th>Source Of Income</th>
<td>{{$customer_details->soi}}</td>


</tr>

<tr>
<th>Address</th>
<td>{{$customer_details->address}}</td>
</tr>

<tr>
<th>Mailing Country</th>
<td>{{$customer_details->country1}}</td>
</tr>

<tr>
<th>Mailing City</th>
<td>{{$customer_details->city1}}</td>
</tr>

<tr>
<th>Zakat</th>
<td>{{$customer_details->zakat}}</td>
</tr>

@if($customer_details->zakat == 'no')
<tr>
<th>Zakat Certificate</th>
<td>
<button type="button" style="background: none; border:none;" value="{{$customer_details->zakat_certificate}}" class="pop">
<img width="150px" height="150px" src="{{url('uploads/zakat_certificates')}}/{{$customer_details->zakat_certificate}}">
</button>
</td>
</tr>	
@endif
</tbody>
</table>


<table class="table">
<tbody>

<br>
<h4 class="alert alert-success">Bank Details</h4>

<tr>
<th>Bank Name</th>
<td>{{$bank_details->bank_name}}</td>
</tr>

<tr>
<th>Branch Name</th>
<td>{{$bank_details->branch_name}}</td>
</tr>

<tr>
<th>Account Title</th>
<td>{{$bank_details->account_title}}</td>
</tr>

<tr>
<th>IBAN</th>
<td>{{$bank_details->iban_number}}</td>
</tr>


</tbody>
</table>

<table class="table">
<tbody>

<br>
<h4 class="alert alert-success">Investment Details</h4>

<tr>
<th>Fund Name</th>
<td>{{$investment_details->fund_name}}</td>
</tr>

<tr>
<th>Amount</th>
<td>{{$investment_details->amount}}</td>
</tr>

<tr>
<th>Payment Mode</th>
<td>{{$investment_details->payment_mode}}</td>
</tr>

<tr>
<th>Attachment</th>
<td>
<button type="button" style="background: none; border:none;" value="{{$investment_details->attachment}}" class="pop">
<img width="150px" height="150px" src="{{url('uploads/investment_detail_attachments')}}/{{$investment_details->attachment}}">
</button>
</td>
</tr>

<tr>
<th>Bank Name</th>
<td>{{$investment_details->bank_name}}</td>
</tr>


<tr>
<th>Instrument Number</th>
<td>{{$investment_details->instrument_number}}</td>
</tr>


</tbody>
</table>


<table class="table">
<tbody>

<br>
<h4 class="alert alert-success">Fatca Details</h4>

<tr>
<th>Multiple Nationalities</th>
<td>{{$fatca_details->multiple_nat}}</td>
</tr>

@if($fatca_details->multiple_nat == 'yes')
<tr>
<th>Nationilty </th>
<td>{{$fatca_details->nats}}</td>
</tr>
@endif

<tr>
<th>Green Card</th>
<td>{{$fatca_details->green_card}}</td>
</tr>

<tr>
<th>Foriegn Citizenship</th>
<td>{{$fatca_details->for_citi}}</td>
</tr>

<tr>
<th>Care Of Mailing Address</th>
<td>{{$fatca_details->co_ma}}</td>
</tr>


<tr>
<th>Care Of Mailing Phone Number</th>
<td>{{$fatca_details->co_mp}}</td>
</tr>

<tr>
<th>Power of Attorney</th>
<td>{{$fatca_details->attor}}</td>
</tr>


@if($fatca_details == 'yes')
<tr>
<th>Power of Attorney</th>
<td>{{$fatca_details->attor_addr}}</td>
</tr>
@endif


<tr>
<th>Transfer Funds </th>
<td>{{$fatca_details->trans_fund}}</td>
</tr>


<tr>
<th>w8/w9 form</th>
<td>{{$fatca_details->wf}}</td>
</tr>

@if($fatca_details->wf == 'yes')
<tr>
<th>form</th>

<td>
<a target="_blank" href="{{url('uploads/wforms')}}/{{$fatca_details->wform}}">view pdf</a>
</td>
</tr>
@endif

</tbody>
</table>


<br>
<table class="table">
<h4 class="alert alert-success">Action</h4>
<tbody>
<tr>
<th>Sales Agent</th>
<td><strong>{{$user_name}}</strong></td>
</tr>

<tr>
<th>Channel</th>
<td>{{$form_data->channel}}</td>
</tr>

<tr>
<th>Submitted At</th>
<td>{{$form_data->created_at}}</td>
</tr>


</tbody>
</table>

<!-- Modal -->




</div>

</div>

<div class="clearfix"> </div>


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

<script type="text/javascript">

$(".pop").on("click", function() {
var value = this.value;
var url = '<?php echo url('uploads/zakat_certificates/');?>';
$('#imagepreview').attr('src',url+'/'+value);
$('#imagemodal').modal('show');
});
</script>

@endsection
