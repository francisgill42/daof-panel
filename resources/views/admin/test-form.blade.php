@extends('layouts.admin-app')

@section('content')
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
		
				<div class="forms">
					
					<div class=" form-grids row form-grids-right">
						<div class="widget-shadow " data-example-id="basic-forms"> 
							<div class="form-title">
								<h4>Enter Fund Name Here:</h4>
							</div>
							<div class="form-body">
								<form method="POST" action="{{url('test')}}" class="form-horizontal">
								{{csrf_field()}}								
								<div class="form-group">
								<div class="col-md-6">
								<input id="name" type="text" name="title" value="" required="required" autofocus="autofocus" class="form-control">
                                <br><button type="submit" class="btn btn-primary">Add Fund</button>

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
