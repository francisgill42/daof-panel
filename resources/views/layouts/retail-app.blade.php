<!DOCTYPE HTML>
<html>
<head>
<title>Retail</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Novus Admin Panel Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="{{url('css/bootstrap.css')}}" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="{{url('css/style.css')}}" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="{{url('css/font-awesome.css')}}" rel="stylesheet">
<!-- //font-awesome icons -->
<!-- js-->
<script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
<script src="{{url('js/modernizr.custom.js')}}"></script>
<!--webfonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts-->
<!--animate-->
<link href="{{url('css/animate.css')}}" rel="stylesheet" type="text/css" media="all">
<script src="{{url('js/wow.min.js')}}"></script>


<script>
new WOW().init();
</script>
<!--//end-animate-->
<!-- Metis Menu -->
<script src="{{url('js/custom.js')}}"></script>
<link href="{{url('css/custom.css')}}" rel="stylesheet">


<!-- Metis Menu -->
<script src="{{asset('js/metisMenu.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<link href="{{asset('css/custom.css')}}" rel="stylesheet">
</head>
<body class="cbp-spmenu-push">
<div class="main-content">
<!--left-fixed -navigation-->
<div class=" sidebar" role="navigation">
<div class="navbar-collapse">
<nav style="background:orange" class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">

<ul class="nav" id="side-menu">

<li>
<a style="color:white;" href="{{url('/')}}"><i class="fa fa-home nav_icon"></i>Home</a>
</li>

</ul>
<!-- //sidebar-collapse -->
</nav>
</div>
</div>

<div id="test"></div>
<!--left-fixed -navigation-->
<!-- header-starts -->
<div class="sticky-header header-section ">
<div class="header-left">
<!--toggle button start-->
<button id="showLeftPush"><i class="fa fa-bars"></i></button>
<!--toggle button end-->
<!--logo -->
<!-- <img style="height: 65px; width: 192px; margin-top: 3px;" src="{{url('images/logo.PNG')}}" alt=""> -->
<!--//logo-->

<div class="clearfix"> </div>
</div>
<div class="header-right">

<div class="profile_details">
<ul>
<li class="dropdown profile_details_drop">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
<div class="profile_img">
<span class="prfil-img"><img style="display:none;" src="" alt=""> </span>
<div class="user-name">
<p style="color:rgb(227, 46, 33);">  {{ Auth::user()->email }}</p>
<span></span>
</div>
<i class="fa fa-angle-down lnr"></i>
<i class="fa fa-angle-up lnr"></i>
<div class="clearfix"></div>
</div>
</a>
<ul class="dropdown-menu drp-mnu">
<li> <a href="{{ route('logout') }}"
onclick="event.preventDefault();
document.getElementById('logout-form').submit();">
<i class="fa fa-sign-out"></i>Logout</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
{{ csrf_field() }}
</form>
</li>
</ul>
</li>
</ul>
</div>
<div class="clearfix"> </div>
</div>
<div class="clearfix"> </div>
</div>
<!-- //header-ends -->
@yield('content')

<!--footer-->
<div class="footer">
<p>&copy; <?php echo date('Y');?>All Rights Reserved 
<!-- | Design & Developed by <a href="{{url('http://orangeroomdigital.com/')}}" target="_blank">Orange Room Digital</a>-->
</p>
</div>
<!--//footer-->
</div>
<!-- Classie -->
<script src="{{url('js/classie.js')}}"></script>
<script>
var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
showLeftPush = document.getElementById( 'showLeftPush' ),
body = document.body;

showLeftPush.onclick = function() {
classie.toggle( this, 'active' );
classie.toggle( body, 'cbp-spmenu-push-toright' );
classie.toggle( menuLeft, 'cbp-spmenu-open' );
disableOther( 'showLeftPush' );
};


function disableOther( button ) {
if( button !== 'showLeftPush' ) {
classie.toggle( showLeftPush, 'disabled' );
}
}
</script>
<!--scrolling js-->
<script src="{{url('js/jquery.nicescroll.js')}}"></script>
<!--//scrolling js-->
<!-- Bootstrap Core JavaScript -->
<script src="{{url('js/bootstrap.js')}}"> </script>

<!-- <script
src="https://code.jquery.com/jquery-3.3.1.min.js"
integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous"></script> -->


</body>
</html>
