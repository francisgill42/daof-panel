<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use JWTAuth;
use JWTAuthException;
use App\User;

class ApiController extends Controller
{

//   if (auth()->validate($credentials)) {
//     // credentials are valid
// }

public function __construct()
{
$this->user = new User;
}

public function login(Request $request){
$credentials = $request->only('email', 'password');
$token = null;
try {
if (!$token = JWTAuth::attempt($credentials)) {
return response()->json([
'response' => 'error',
'message' => 'invalid_email_or_password',
]);
}
} catch (JWTAuthException $e) {
return response()->json([
'response' => 'error',
'message' => 'failed_to_create_token',
]);
}
return response()->json([
'response' => 'success',
'result' => [
'token' => $token,
],
]);
}
public function me()
{
	return response()->json(auth('api')->user()->id);
}

public function getAuthUser(Request $request){
$user = JWTAuth::toUser($request->token);
$role_title = \DB::table('roles')->where('id',$user->role_id)->first()->role_title;
$data = [
		'id' => $user->id,
		'name' => $user->name,
		'email' => $user->email, 
		'role_title' => $role_title, 
];
return response()->json($data);
}


public function pitch_count_set($count)
{

	$counts = \DB::table('views')->where('user_id',auth('api')->user()->id)->first();

	if(is_null($counts)){
	\DB::table('views')
	->insert([
	'count'=> 1,
	'user_id' => auth('api')->user()->id,
	]);
	} 
	else{
	\DB::table('views')
	->where('user_id',auth('api')->user()->id)
	->update([
	'count'=> $count
	]);
	}
	return response()->json(200);
}
public function pitch_count_get()
{
	$count = \DB::table('views')->where('user_id',auth('api')->user()->id)->sum('count');
	return response()->json($count);
}

public function leads_count()
{
	$count = \DB::table('risks')->where('user_id',auth('api')->user()->id)->count();
	return response()->json($count);
}

public function push()
{
$res = ($this->generate_leads() == 200) ? 200 : 401 ;
return response()->json($res);


}

private function generate_leads(){
$data = \DB::table('risks')->where('user_id',auth('api')->user()->id)->where('pushed',0)->get();
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

$url = "http://10.6.209.252:5000/service.asmx/CreateLead?OwnerEmail=".auth('api')->user()->email."&Name=".$client_name ."&LeadEmail=".$client_email."&MobileNumber=".$num."&City=".$location."&Fund=".$choosen_fund."&&CNICORNTN=".$data->client_cnic."";
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
//curl_close($ch);
}
public function getleadsbyuser()
{
	return response()->json(
		\DB::table('risks')
	->where('pushed',0)
	->where('user_id', auth('api')->user()->id)->get());

}

}
