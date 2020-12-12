<style>
canvas {
-moz-user-select: none;
-webkit-user-select: none;
-ms-user-select: none;
}
</style>

<script>
var mmsf_yr = [];
var mmsf_ret = [];
var mmsf_val = [];
var dsf_yr = [];
var dsf_ret = [];
var dsf_val = [];
var esf_yr = [];
var esf_ret = [];
var esf_val = [];
<?php foreach($charts as $chart){  
    if($chart->plan == 'cap'){
    ?>
var mmsf_y = '<?php echo $chart->year;?>';
var mmsf_r = <?php echo rtrim($chart->ret,'%');?>;
var mmsf_v = <?php echo $chart->val;?>;

mmsf_yr.push(mmsf_y);
mmsf_ret.push(mmsf_r);
mmsf_val.push(mmsf_v);

<?php }
 if($chart->plan == 'aap'){?>

var dsf_y = '<?php echo $chart->year;?>';
var dsf_r = <?php echo rtrim($chart->ret,'%');?>;
var dsf_v = <?php echo $chart->val;?>;

dsf_yr.push(dsf_y);
dsf_ret.push(dsf_r);
dsf_val.push(dsf_v);

 <?php }

if($chart->plan == 'sap'){?>

    var esf_y = '<?php echo $chart->year;?>';
    var esf_r = <?php echo rtrim($chart->ret,'%');?>;
    var esf_v = <?php echo $chart->val;?>;
    
    esf_yr.push(esf_y);
    esf_ret.push(esf_r);
    esf_val.push(esf_v);
    
     <?php } }?>

 window.onload = function() {
  

    Reveal.addEventListener( 'somestate1', function() {
       
   


var chartData = {
labels:mmsf_yr,
datasets: [
{
type: 'line',
label: 'Return in %',
borderColor: window.chartColors.blue,
borderWidth: 1,
fill: false,
data:mmsf_ret,
borderColor: [
'rgba(197,90,17,1)'
],
backgroundColor: [
'rgba(255, 255, 255, 1)',
],
},

{
type: 'bar',
label: 'Value',
backgroundColor: window.chartColors.red,
data:mmsf_val,
borderColor: '',
borderWidth: 0,
backgroundColor: [
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)'
],

}, ]

};
var ctx = document.getElementById("canvas1").getContext("2d");
window.myMixedChart = new Chart(ctx, {
type: 'bar',
data: chartData,
options: {
responsive: true,
legend: {
position: 'bottom',
},
title: {
display: true,
text: ''
},
tooltips: {
mode: 'index',
intersect: true
}
}
});
    }, false );

    Reveal.addEventListener( 'somestate2', function() {


    var chartData = {
labels:dsf_yr,
datasets: [
{
type: 'line',
label: 'Return in %',
borderColor: window.chartColors.blue,
borderWidth: 1,
fill: false,
data:dsf_ret,
borderColor: [
'rgba(197,90,17,1)'
],
backgroundColor: [
'rgba(255, 255, 255, 1)',
],
},

{
type: 'bar',
label: 'Value',
backgroundColor: window.chartColors.red,
data:dsf_val,
borderColor: '',
borderWidth: 0,
backgroundColor: [
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)'
],

}, ]

};
var ctx2 = document.getElementById("canvas2").getContext("2d");
window.myMixedChart = new Chart(ctx2, {
type: 'bar',
data: chartData,
options: {
responsive: true,
legend: {
position: 'bottom',
},
title: {
display: true,
text: ''
},
tooltips: {
mode: 'index',
intersect: true
}
}
});
    }, false );

Reveal.addEventListener( 'somestate3', function() {
    
 

        var chartData = {
labels:esf_yr,
datasets: [
{
type: 'line',
label: 'Return in %',
borderColor: window.chartColors.blue,
borderWidth: 1,
fill: false,
data:esf_ret,
borderColor: [
'rgba(197,90,17,1)'
],
backgroundColor: [
'rgba(255, 255, 255, 1)',
],
},

{
type: 'bar',
label: 'Value',
backgroundColor: window.chartColors.red,
data:esf_val,
borderColor: '',
borderWidth: 0,
backgroundColor: [
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)'
],

}, ]

};
var ctx3 = document.getElementById("canvas3").getContext("2d");
window.myMixedChart = new Chart(ctx3, {
type: 'bar',
data: chartData,
options: {
responsive: true,
legend: {
position: 'bottom',
},
title: {
display: true,
text: ''
},
tooltips: {
mode: 'index',
intersect: true
}
}
});
    }, false );


 };

</script>