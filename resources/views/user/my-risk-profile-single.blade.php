@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">

<br>
<br>

<div class="tables">
<div class="panel-body widget-shadow">
<h4>Risk Profiles</h4>
<table class="table">

<tbody>
<?php foreach($data as $user){ ?>


<tr>
<th>Client ID</th>
<td><?php echo $user->id; ?></td>
</tr>
<tr>
<th>Client Name</th>
<td><?php echo $user->client_name;?></td>
</tr>
<tr>
<th>Client Email</th>
<td><?php echo $user->client_email;?></td>
</tr>
<tr>
<th>Client Number</th>
<td><?php echo $user->client_number;?></td>
</tr>
<tr>
<th>Client CNIC</th>
<td><?php echo $user->client_cnic;?></td>
</tr>
<tr>
<th>Location</th>
<td><?php echo $user->location;?></td>
</tr>
<tr>
<th>Choosen Fund</th>
<td><?php echo $user->choosen_fund;?></td>
</tr>

<tr>
<th>Recommended Islamic Fund</th>
<td><?php echo $user->irf;?></td>
</tr>

<tr>
<th>Recommended Conventional Fund</th>
<td><?php echo $user->crf;?></td>
</tr>


<tr>
<th>Seller</th>
<td>
<?php 	
$username = DB::table('users')->where('id', '=', $user->user_id )->get();	
foreach($username as $un){
echo $un->name;
}?>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</div>

<div class="clearfix"> </div>


</div>
</div>

@endsection
