<footer>
<div class="col-sm-4 weblink">
<img src="./images/glob-icon.png" alt="">
<p><a href="http://hblasset.com/" target="_blank">www.hblasset.com</a></p>

<p><a class="" href="{{url('/')}}">DashBoard</a></p>
<p><a class="" href="{{url('welcome')}}">Home</a></p>




</div>
<div class="col-sm-4 copyright"><p>©<?php echo date('Y');?> HBL Asset Management.</p></div>
<div class="col-sm-4 social">
<a href="#"><img src="./images/linkedin-icon.png" alt=""></a>
<a href="#"><img src="./images/facebook-icon.png" alt=""></a>
<a href="#"><img src="./images/twitter-icon.png" alt=""></a>
<p> Follow Us On :</p>
<p>	 <a href="{{ route('logout') }}"
onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <i class="fa fa-sign-out"></i>Logout &nbsp;</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
{{ csrf_field() }}
</form>
</p>
</div>
</footer>
<script src="lib/js/head.min.js"></script>
<script src="js/reveal.js"></script>
<script src="js/fe_wow.min.js"></script>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>

//$('input[type="text"],input[type="radio"]').prop('required',true);
$('#submitButton').on('touchstart click', function(){
submitQuiz(); 
});


$('#btn').on('touchstart click', function(){
alert()
});


function submitQuiz() {
console.log('submitted');

// get each answer score

function answerScore (qName) {
var radiosNo = document.getElementsByName(qName);

for (var i = 0, length = radiosNo.length; i < length; i++) {
if (radiosNo[i].checked) {
// do something with radiosNo
var answerValue = Number(radiosNo[i].value);
}
}
// change NaNs to zero
if (isNaN(answerValue)) {
answerValue = 0;
}
return answerValue;
}

var calcScore = (answerScore('q1') + answerScore('q2') + answerScore('q3') + answerScore('q4') + answerScore('q5') + answerScore('q6')
+ answerScore('q7') + answerScore('q8'));


var r = document.getElementsByName('q9');
var v = '';
var p1 = p2 = p3 = p4 = p5 = p6 = '';
var i1 = i2 = i3 = '';
var c1 = c2 = c3 = '';
var r1 = r2 = '';
var f1 = f2 = '';



for (var i = 0, length = r.length; i < length; i++) {
if (r[i].checked) {
v = r[i].value;
console.log(v);
}
}
//Test
//calcScore = -12;


if (calcScore >= -20 && calcScore <= -12) {
c1  = 'Money Market Fund';
c2 = 'Cash Fund';
}
else if(calcScore >= -11 && calcScore <= -6)
{
c1 = "Income Fund";
c2 = "Government Securities Fund";
}
else if(calcScore >= -5 && calcScore <= 0)
{
c1 = "FPF Conservative Plan";
}
else if(calcScore >= 1 && calcScore <= 6)
{
c1 = "Multi Asset Fund";
}
else if(calcScore >= 7 && calcScore <= 12)
{
c1 = "FPF Multi Allocation Plan";
}
else if(calcScore >= 13 && calcScore <= 20)
{
c1 = "Stock Market Fund";
c2 = "Energy Fund";
}
else{
c = "-";
}
if (calcScore >= -20 && calcScore <= -12) {
i1 = "Islamic Money Market Fund";
}
else if(calcScore >= -11 && calcScore <= -6)
{
i1 = "Islamic Income Fund";
}
else if(calcScore >= -5 && calcScore <= 6)
{
i1 = "Islamic Asset Allocation Fund";
i2 = "IFPF Conservative Plan";
}
else if(calcScore >= 7 && calcScore <= 12)
{
i1 = "IFPF Active Allocation Plan";
}
else if(calcScore >= 13 && calcScore <= 20)
{
i1 = "Islamic Stock Fund";
i2 = "Equity Fund";
i3 = "Islamic Equity Fund";
}
else{
i = "-";
}

if(v == "conventional")
{
p1 = c1;
p2 = c2;
p3 = c3;

if(c1 == ''){
        $('#con1').hide();
}
if(c2 == ''){
        $('#con2').hide();
}
if(c3 == ''){
        $('#con3').hide();
}
$('#con1').text(c1);
$('#con2').text(c2);
$('#con3').text(c3);
$('#con1').val(c1);
$('#con2').val(c2);
$('#con3').val(c3);
$('#con').show();
$('#isl').hide();

}

else if(v == "islamic")
{

p4 = i1;
p5 = i2;
p6 = i3;
if(i1 == ''){
        $('#isl1').hide();
}
if(i2 == ''){
        $('#isl2').hide();
}
if(i3 == ''){
        $('#isl3').hide();
}
$('#isl1').text(i1);
$('#isl2').text(i2);
$('#isl3').text(i3);
$('#isl1').val(i1);
$('#isl2').val(i2);
$('#isl3').val(i3);
$('#con').hide();
$('#isl').show();

}

else if(v == "any")
{

p1 = c1;
p2 = c2;
p3 = c3;
p4 = i1;
p5 = i2;
p6 = i3;
if(c1 == ''){
        $('#con1').hide();
}
if(c2 == ''){
        $('#con2').hide();
}
if(c3 == ''){
        $('#con3').hide();
}
if(i1 == ''){
        $('#isl1').hide();
}
if(i2 == ''){
        $('#isl2').hide();
}
if(i3 == ''){
        $('#isl3').hide();
}

$('#con1').text(c1);
$('#con2').text(c2);
$('#con3').text(c3);
$('#con1').val(c1);
$('#con2').val(c2);
$('#con3').val(c3);
$('#isl1').text(i1);
$('#isl2').text(i2);
$('#isl3').text(i3);
$('#isl1').val(i1);
$('#isl2').val(i2);
$('#isl3').val(i3);
$('#con').show();
$('#isl').show();
}

else
{
var check = "Product Not Selected";
$('#pns').text(check);
}

$('#crf').on('touchstart click', function(){
	        $('#choosen_fund').val($('#crf').val());      

});
$('#irf').on('touchstart click', function(){
	        $('#choosen_fund').val($('#irf').val());      

});

/////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//$('#rf').val(p);

//// fg logics

//if(p == 'Multi Asset Fund'){

//$(".recommended_fund_link").attr("href", "maf");

//}
//if(p == 'Islamic Money Market Fund'){

//$(".recommended_fund_link").attr("href", "immf");
//}
//if(p == 'Islamic Income Fund'){
//$(".recommended_fund_link").attr("href", "iif");
//}
//if(p == 'Islamic Asset Allocation Fund or IFPF Conservative Plan'){
//$(".recommended_fund_link").attr("href", "iaaf");
//}		
//if(p == 'IFPF Active Allocation Plan'){
//$(".recommended_fund_link").attr("href", "ifpf");
//}

//// fg logics end
}

// Set animation delay if data-delay is specified
Reveal.addEventListener('ready', function ( event ) {
$('*[data-delay]').each( function () {
var delay = $(this).attr("data-delay");
$(this).css("-webkit-animation-delay", delay+"s");
$(this).css("animation-delay", delay+"s");
});
});

// Set animation duration if data-duration is specified
Reveal.addEventListener('ready', function ( event ) {
$('*[data-duration]').each( function () {
var duration = $(this).attr("data-duration");
$(this).css("-webkit-animation-duration", duration+"s");
$(this).css("animation-duration", duration+"s");
});
});

// Animate items that are not in a fragment
Reveal.addEventListener('slidechanged', function( event ) {
// Animate elements that are not a fragment (or in a fragment)
var filter = '*[data-animate]:not(.fragment):not(.fragment *)';

$(event.currentSlide).find(filter).each( function () {
$(this).addClass('animated');
$(this).addClass($(this).attr('data-animate'));
});
$(event.previousSlide).find(filter).each( function () {
$(this).removeClass('animated');
$(this).removeClass($(this).attr('data-animate'));
});
});

// Animate fragments
Reveal.addEventListener('fragmentshown', function( event ) {
function loop(i, el) {
if ($(el).attr('data-animate')) {
$(el).addClass('animated');
$(el).addClass($(el).attr('data-animate'));
}
$.each($(el).children().not('.fragment'), loop);
};
$.each(event.fragments, loop);
});

// Make the animation runnable again if fragment is hidden
Reveal.addEventListener('fragmenthidden', function( event ) {
function loop(i, el) {
if ($(el).attr('data-animate')) {
$(el).removeClass('animated');
$(el).removeClass($(el).attr('data-animate'));
}
$.each($(el).children().not('.fragment'), loop);
};
$.each(event.fragments, loop);
});


// More info about config & dependencies:
// - https://github.com/hakimel/reveal.js#configuration
// - https://github.com/hakimel/reveal.js#dependencies
Reveal.initialize({
// The "normal" size of the presentation, aspect ratio will be preserved
// when the presentation is scaled to fit different resolutions. Can be
// specified using percentage units.
width: 1920,
height: 970,



// Factor of the display size that should remain empty around the content
margin: -0.05,

// Bounds for smallest/largest possible scale to apply to content
minScale: 0.2,
maxScale: 1.5,
dependencies: [
{ src: 'plugin/markdown/marked.js' },
{ src: 'plugin/markdown/markdown.js' },
{ src: 'plugin/notes/notes.js', async: true },
{ src: 'plugin/chart/Chart.min.js' },
{ src: 'plugin/chart/csv2chart.js' },
{ src: 'plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } }
]
});

/// code for geo location

if (navigator.geolocation) {
navigator.geolocation.getCurrentPosition(showPosition);
} else { 
x.innerHTML = "Geolocation is not supported by this browser.";
}


function showPosition(position) {
var lat =  position.coords.latitude;
var lng = position.coords.longitude;	
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var arr = JSON.parse(this.responseText);
var data = arr.results[0].address_components;
var loc = data[0].long_name+ ' ' + data[1].long_name;
$('#location').val(loc);
$('#longitude').val(lng);
$('#latitude').val(lat);

console.log(arr)
}
};
xhr.open("GET", "https://maps.google.com/maps/api/geocode/json?latlng="+lat+","+lng+"&sensor=true");
xhr.send();

}
/// code for geo location end



/// hide product menu from footer on index and products page

var index_url = "{{url('welcome')}}";
var products_url = "{{url('products')}}";
var default_url = window.location.href.toString();
if(index_url == default_url || products_url == default_url ){
   $('.all_products').hide();
}
/// hide product menu from footer on index and products page end
</script>



</body>
</html>