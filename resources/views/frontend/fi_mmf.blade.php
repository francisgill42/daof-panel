@include('frontend.header')
<div class="slides">
<!-- Mutual Funds and Plans Start-->

<section id="choose-fund" data-transition="slide">


<div class="col-lg-12 product">
<h6>Choose Product</h6>
<p class="product-heading-bg">Fixed Income /  <br/> Money Market Funds</p>

<div class="col-lg-12">
<a href="{{url('cf')}}"><img src="./images/cash-fund-pimg.jpg" alt=""></a>
<a href="{{url('immf')}}"><img src="./images/islamic-fund-pimg.jpg" alt=""></a>
<a href="{{url('iif')}}"><img src="./images/islamic-income-fund-pimg.jpg" alt=""></a>

</div>
<div class="col-lg-12">
<a href="{{url('mmf')}}"><img src="./images/money-market-fund-pimg.jpg" alt=""></a>
<a href="{{url('if')}}"><img src="./images/hbl-income-fund-pimg.jpg" alt=""></a>
<a href="{{url('gsf')}}"><img src="./images/gov-security-fund-pimg.jpg" alt="">	</a>				
</div>
<p class="product-heading-bg"><a style="color:#fff;" href="products">All Products</a></p>
</div>

</section>


</div>

</div>
@include('frontend.footer')