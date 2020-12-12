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
	<form method="post" action="{{url('user_delete')}}">
		{{csrf_field()}}
	<table class="table">
	
	<tbody>
	<?php foreach($users as $u){ ?>
	<tr>
	<th>Name</th>
	<td>
	<input name="id" type="hidden" class="form-control" value="<?php echo $u->id; ?>">
	<input name="name" class="form-control" value="<?php echo $u->name; ?>">
	</td>
	</tr>
	<tr>
	<th>Email</th>
	<td>
	<input name="email" class="form-control" value="<?php echo $u->email; ?>">
	</td>
	</tr>
	<tr>
	<th>Password</th>
	<td>
	<input name="password" type="password" class="form-control">
	</td>
	</tr>
	<tr>
	<th>Role</th>
	<td>
	<select name="role_id" class="form-control">
	<?php 	
	$role_name = DB::table('roles')->get();	
	foreach($role_name as $role){?>
	<option value="<?php echo $role->id;?>"><?php echo $role->role;?></option>
	<?php } ?>
	</select>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<div class="col-md-2 col-md-offset-4 update_btn" style="margin-left: 30.333333%;">
	<input style="background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit" value="Delete">
	</div>
	</form>
	</div>

	</div>

	<div class="clearfix"> </div>


	</div>
	</div>

	@endsection
