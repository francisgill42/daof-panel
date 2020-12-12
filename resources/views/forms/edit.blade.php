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



<form  method="post" action="{{url('send_back')}}/{{$form_id}} " enctype="multipart/form-data">

<table class="table">
<tbody>
<button type="submit" class="btn btn-info" style="float:right;margin:0px;">Send Back To Changes</button>
{{csrf_field()}}

<br>
<strong>Add Special Note</strong>
<br>
<textarea required name="msg" class="form-control" rows="4"></textarea>
<br>

<h4 class="alert alert-success">Customer Details</h4>
<tr>
<td>
<input type="hidden" name="user_id" value="{{$form_id}}">
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
<td>{{$customer_details->pob_country}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="pob_city">
</td>
<th>Place Of Birth (City)</th>
<td>{{$customer_details->pob_city}}</td>
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
<th>occupation</th>
<td>{{$customer_details->occupation}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="soi">
</td>
<th>Source Of Income</th>
<td>{{$customer_details->soi}}</td>


</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="address">
</td>
<th>Address</th>
<td>{{$customer_details->address}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="country1">
</td>
<th>Mailing Country</th>
<td>{{$customer_details->country1}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="city1">
</td>
<th>Mailing City</th>
<td>{{$customer_details->city1}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="cd[]" value="zakat">
</td>
<th>Zakat</th>
<td>{{$customer_details->zakat}}</td>
</tr>

@if($customer_details->zakat == 'no')
<tr>
<td>
<input type="checkbox" name="cd[]" value="zakat_certificate">
</td>
<th>Zakat Certificate</th>
<td>{{$customer_details->zakat_certificate}}</td>
</tr>	
@endif
</tbody>
</table>


<table class="table">
<tbody>

<br>
<h4 class="alert alert-success">Bank Details</h4>

<tr>
<td>
<input type="checkbox" name="bd[]" value="bank_name">
</td>
<th>Bank Name</th>
<td>{{$bank_details->bank_name}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="bd[]" value="branch_name">
</td>
<th>Branch Name</th>
<td>{{$bank_details->branch_name}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="bd[]" value="account_title">
</td>
<th>Account Title</th>
<td>{{$bank_details->account_title}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="bd[]" value="iban_number">
</td>
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
<td>
<input type="checkbox" name="ids[]" value="fund_name">
</td>
<th>Fund Name</th>
<td>{{$investment_details->fund_name}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="ids[]" value="amount">
</td>
<th>Amount</th>
<td>{{$investment_details->amount}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="ids[]" value="payment_mode">
</td>
<th>Payment Mode</th>
<td>{{$investment_details->payment_mode}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="ids[]" value="attachment">
</td>
<th>Attachment</th>
<td>{{$investment_details->attachment}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="ids[]" value="bank_name">
</td>
<th>Bank Name</th>
<td>{{$investment_details->bank_name}}</td>
</tr>


<tr>
<td>
<input type="checkbox" name="ids[]" value="instrument_number">
</td>
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
<td>
<input type="checkbox" name="fd[]" value="multiple_nat">
</td>
<th>Multiple Nationalities</th>
<td>{{$fatca_details->multiple_nat}}</td>
</tr>

@if($fatca_details->multiple_nat == 'yes')
<tr>
<td>
<input type="checkbox" name="fd[]" value="nats">
</td>
<th>Nationilty </th>
<td>{{$fatca_details->nats}}</td>
</tr>
@endif

<tr>
<td>
<input type="checkbox" name="fd[]" value="green_card">
</td>
<th>Green Card</th>
<td>{{$fatca_details->green_card}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="fd[]" value="for_citi">
</td>
<th>Foriegn Citizenship</th>
<td>{{$fatca_details->for_citi}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="fd[]" value="co_ma">
</td>
<th>Care Of Mailing Address</th>
<td>{{$fatca_details->co_ma}}</td>
</tr>


<tr>
<td>
<input type="checkbox" name="fd[]" value="co_mp">
</td>
<th>Care Of Mailing Phone Number</th>
<td>{{$fatca_details->co_mp}}</td>
</tr>

<tr>
<td>
<input type="checkbox" name="fd[]" value="attor">
</td>
<th>Power of Attorney</th>
<td>{{$fatca_details->attor}}</td>
</tr>


@if($fatca_details == 'yes')
<tr>
<td>
<input type="checkbox" name="fd[]" value="attor_addr">
</td>
<th>Power of Attorney</th>
<td>{{$fatca_details->attor_addr}}</td>
</tr>
@endif


<tr>
<td>
<input type="checkbox" name="fd[]" value="trans_fund">
</td>
<th>Transfer Funds </th>
<td>{{$fatca_details->trans_fund}}</td>
</tr>


<tr>
<td>
<input type="checkbox" name="fd[]" value="wf">
</td>
<th>w8/w9 form</th>
<td>{{$fatca_details->wf}}</td>
</tr>

@if($fatca_details->wf == 'yes')
<tr>
<td>
<input type="checkbox" name="fd[]" value="wform">
</td>
<th>form</th>
<td>{{$fatca_details->wform}}</td>
</tr>
@endif

</tbody>
</table>

</form>

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
<td>{{$form_id->channel}}</td>
</tr>

<tr>
<th>Submitted At</th>
<td>{{$form_id->created_at}}</td>
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

@endsection
