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
<script>
var yr = [];
var ret = [];
var val = [];
<?php foreach($charts as $chart){ ?>
var y = '<?php echo $chart->year;?>';
var r = <?php echo $chart->ret;?>;
var v = <?php echo $chart->val;?>;

yr.push(y);
ret.push(r);
val.push(v);

<?php } ?>
window.onload = function() {
Reveal.addEventListener( 'somestate', function() {
    //alert("azhar");
var chartData = {
labels:yr,
datasets: [
{
type: 'line',
label: 'Return in %',
borderColor: window.chartColors.blue,
borderWidth: 1,
fill: false,
data:ret,
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
data:val,
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
