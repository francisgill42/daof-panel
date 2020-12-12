@extends('layouts.admin-app')

@section('content')
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">

@if(count($errors) > 0)

@foreach($errors->all() as $error)
<div class="alert alert-danger" role="alert">
{{$error}}
</div>
@endforeach	
@endif
		
				<div class="forms">
					
					<div class=" form-grids row form-grids-right">
						<div class="widget-shadow " data-example-id="basic-forms"> 
							<div class="form-title">
								<h4>Add User:</h4>
							</div>
							<div class="form-body">
								<form method="POST" action="add-user" class="form-horizontal">
								{{csrf_field()}}								
								<div class="form-group">
								<label for="name" class="col-md-4 control-label">Name</label>
								<div class="col-md-6">
								<input id="name" type="text" name="name" value="" required="required" autofocus="autofocus" class="form-control">
								</div>
								</div> 								
								<div class="form-group">
								<label for="email" class="col-md-4 control-label">E-Mail Address</label> 
								<div class="col-md-6"><input id="email" type="email" name="email" value="" required="required" class="form-control">
								</div></div> <div class="form-group"><label for="password" class="col-md-4 control-label">Password</label> 
								<div class="col-md-6"><input id="password" type="password" name="password" required="required" class="form-control">
								</div>
								</div> 
								<div class="form-group">
								<label for="password-confirm" class="col-md-4 control-label">Role</label> 
								<div class="col-md-6">
								<select id="role" name="role" class="form-control">
								<?php foreach($roles as $role){?>
								<option value="<?php echo $role->id;?>"><?php echo $role->role_title;?></option>
								<?php }?>
								</select>
								</div>								
								</div> 
								<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Add User</button>
								</div>								
								</div>
								</form>
								</div>
						</div>
					</div>
					</div>
				<div class="clearfix"> </div>
				
				
			</div>
		</div>

@endsection
