function yes_no(value,check,selector){
    if(value == check) {
    $(selector).show();
    }
    else{
    $(selector).hide();    
    }
}
function show_hide(value,check,selector1,selector2){
 
    if(value == check) {
      $(selector1).show();
      $(selector2).hide();
    }
    else{
      $(selector1).hide();
      $(selector2).show();
    }

}

function getCities(){
  $.get('http://orangeroomdigital.com/location/cities.php',function(data,status){
  var cities = JSON.parse(data);
  //console.log(cities);
  for(c in cities){
  $('#mycities,#mycities_resi').append("<option value='"+cities[c].id+"'>"+cities[c].city+"</option>");
  }
  });

  $('.js-example-basic-single').select2();

}

getCities();


var val_holder = 0;
var total_sum = 25000;
var limit = 0; 

checkit('sum','amount_msg');
function checkit(cal_class,cal_class_msg){



var last_amount = 0;
var old_amount = parseInt(localStorage.getItem('old_amount'));
$('.' + cal_class).blur(function(){ 
  if(old_amount){
         val_holder += old_amount;

  }

var amount = parseInt($(this).val()); 
if(last_amount == 0){
val_holder += amount;
old_amount -= old_amount; 

}
else{
val_holder -= last_amount;
val_holder += amount;
old_amount -= old_amount; 

}

if(val_holder <= total_sum){

$('#add_fund,.submit').show();
$('.' + cal_class_msg).text('');
last_amount = amount;

}
else{

$('#add_fund,.submit').hide();

 if(isNaN(amount) || isNaN(val_holder)){
  val_holder = amount = 0;
}

if(val_holder > 0){
if(old_amount){

}
val_holder -= amount; 
limit = total_sum - val_holder;
$('.' + cal_class_msg).text('investment limit '+limit+' 0nly');
last_amount = 0;
}


}


if(val_holder == total_sum){
$('#add_fund').hide();
}
});



}


$('#add_fund').hide();
word_converter('amount','word');
min_max_validation('cnic',13,20,'cnic_msg','number');
min_max_validation('cell',11,15,'cell_msg','number');
capitalize($('#name'));
capitalize($('#middle_name'));
capitalize($('#last_name'));
capitalize($('#father_name'));
capitalize($('#soi'));
capitalize($('#occupation'));
capitalize($('#address'));








var funds = [];
$('#fund_name').change(function(){
  funds.push($(this).val());
  //console.log(funds);
});

$('#checkout').click(function(){
        $('#pre_name').text($('#name').val());
        $('#pre_father_name').text($('#father_name').val());
        $('#pre_email').text($('#email').val());
        $('#pre_dob').text($('#dob').val());
        $('#pre_cnic').text($('#cnic').val());
        $('#pre_cell').text($('#cell').val());
        $('#pre_occupation').text($('#occupation').val());
        $('#pre_soi').text($('#soi').val());
        $('#pre_address').text($('#address').val());
        $('#pre_country').text($('#country').val());
        $('#pre_cities').text($('#mycities').val());


});



$('.check').click(function(){
var id = localStorage.getItem('id');
$('.fund_row'+id).remove();
localStorage.removeItem('id');
});

$('.mop_check').click(function(){
var mop_id = localStorage.getItem('mop_id');
$('.mop_row'+mop_id).remove();
localStorage.removeItem('mop_id');
});

function delItem(clickclass,deleteclass){

$(clickclass).click(function(){
$(deleteclass).remove();
});

}

function capitalize(selector){

selector.keyup(function(){
$(this).val($(this).val().toUpperCase() );
});

}
delItem('.rem0','.fund_row0');
delItem('.mop_rem0','.mop_row0');
delItem('.mop_reset','.mop_remove');
delItem('.reset','.remove');


var n = 0;
$('#add_mop').click(function(){
n++;

$('.mop2bappend').append('<div class="form-row mop_remove mop_row'+n+'"><div class="col-md-6"><label>Payment Mode</label><select style="height: auto;"  name="mop[]" class="form-control" ><option value="Cheque:'+n+'">Cheque</option><option value="Pay Order:'+n+'">Pay Order</option><option value="Demand:'+n+'">Demand</option><option value="Draft:'+n+'">Draft</option><option value="Real Time Gross Settlement (RTGS):'+n+'">Real Time Gross Settlement (RTGS)</option></select></div><div class="col-md-5"><label>Attachments</label><input name="mop_files[]" type="file" multiple style="padding: 3px 5px" class="form-control" ></div><div class="col-md-1 form-inline" style="margin-top:24px;"><button type="button" value="'+n+'" class="mop_rem'+n+' btn btn-primary">X</button></div></div>');

$('.mop_rem'+n).click(function(){
localStorage.setItem('mop_id',this.value);
var get_id = localStorage.getItem('mop_id');
$('.mop_check').trigger('click');

});
});

function min_max_validation(selector,min_range,max_range,msg_selector,type){

$("#"+selector).keyup(function(){


var value = $("#"+selector).val();
//var alphabets = parseInt(value);

// if(!isNaN(alphabets)) {
// $("."+msg_selector).text('number input is not allowed.');
// }

if(value.length < min_range &&  value != '') {
$("."+msg_selector).text('minimum '+min_range+' character is allowed');
}

else if(value.length > max_range) {
$("."+msg_selector).text('maximum '+max_range+' character is allowed');
}

else{
  $("."+msg_selector).text('');


}


});
}

