<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sponsersController extends Controller
{

public function index()
{
return view('admin.sponsers');
}



public function store(Request $request)
{
$args = [
"title" => $request->title, 
"f1" => $request->f1,
"f2" => $request->f2,
"f3" => $request->f3,
"f4" => $request->f4,
"f5" => $request->f5,
"f6" => $request->f6,
];

$success =  \DB::table('sponsers')->insert($args);
if($success){
return redirect('sponsers');
}

}


public function edit()
{
return view('admin.sponsers-edit',[
"results"=>\DB::table('sponsers')
->where('id','=',1)
->get(),
]);
}
public function edit_sc_chart($id)
{
$data = \DB::table('sponsor_chart_1')
->where('id','=',$id)
->get();
$banks =  \DB::table('banks')
->where('chart_id','=',$id)
->get();

$results =  \DB::table('sc_yr')
->where('chart_id','=',$id)
->get();

return view('admin.sc-chart',["data"=>$data,"banks"=>$banks,"results"=>$results]);
}

public function update_sc_chart(Request $request)
{
$success = \DB::table('sponsor_chart_1')->where('id','=',$request->id)->update([
'sc_1_nums'=>$request->sc_1_nums
]);  
if($success){
return back()->with('msg','Record has been updated');  
}
else{
return back()->with('err','Oops! something went wrong!!');  
}

}

public function edit_scf($id)
{
$data = \DB::table('sponsor_chart_1')->where('id','=',$id)->get();
return view('admin.scf',["data"=>$data]);
}

public function update_scf(Request $request)
{

$success = \DB::table('sponsor_chart_1')->where('id','=',$request->id)->update([
'sc_1_nums'=>$request->sc_1_nums

]);  
if($success){
return back()->with('msg','Record has been updated');  
}
else{
return back()->with('err','Oops! something went wrong!!');  
}

}

public function update_yr_chart(Request $request)
{
$success = \DB::table('sc_yr')->where('id','=',$request->id)->update([
'yr'=>$request->yr

]);  
if($success){
return back()->with('msg','Record has been updated');  
}
else{
return back()->with('err','Oops! something went wrong!!');  
}

}





public function edit_bank_names($id)
{
$data = \DB::table('banks')->where('id','=',$id)->get();
return view('admin.edit-banks',["data"=>$data]);
}

public function update_bank_names(Request $request)
{
$success = \DB::table('banks')->where('id','=',$request->id)->update([
'bank'=>$request->bank,
'bank_color'=>$request->bank_color
]);  
if($success){
return back()->with('msg','Record has been updated');  
}
else{
return back()->with('err','Oops! something went wrong!!');  
}

}


public function update(Request $request)
{
$results = \DB::table('sponsers')->where('id','=', 1 )->update([ 
"title" => $request->title, 
"f1" => $request->f1,
"f2" => $request->f2,
"f3" => $request->f3,
"f4" => nl2br($request->f4),
"f5" => $request->f5,
"f6" => $request->f6,]);
return back();
}
}
