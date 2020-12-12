<!doctype html>
<html>

<head>
<title>Combo Bar-Line Chart</title>

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
<!-- <canvas id="canvas1"></canvas> -->
<canvas id="canvas2"></canvas>
</div>
<script>
var yr = [];
var ret = [];
var val = [];
<?php foreach($charts as $chart){  
    if($chart->plan == 'mmsf'){
    ?>
var y = '<?php echo $chart->year;?>';
var r = <?php echo rtrim($chart->ret,'%');?>;
var v = <?php echo $chart->val;?>;

yr.push(y);
ret.push(r);
val.push(v);

<?php } }?>

</script>

</body>

</html>
