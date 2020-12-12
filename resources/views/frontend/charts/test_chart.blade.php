<!doctype html>
<html>

<head>
<title>Combo Bar-Line Chart</title>
<script src="{{url('js/Chart.bundle.js')}}"></script>
<script src="{{url('js/utils.js')}}"></script>
<style>
canvas {
-moz-user-select: none;
-webkit-user-select: none;
-ms-user-select: none;
}
</style>
<style>

</style>
</head>

<body>
<div  id="graphs" >
<canvas id="canvas"></canvas>
</div>
<?php 
foreach($charts as $chart){
$ch_k1 =  $chart->ch_k1;
$ch_k2 =  $chart->ch_k2;
$ch_k3 =  $chart->ch_k3;
$ch_rf1 =  $chart->ch_rf1;
$ch_rf2 =  $chart->ch_rf2;
$ch_rf3 =  $chart->ch_rf3;   
$ch_vf1 =  $chart->ch_vf1;
$ch_vf2 =  $chart->ch_vf2;
$ch_vf3 =  $chart->ch_vf3;

}
?>
<script>
window.onload = function() {
Reveal.addEventListener( 'somestate', function() {
var chartData = {
labels: [
'<?php echo $ch_k1;  ?>',
'<?php echo $ch_k2;  ?>',
'<?php echo $ch_k3;  ?>',
],
datasets: [
{
type: 'line',
label: 'Return in %',
borderColor: window.chartColors.blue,
borderWidth: 1,
fill: false,
data: [ 
<?php echo $ch_rf1;?>,
<?php echo $ch_rf2;?>,
<?php echo $ch_rf3 ;?>,
],
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
data: [ 
<?php echo $ch_vf1;?>,
<?php echo $ch_vf2;?>,
<?php echo $ch_vf3;?>,
],
borderColor: '',
borderWidth: 0,
backgroundColor: [
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)',
'rgba(0, 168, 150, 1)'
],

}, ]

};
var ctx = document.getElementById("canvas").getContext("2d");
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

};





document.getElementById('randomizeData').addEventListener('click', function() {
chartData.datasets.forEach(function(dataset) {
dataset.data = dataset.data.map(function() {
return randomScalingFactor();
});
});
window.myMixedChart.update();
});
</script>

</body>

</html>
