<script>
window.onload = function() {
Reveal.addEventListener( 'somestate', function() {

<?php
get_chart_data(1,'canvas1','bar','Deposit Base of Big Five Banks');
get_chart_data(2,'canvas2','line','Profit After Tax of Big Five Banks (PKR MILLION)');
?>


}, false );


var key = [];
var val = [];
@foreach($aums1_chart as $ac1)
var k = '{{$ac1->aums_1_key}}';
var v = <?php echo  str_replace(',', '',$ac1->aums_1_value); ?>;
key.push(k);
val.push(v);
@endforeach




Reveal.addEventListener( 'some_state1', function() {

var chartData = {
labels:key,
datasets: [{
label: 'HBL',
backgroundColor: '#0d846c',
borderWidth: 1,
data:val
}

]
};
var ctx3 = document.getElementById("canvas3").getContext("2d");
window.myMixedChart = new Chart(ctx3, {
type: 'bar',
data: chartData,
options: {
responsive: true,
legend: {
display: false,
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


var key2 = [];
var val2 = [];
<?php foreach($aums2_chart as $ac1){?>
var k2 = '<?php echo $ac1->aums_2_key?>';
var v2 = <?php echo  str_replace(',', '',$ac1->aums_2_value); ?>;
key2.push(k2);
val2.push(v2);
<?php }?>
Reveal.addEventListener( 'some_state2', function() {

var chartData = {
labels:key2,
datasets: [{
label: 'HBL',
backgroundColor: '#0d846c',
borderWidth: 1,
data: val2
}

]
};
var ctx4 = document.getElementById("canvas4").getContext("2d");
window.myMixedChart = new Chart(ctx4, {
type: 'bar',
data: chartData,
options: {
responsive: true,
legend: {
display: false,
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
<!-- core function  -->
<?php function get_chart_data($id,$canvas,$type,$text){?>
window.myMixedChart = new Chart(document.getElementById("<?php echo $canvas;?>").getContext("2d"), {
type: '<?php echo $type;?>',
data: {
labels:
[
<?php
foreach(\DB::table('sc_yr')->where('chart_id',$id)->pluck('yr') as $i){
for($row = 1; $row <6; $row++){
?>
'<?php echo $i++;?>',
<?php }}?>
],
datasets: [
<?php
foreach(\DB::table('banks')->where('chart_id','=',$id)->get() as $bank){?>
  {
    label: '<?php echo $bank->bank;?>',
    backgroundColor:"<?php echo $bank->bank_color;?>",
    borderWidth: 5,
    borderColor: "<?php echo $bank->bank_color;?>",
            fill: false,
    data:
    [
      <?php
      $values = \DB::table('sponsor_chart_1')->where('bank_id','=',$bank->id)->get();
      foreach($values as $value){?>
        <?php echo $value->sc_1_nums;?>,
     <?php } ?>
    ]
    },
<?php } ?>
]
},
options: {
responsive: true,
legend: {
position: 'bottom',
},
title: {
display: true,
text: '<?php echo $text;?>'
},

tooltips: {
mode: 'index',
intersect: true
}
}
});
<?php }?>
