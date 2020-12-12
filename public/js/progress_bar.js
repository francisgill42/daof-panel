function progress_bar(s,v){
  $('#'+s).keyup(function(){
      if($('#'+s).val()){
       $("#pb").css({"width": v+'%'}).text(v+'%');
      }
  }).change(function(){
    if($('#'+s).val()){
     $("#pb").css({"width": v+'%'}).text(v+'%');
    }
  });
}

progress_bar('name',10);
progress_bar('father_name',20);
progress_bar('dob',30);
progress_bar('cnic',40);
progress_bar('cell',50);
progress_bar('occupation',60);
progress_bar('soi',70);
progress_bar('address',80);
progress_bar('fund',90);
progress_bar('mop',100);
