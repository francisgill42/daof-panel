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
	<form method="post" action="{{url('update')}}">
		{{csrf_field()}}
	<table class="table">
	
	<tbody>
	<?php foreach($user as $u){ ?>
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
	<input name="old_password" value="<?php echo $u->password; ?>"  type="hidden" class="form-control">
	<input name="password" value="" placeholder="leave empty if no change" type="password" class="form-control">
	</td>
	</tr>
	<tr>
	<th>Role</th>
	<td>
	<select name="role_id" class="form-control">
	<?php 	
	$role_name = DB::table('roles')->get();	
	foreach($role_name as $role){?>
	<option value="<?php echo $role->id;?>"><?php echo $role->role_title;?></option>
	<?php } ?>
	</select>
	</td>
	</tr>
	<?php } ?>
	<tr>
	<td></td>
	<td>
	<div class="col-md-3  update_btn">
	<input style="background-color: #00a997;border: none;" class="form-control btn btn-danger"  type="submit">
	</div>
	</td>
	</tr>
	</tbody>
	</table>
	
	</form>
	</div>

	</div>

	<div class="clearfix"> </div>


	</div>
	</div>

	@endsection
