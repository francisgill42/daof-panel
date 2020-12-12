@extends('layouts.cbc-app')

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
<div class="alert alert-info" role="alert">
<strong>Well done!</strong> {{session('msg')}}
</div>
@endif

@if (session()->has('err'))
<div class="alert alert-danger" role="alert">
{{session('err')}}
</div>
@endif

<br>
<div class="form-title" style="background:rgb(227, 46, 33);">
<h4 style="color:white;">Form Id # {{$form_id}}
<button type="button" class="btn btn-danger" style="display:;float:right;margin-top:-5px;" data-toggle="modal" data-target="#myModal">Discussion</button>

</h4>
</div>


<div class="tables">
<div class="panel-body widget-shadow">

<form  method="post" action="{{url('cbc_done')}}/{{$form_id}} " enctype="multipart/form-data">

<table class="table">
<tbody>
<button type="submit" class="btn btn-danger" style="float:right;margin:0px;">Submit</button>
{{csrf_field()}}

<br>
<strong>Add Special Note</strong>
<br>
<textarea required name="msg" class="form-control" rows="4"></textarea>
<br>
<h4 class="alert alert-danger">Customer Details</h4>
<tr>

<th>Name</th>
<td>
<input type="text"class="form-control" name="name" value="{{$customer_details->name}}">
</td>
</tr>

<tr>
<th>Father Name</th>
<td>
<input type="text"class="form-control" name="father_name" value="{{$customer_details->father_name}}">
</td>
</tr>

<tr>
<th>Mother Name</th>
<td>
<input type="text"class="form-control" name="mother_name" value="{{$customer_details->mother_name}}">
</td>
</tr>

<tr>
<th>Date Of Birth</th>
<td>
<input type="text"class="form-control" name="dob" value="{{$customer_details->dob}}">
</td>
</tr>

<tr>
<th>CNIC</th>
<td>
<input type="text"class="form-control" name="cnic" value="{{$customer_details->cnic}}">
</td>
</tr>

@if($customer_details->cnic_attachment != 'no_image.png' && !is_null($customer_details->cnic_attachment))
<tr>
<th>CNIC Attachment</th>
<td>

<input type="file" name="cnic_attachment">
<input type="hidden" name="old_file_cnic" value="{{$customer_details->cnic_attachment}}">

<br>
<button type="button" style="background: none; border:none;" value="{{$customer_details->cnic_attachment}}" class="pop_cnic">
<img width="150px" height="150px" src="{{url('uploads/cnic_attachment')}}/{{$customer_details->cnic_attachment}}">
</button>
</td>

</td>
</tr>
@endif


<tr>
<th>CNIC Issue Date</th>
<td>
<input type="text"class="form-control" name="cnic_issue_date" value="{{$customer_details->cnic_issue_date}}">
</td>
</tr>

<tr>
<th>Place Of Birth (Country)</th>
<td>
<input type="text"class="form-control" name="pob_country[]" value="{{explode('|',$customer_details->pob_country)[1]}}">
<input type="hidden" type="text"class="form-control" name="pob_country[]" value="{{$customer_details->pob_country}}">
</td>
</tr>

<tr>
<th>Place Of Birth (City)</th>
<td>
<input type="text"class="form-control" name="pob_city[]" value="{{explode('|',$customer_details->pob_city)[1]}}">
<input type="hidden" type="text"class="form-control" name="pob_city[]" value="{{$customer_details->pob_city}}">
</td>
</tr>

<tr>
<th>Email</th>
<td>
<input type="text"class="form-control" name="email" value="{{$customer_details->email}}">
	</td>
	</tr>


	<tr>
	<th>Cell</th>
	<td>
	<input type="text"class="form-control" name="cell" value="{{$customer_details->cell}}">
	</td>
	</tr>

	<tr>
	<th>Occupation</th>
	<td>
	<input type="text"class="form-control" name="occupation" value="{{$customer_details->occupation}}">
	</td>
	</tr>

<tr>
<th>Education</th>
<td>
	<input type="text"class="form-control" name="education" value="{{$customer_details->education}}">
</td>
</tr>


@if($customer_details->soi_attachment != 'no_image.png' && !is_null($customer_details->soi_attachment))
<tr>
<th>Source of Income Attachment</th>
<td>

<input type="file" name="soi_attachment">
<input type="hidden" name="old_file_soi" value="{{$customer_details->soi_attachment}}">

<br>
<button type="button" style="background: none; border:none;" value="{{$customer_details->soi_attachment}}" class="pop_soi">
<img width="150px" height="150px" src="{{url('uploads/soi_attachment')}}/{{$customer_details->soi_attachment}}">
</button>
</td>

</td>
</tr>
@endif

@if($customer_details->zakat_certificate != 'no_image.png' && !is_null($customer_details->zakat_certificate))
<tr>
<th>Zakat Certificate</th>
<td>

<input type="file" name="zakat_certificate">
<input type="hidden" name="old_file" value="{{$customer_details->zakat_certificate}}">

<br>
<button type="button" style="background: none; border:none;" value="{{$customer_details->zakat_certificate}}" class="pop">
<img width="150px" height="150px" src="{{url('uploads/zakat_certificates')}}/{{$customer_details->zakat_certificate}}">
</button>
</td>

</td>
</tr>

@endif

</tbody>
</table>
</form>



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
$user = \DB::table('users')->where('id',$msg->sender_id)->first();
@endphp

@if($msg->receiver_id == \Auth::user()->id)
<li class="left clearfix" style="list-style-type: none;"><span class="chat-img pull-left">
<img src="http://placehold.it/50/55C1E7/fff&text={{($user->role_id == 3) ? 'RA' : 'R'}}" alt="User Avatar" class="img-circle" />
</span>
<div class="chat-body clearfix">
<div class="header">
<strong style="margin-left:7px;">{{$user->name}}</strong>
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
$role = \DB::table('users')->where('id',$msg->sender_id)->first()->role_id;
@endphp
@if($role == 5)

<li class="right clearfix" style="list-style-type: none;"><span class="chat-img pull-right">
<img src="http://placehold.it/50/FA6F57/fff&text=CBC" alt="User Avatar" class="img-circle" />
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

$(".pop_soi").on("click", function() {
var value = this.value;
var url = '<?php echo url('uploads/soi_attachment/');?>';
$('#imagepreview').attr('src',url+'/'+value);
$('#imagemodal').modal('show');
});

$(".pop_cnic").on("click", function() {
var value = this.value;
var url = '<?php echo url('uploads/cnic_attachment/');?>';
$('#imagepreview').attr('src',url+'/'+value);
$('#imagemodal').modal('show');
});
</script>

@endsection

