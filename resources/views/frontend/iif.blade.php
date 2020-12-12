@include('frontend.header')
<div class="slides">

<section data-transition="slide">
<h3 class="main-heading" data-animate="fadeInRight">
@foreach($fund_title as $fd)
{{$fd->title}}
@endforeach
</h3>

			<div class="col-lg-12 cash-fund ">
					<h5 data-animate="fadeInUp">Investment Objective</h5>
					<p data-animate="fadeInRight">To provide competitive risk adjusted returns to its investors by investing in a diversified portfolio of long, medium and short term Shariah compliant debt instruments while taking into account liquidity considerations.

</p>
					<h6 data-animate="fadeInRight">Risk Category</h6>
					<img data-animate="fadeInRight" src="./images/cash-fund-color-img3.jpg" alt="">
					<h6 data-animate="fadeInRight">Investment Horizon</h6>
					<button data-animate="fadeInRight">Medium / Long Term</button>
					</div>

</section>

<section data-transition="slide">
<h3 class="main-heading" data-animate="fadeInRight">
@foreach($fund_title as $fd)
{{$fd->title}}
@endforeach
</h3>

<div class="col-lg-8 cash-fund" style="margin:0 auto;">
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


<div class="col-lg-6 info" data-animate="fadeInRight">
<h6>FUND INFORMATION </h6>

<ul class="col-lg-12">
@foreach($fi as $fi)
<li><span>{{$fi->fi_k_1}}</span><em>{{$fi->fi_v_1}}</em></li>
<li><span>{{$fi->fi_k_2}}</span><em>{{$fi->fi_v_2}}</em></li>
<li class="color-bg"><span>{{$fi->fi_k_3}}</span><em>{{$fi->fi_v_3}}</em></li>
<li><span>{{$fi->fi_k_4}}</span><em>{{$fi->fi_v_4}}</em></li>
<li class="color-bg"><span>{{$fi->fi_k_5}}</span><em>{{$fi->fi_v_5}}</em></li>
@endforeach
</ul>
</div>

<div class="col-lg-6 info" data-animate="fadeInRight" style="border-left:20px solid #fff;">
<h6>ASSET ALLOCATION (% of Total Assets) </h6>
<ul class="col-lg-12">
@foreach($aa as $aa)
<li><span>{{$aa->aa_keys}}</span><em>{{$aa->aa_values}}</em></li>
@endforeach
</ul>
</div>

</div>

</section>



<section id="ext" data-transition="slide" data-state="somestate">
<h3 class="main-heading" data-animate="fadeInRight">
Returns - 
@foreach($fund_title as $fd)
{{$fd->title}}
@endforeach
</h3>

<div class="col-lg-8 mmf" style="margin:0 auto;">
<div class="col-sm-8" style="float:left; height:100%;">
<h5 >
@foreach($fund_title as $fd)
{{$fd->title}}
@endforeach
</h5>
<div class="col-sm-12 chart-detail">
<div>
    @include('frontend.charts.chart')
</div>
 
<p data-animate="fadeInRight" style="text-align:left; font-size:16px; color:#000; padding:0; margin:0">
@foreach($exp as $shd)
{{$shd->shd}}
@endforeach
</p>
<table class="cf-table" data-animate="fadeInUp">
<tr>
<td width="16%" style="font-size:25px;">&nbsp;</td>
@foreach($charts as $chart)
<td width="28%" style="font-size:25px;">{{$chart->year}}</td>
@endforeach
</tr>
<tr>
<td style="text-align:left; font-size:25px;" >Value</td>
@foreach($charts as $chart)
<td  style="font-size:25px;">{{$chart->val}}</td>
@endforeach
</tr>
<tr>
<td style="text-align:left; font-size:25px;" >Return %</td>
@foreach($charts as $chart)
<td  style="font-size:25px;">{{$chart->ret}}</td>
@endforeach
</tr>

</table>


@foreach($exp as $exp)

<p data-animate="fadeInRight" style="text-align:left; font-size:16px; padding:5px 0 0 0; color:#01a896;">
<strong>{{$exp->title}}</strong></p>
<p data-animate="fadeInRight" style="text-align:left; font-size:21px; color:#000; line-height:1; padding:0;">
{{$exp->desc}}
</p>
@endforeach			
</div>	

</div>

</div>

<div class="col-sm-4 mmf" style="float:left; padding:1% 3%  0 0%;">
<p>&nbsp;</p>
<div class="col-lg-12 info5" data-animate="fadeInRight">
<h6 style="text-align:center;font-size:28px;">Investment Avenues</h6>
<ul class="col-lg-12">
@foreach($ia as $aa)
<li style="font-size:27px;" class="color-bg"><span>{{$aa->ai_key}}</span><em><img src="./images/tick-icon.png" alt=""></em></li>
@endforeach
</ul>

</div>
<div class="col-lg-12 info3" data-animate="fadeInRight">
<h6 style="text-align:center;font-size:28px;">Funds Return (%)</h6>
<ul class="col-lg-12">
@foreach($fr as $fr)
<li style="font-size:25px;"><span>{{$fr->k1}}</span><em>{{$fr->k2}}</em><b>{{$fr->k3}}</b></li>
<li style="font-size:27px;"><span>{{$fr->k1v1}}</span><em>{{$fr->k2v1}}</em><b>{{$fr->k3v1}}</b></li>
<li style="font-size:27px;"><span>{{$fr->k1v2}}</span><em>{{$fr->k2v2}}</em><b>{{$fr->k3v2}}</b></li>
<li style="font-size:27px;"><span>{{$fr->k1v3}}</span><em>{{$fr->k2v3}}</em><b>{{$fr->k3v3}}</b></li>
<li style="font-size:27px;"><span>{{$fr->k1v4}}</span><em>{{$fr->k2v4}}</em><b>{{$fr->k3v4}}</b></li>
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

