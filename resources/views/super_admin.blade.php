@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
				<div class="row-one">
					<!-- <div class="col-md-4 widget">
						<div class="stats-left" style="background:rgb(227, 46, 33);">
							<h5>Total</h5>
							<h4>Pitches</h4>
						</div>
						<div class="stats-right">
							<label>
							<?php //echo DB::table('views')->sum('count');?>
							</label>

						</div>
						<div class="clearfix"> </div>	
					</div> -->
					<div class="col-md-4 widget states-mdl">
						<div class="stats-left" style="background:rgb(227, 46, 33);">
							<h5>Total</h5>
							<h4>Users</h4>
						</div>
						<div class="stats-right">
							<label> {{ App\User::count() }}</label>
						</div>
						<div class="clearfix"> </div>	
					</div>
				
					<div class="clearfix"> </div>	
				</div>
				<!-- <div class="charts">
				
					<div class="col-md-12 charts-grids ">
						<h4 class="title">Bar Chart Example</h4>
						<canvas id="bar" height="150" width="600"> </canvas>
					</div>
					
				
					<div class="clearfix"> </div>
							 <script>
								var barChartData = {
									labels : ["Jan","Feb","March","April","May","June","July"],
									datasets : [
										{
											fillColor : "rgba(233, 78, 2, 0.9)",
											strokeColor : "rgba(233, 78, 2, 0.9)",
											highlightFill: "#e94e02",
											highlightStroke: "#e94e02",
											data : [65,59,90,81,56,55,40]
										},
										{
											fillColor : "rgba(79, 82, 186, 0.9)",
											strokeColor : "rgba(79, 82, 186, 0.9)",
											highlightFill: "#4F52BA",
											highlightStroke: "#4F52BA",
											data : [40,70,55,20,45,70,60]
										}
									]
									
								};
								
								
								var DoughnutData = [
								{
								value :1,
								label:'admin',
								color : "rgba(242, 179, 63, 1)"
								},
								{
								value :2,
								label:'user',
								color : "rgba(88, 88, 88,1)"
								},
								];
								
							new Chart(document.getElementById("bar").getContext("2d")).Bar(barChartData);
							new Chart(document.getElementById("pie").getContext("2d")).Doughnut(DoughnutData);
							
							</script>
							
				</div>
				<br>
		
				<div class="clearfix"> </div> -->

				<?php 
		// 		$res = \DB::table('views')->where('update_at','=',date('Y-m-d'))->get();
		// 				foreach($res as $r){
		// 					echo $r->count;
		// 				}


		// 				$views = DB::table('views')
		// 				->select('update_at',DB::raw('SUM(count) as total'))
		// 				->groupBy('update_at')
		// 				->get();
		// 				foreach($views as $v){
		// 					echo $v->update_at.' ';
		// 					echo $v->total.'<br>';
		// 				}



		// 	$currentMonth = date('m');
		// 	$data = DB::table("views")
		// 	->select('update_at',DB::raw('SUM(count) as total'))
		// 	->groupBy('update_at')
        //    ->whereRaw('MONTH(update_at) = ?',[$currentMonth])
		// 	->get();
		// 	echo '<pre>';
		// 	print_r($data);
						?>
			</div>
		</div>
@endsection
