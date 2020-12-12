<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadsController extends Controller
{


public function generate_leads()
{
	if ($this->push() == 200) {
		return back()->with('msg','Leads has been pushed to CRM');
	} 
	else{
		return back()->with('msg','Leads has not been pushed to CRM');
	}

}

private function push(){
$data =	\DB::table('risks')->where('user_id',\Auth::user()->id)->where('pushed',0)->get();
$count = count($data);

$i = 0;
foreach ($data as $data) {

$num = (substr($data->client_number, 0, 1) == '0') ? '92'.ltrim($data->client_number,"0") : $data->client_number;
$client_name = str_replace(' ','%20',$data->client_name);
$client_email = str_replace(' ','%20',$data->client_email);
$location = str_replace(' ','%20',$data->location);
$choosen_fund = str_replace(' ','%20',$data->choosen_fund);

/////////////
$i++;

$ch = curl_init();

$url = "http://10.6.209.252:5000/service.asmx/CreateLead?OwnerEmail=".\Auth::user()->email."&Name=".$client_name ."&LeadEmail=".$client_email."&MobileNumber=".$num."&City=".$location."&Fund=".$choosen_fund."&&CNICORNTN=".$data->client_cnic."";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

if(curl_error($ch)){
		return json_encode(404);
}
else{

	curl_exec($ch);

	\DB::table('risks')
	->where('id',$data->id)
	->update([
	'pushed' => 1
	]);
}



}
if($i == $count){
	return json_encode(200);
}
else{
	return json_encode(400);
}
curl_close($ch);
}

}
