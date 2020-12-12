@include('frontend.header')
@include('frontend.charts.chart2')

<div class="slides">
@foreach($fund_title as $fd)
@endforeach

@foreach($exp as $exp)
@endforeach	

<section data-transition="slide">
<h3 class="main-heading" data-animate="fadeInRight">{{$fd->title}}</h3>

<div class="col-lg-12 cash-fund info">
					<h5 data-animate="fadeInUp" >Investment Objective</h5>
					<p data-animate="fadeInRight">The objective of HBL Pension Fund is to provide a secure source of savings and regular income after retirement to the participants.</p>
					<h6 data-animate="fadeInRight">Risk Category</h6>
					<img data-animate="fadeInRight" src="./images/cash-fund-color-img6.jpg" alt="">
					</div>

<div class="col-lg-8 fab" style="margin:0 auto;">
<div class="col-lg-12 benefits" data-animate="fadeInRight">
<h4>Features and Benefits</h4>
<ul class="col-lg-5">
<?php 
    foreach($fabs as $fab) {
        if($fab->fb_bullets_rt == ''){
            
          echo '<li style="display:none;">'.$fab->fb_bullets_rt.'</li>';

        }  
        else{ 
        echo '<li>'.$fab->fb_bullets_rt.'</li>'; 
          } 
}
    ?>
</ul>
<ul class="col-lg-5">
    <?php 
    foreach($fabs as $fab) {
        if($fab->fb_bullets_lt == ''){
            
          echo '<li style="display:none;">'.$fab->fb_bullets_lt.'</li>';

        }  
        else{
        echo '<li>'.$fab->fb_bullets_lt.'</li>'; 
        } 
}
    ?>
</ul>
</div>

</section>

<section data-transition="slide">
<h3 class="main-heading" data-animate="fadeInRight">{{$fd->title}}</h3>

<div class="col-lg-8 fpf" style="margin:40px auto 0;">


<div class="col-lg-12 info" data-animate="fadeInRight">
<ul class="col-lg-12">
<li style="background:#01a896; color:#fff;"><span>FUND INFORMATION</span><em>MMSF</em><b>DSF</b><i>ESF</i></li>
@foreach($fi1 as $fi)
<li><span>{{$fi->fi_k1}}</span><em>{{$fi->fi_k1v1}}</em><b>{{$fi->fi_k1v2}}</b><i>{{$fi->fi_k1v3}}</i></li>
<li class="color-bg"><span>{{$fi->fi_k2}}</span><em>&nbsp;</em><b>{{$fi->fi_k2v2}}</b><i>&nbsp;</i></li>
<li><span>{{$fi->fi_k3}}</span><em>&nbsp;</em><b>{{$fi->fi_k3v2}}</b><i>&nbsp;</i></li>
<li class="color-bg"><span>{{$fi->fi_k4}}</span><em>{{$fi->fi_k4v1}}</em><b>{{$fi->fi_k4v2}}</b><i>{{$fi->fi_k4v3}}</i></li>
<li><span>{{$fi->fi_k5}}</span><em>{{$fi->fi_k5v1}}</em><b>{{$fi->fi_k5v2}}</b><i>{{$fi->fi_k5v3}}</i></li>
@endforeach
</ul>

</div>

<div class="col-lg-12 info" data-animate="fadeInRight">
<h6 style="text-align:center;">ASSET ALLOCATION (% of Total Assets)</h6>
<ul class="col-lg-12">
<li style="background:#53b7ac; color:#000; font-weight:bold; font-size: 16px; text-transform: none;"><span>&nbsp;</span><em>Money Market Sub Fund (MMSF)</em><b style="font-weight: bold;">Debt Sub Fund (DSF)</b><i>Equity Sub Fund (ESF)</i></li>
@foreach($aa1 as $aa)
<li class="color-bg">
<span class="color-bg">
{{$aa->aa1_key}}
</span>
<em>{{$aa->aa1_v1}}</em>
<b>{{$aa->aa1_v2}}</b>
<i>{{$aa->aa1_v3}}</i>
</li>
@endforeach

</ul>

</div>
</div>

</section>

<section class="fund-charts" data-state="somestate1" style="height: 70%">
<h3 class="main-heading" data-animate="fadeInRight">Returns - {{$fd->title}}</h3>
<h5>Money Market Sub Fund</h5>
<div class="col-sm-6">
<div  id="graphs" >
<canvas id="canvas1"></canvas>
</div>


</div>
   
<p data-animate="fadeInRight" style="text-align:right; font-size:16px; color:#000; padding:0; margin:0 10%">{{$exp->shd}}</p>
<table class="cf-table2" data-animate="fadeInUp">

<tr>
<td>Year/Days</td>
@foreach($charts as $chart)
@if($chart->plan =='mmsf')  
<td width="28%">{{$chart->year}}</td>
@endif
@endforeach

</tr>


<tr>
<td style="text-align:left;">Value</td>
@foreach($charts as $chart)
@if($chart->plan =='mmsf')  
<td>{{$chart->val}}</td>
@endif
@endforeach
</tr>

<tr>
<td style="text-align:left;">Return %</td>
@foreach($charts as $chart)
@if($chart->plan =='mmsf')  
<td>{{$chart->ret}}</td>
@endif
@endforeach
</tr>


</table>

</section>


<section class="fund-charts"  data-state="somestate2" style="height: 70%">
<h3 class="main-heading" data-animate="fadeInRight">Returns - {{$fd->title}}</h3>
<h5>Debt Sub Fund</h5>
<div class="col-sm-6">
<div  id="graphs" >
<canvas id="canvas2"></canvas>
</div>


</div>
<div>
</div>
<p data-animate="fadeInRight" style="text-align:right; font-size:16px; color:#000; padding:0; margin:0 10%">{{$exp->shd}}</p>
<table class="cf-table2" data-animate="fadeInUp">
<tr>
<td>Year/Days</td>
@foreach($charts as $chart)
@if($chart->plan =='dsf')  
<td width="28%">{{$chart->year}}</td>
@endif
@endforeach

</tr>


<tr>
<td style="text-align:left;">Value</td>
@foreach($charts as $chart)
@if($chart->plan =='dsf')  
<td>{{$chart->val}}</td>
@endif
@endforeach
</tr>

<tr>
<td style="text-align:left;">Return %</td>
@foreach($charts as $chart)
@if($chart->plan =='dsf')  
<td>{{$chart->ret}}</td>
@endif
@endforeach
</tr>
</table>
</section>



<section id="ext" class="fund-charts" data-state="somestate3" style="height: 70%">
<h3 class="main-heading" data-animate="fadeInRight">Returns - {{$fd->title}}</h3>
<div class="col-sm-6">
<div  id="graphs" >
<canvas id="canvas3"></canvas>
</div>


</div>
<div class="col-lg-8 mmf" style="margin:0 auto;">
<div class="col-sm-8" style="float:left; height:100%;">
<h5 style="    margin: 96px 0 0 -220px;">Equity Sub Fund</h5>
<div class="col-sm-12 chart-detail" style="float:left;     margin-top: 54% !important;">
<p data-animate="fadeInRight" style="text-align:left; font-size:16px; color:#000; padding:0; margin:0">{{$exp->shd}}</p>
<table class="cf-table" data-animate="fadeInUp">
<tr>
<td>Year/Days</td>
@foreach($charts as $chart)
@if($chart->plan =='esf')  
<td width="28%">{{$chart->year}}</td>
@endif
@endforeach

</tr>


<tr>
<td style="text-align:left;">Value</td>
@foreach($charts as $chart)
@if($chart->plan =='esf')  
<td>{{$chart->val}}</td>
@endif
@endforeach
</tr>

<tr>
<td style="text-align:left;">Return %</td>
@foreach($charts as $chart)
@if($chart->plan =='esf')  
<td>{{$chart->ret}}</td>
@endif
@endforeach
</tr>
</table>

<p data-animate="fadeInRight" style="text-align:left; font-size:16px; padding:5px 0 0 0; color:#01a896;"><strong>
{{$exp->title}}</strong></p>
<p data-animate="fadeInRight" >
{{$exp->desc}}
</p>


</div>	

</div>

</div>

<div class="col-sm-4 mmf" style="float:left; padding:1% 3%  0 0%;">
<p>&nbsp;</p>
<div class="col-lg-12 info5" data-animate="fadeInRight">
<h6>Investment Avenues</h6>
<ul class="col-lg-12">
@foreach($ia as $aa)
<li style="font-size:27px;" class="color-bg"><span>{{$aa->ai_key}}</span><em><img src="./images/tick-icon.png" alt=""></em></li>
@endforeach
</ul>
</div>
<div class="col-lg-12 info4" data-animate="fadeInRight">
<h6 style="text-align:center;">Funds Return (%)</h6>
<ul class="col-lg-12">
							<li><span>Tenure</span><em>MMSF</em><b>DSF</b><i>ESF</i></li>
                            @foreach($fr1 as $fr)
							<li><span>{{$fr->fr1_v1}}</span><em>{{$fr->fr1_v2}}</em><b>{{$fr->fr1_v3}}</b><i>{{$fr->fr1_v4}}</i></li>
							@endforeach
                            </ul>

</div>
<p data-animate="fadeInRight" style="text-align:left; font-size:16px; color:#000; padding:0; margin:0">*CYTD = Year to Date</p>
</div>
</section>					



</div>
<a class="home-icon" href="{{url('/')}}/products"><img src="images/home-icon.png"></a>
</div>




@include('frontend.footer')