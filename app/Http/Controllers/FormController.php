<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FormValidation;
use DB;
use App\Form;
use Yajra\Datatables\Datatables;

use Mail;
use App\Mail\Forms;
use App\Mail\Assign_Form;
use App\Mail\Send_To_CBC;
use App\Mail\CBC_Change;

class FormController extends Controller
{
public function __construct()
{
$this->middleware('auth');
}

public function index()
{
$data = Form::all();
return view('forms.list',compact('data'));
}


public function assign(Request $request,$id)
{

  $user_email = DB::table('users')->where('id',$request->user_id)->first()->email;

  $updated = DB::table('forms')
  ->where('form_id',$id)
  ->update([
  'assigned_to' => $request->user_id
  ]);
  
  if($updated){
  
    // Mail::to($user_email)->send(new Assign_Form());

  return back()->with('msg','Form has been assigned');
  }
  else{

  return back()->with('err','Form has already been assigned');
  }

}



public function show($id)
{
//    dd($id);


$customer_id = Form::where('form_id',$id)->first()->customer_id;
$form_data = \DB::table('forms')->where('form_id',$id)->first();

$user_name = 'self';
if($form_data->user_id != 0){

$user_name = \DB::table('users')->where('id',$form_data->user_id)->first()->name;          
}

$customer_details = \DB::table('customers')->where('id',$customer_id)->first();

$investment_details = 
\DB::table('investment_details')->where('customer_id',$customer_id)->first();

$bank_details = \DB::table('bank_details')->where('customer_id',$customer_id)->first();

$other_details = \DB::table('other_details')->where('customer_id',$customer_id)->first();

$fatca_details =  \DB::table('fatca_details')->where('customer_id',$customer_id)->first();

$msgs = \DB::table('discussions')->where('form_id',$id)->get();

return view('retails.single',[
    'form_id' => $id,
    "user_name" => $user_name,
    'form_data' => $form_data,
    'customer_details' => $customer_details,
    'bank_details' => $bank_details,
    'investment_details' => $investment_details,
    'other_details' => $other_details,          
    'fatca_details' => $fatca_details,
    'msgs' => $msgs,
]);

}

public function send_to_cbc(Request $r,$id)
{

$cbc_email = DB::table('users')->where('id',76)->first()->email;

$updated = DB::table('forms')->where('form_id',$id)->update(['status' => 3]);

if($updated){
// Mail::to($cbc_email)->send(new Send_To_CBC());
$arr = [
'form_id'=>$id,
'msg'=>$r->msg,
'receiver_id' => 76/*\DB::table('users')->where('role_id',5)->first()->id*/,
'sender_id' =>  \Auth::user()->id,
'created_at' => now(),
'updated_at' => now(),
];
DB::table('discussions')->insert($arr);
return back()->with('msg','form has been sent to cbc');
}else{
return back()->with('err','form has already been sent to cbc.');   
}

}

public function cbc_edit($id)
{
//    dd(\Auth::user()->id);
$customer_id = \DB::table('forms')->where('form_id',$id)->first()->customer_id;
$customers = \DB::table('customers')->where('id',$customer_id)->first();
//dd($customers);
return view('retails.cbc-edit',[
'form_id'=> $id,
'customer_details' => $customers,
'msgs' => \DB::table('discussions')->where('form_id',$id)
->get(),
]);
}

public function cbc_done(Request $req,$id)
{
$customer_id = \DB::table('forms')->where('form_id',$id)->first()->customer_id;

$cnic_attachment = '';
if($req->hasFile('cnic_attachment')){
$cnic_attachment = $req->cnic_attachment->getClientOriginalName();
$req->cnic_attachment->move(public_path('uploads/cnic_attachment/'),$cnic_attachment);		
}

else{
  $cnic_attachment = $req->old_file_cnic;
}
  

$soi_attachment = '';
if($req->hasFile('soi_attachment')){
$soi_attachment = $req->soi_attachment->getClientOriginalName();
$req->soi_attachment->move(public_path('uploads/soi_attachment/'),$soi_attachment);		
}

else{
$soi_attachment = $req->old_file_soi;
}


$zakat_certificate = '';
if($req->hasFile('zakat_certificate')){
$zakat_certificate = $req->zakat_certificate->getClientOriginalName();
$req->zakat_certificate->move(public_path('uploads/zakat_certificates/'),$zakat_certificate); 
}
else{
$zakat_certificate = $req->old_file;
}

$arr = [
"name" => $req->name,
"father_name" => $req->father_name,
"mother_name" => $req->mother_name, 
"dob" => $req->dob, 
"cnic" => $req->cnic,
"cnic_attachment"=> $cnic_attachment,
"cnic_issue_date" => $req->cnic_issue_date,
"pob_country" => $req->pob_country[1],
"pob_city" => $req->pob_city[1],
"email" => $req->email,
"cell" => $req->cell,    
"occupation" => $req->occupation,
"soi" => $req->soi,
"soi_attachment" => $soi_attachment,
"address" => $req->address,
"country1" => $req->country1[1],
"city1" => $req->city1[1],
"zakat" => $req->zakat,
"zakat_certificate" => $zakat_certificate,
"updated_at" => now(),
];
$old = \DB::table('customers')->where('id',$customer_id)->first();
// dd($old,json_decode(json_encode($arr)));
$updated = \DB::table('customers')->where('id',$customer_id)->update($arr);
if($updated){

$success = DB::table('forms')->where('form_id',$id)->update(['status' => 4]);

DB::table('discussions')->insert([
  'form_id'=>$id,
  'msg'=>$req->msg,
  'receiver_id' => 0,
  'sender_id' =>  \Auth::user()->id,
  'created_at' => now(),
  'updated_at' => now(),
]);
// Mail::to($req->email)->send(new CBC_Change());
return back()->with('msg','record has been updated');
}
else{
  return back()->with('msg','record has not been updated');  
}

}



public function push_to_crm(Request $request){


$form_data = json_decode($request->form_data);
$customer_details = json_decode($request->customer_details);
$bank_details = json_decode($request->bank_details);
$investment_details = json_decode($request->investment_details);
$fatca_details = json_decode($request->fatca_details);
$other_details = json_decode($request->other_details);

$cnic_ext =  \File::extension('uploads/cnic_attachment/'.$customer_details->cnic_attachment);

$wf_ext =  \File::extension('uploads/wforms/'.$fatca_details->wform);

$soi_ext =  \File::extension('uploads/soi_attachment/'.$customer_details->soi_attachment);

$zakat_ext =  \File::extension('uploads/zakat_certificates/'.$customer_details->zakat_certificate);

$inv_ext =  \File::extension('uploads/investment_detail_attachments/'.$investment_details->attachment);


$cnic_attachment = 
base64_encode(file_get_contents('uploads/cnic_attachment/'.$customer_details->cnic_attachment));

$soi_attachment = 
base64_encode(file_get_contents('uploads/soi_attachment/'.$customer_details->soi_attachment));

$inv_attachment = 
base64_encode(file_get_contents('uploads/investment_detail_attachments/'.$investment_details->attachment));

if($customer_details->zakat_certificate != 'no_image.png'){
  $zakat_certificate = 
  base64_encode(file_get_contents('uploads/zakat_certificates/'.$customer_details->zakat_certificate));

}
else{
$zakat_certificate = '';
}

if($fatca_details->wform != 'no_image.png'){
  $wf_attachment = 
  base64_encode(file_get_contents('uploads/wforms/'.$fatca_details->wform));
}
else{
  $wf_attachment = '';
}


if($fatca_details->multiple_nat == 'yes'){
  $nats = explode(',', $fatca_details->nats);
}


$arr = array (
  
  'InvestmentAttachment' => $inv_attachment,
  'SourceofIncomeAttachment' => $soi_attachment,
  'CNICAttachment' => $cnic_attachment,
  'ZakatAttachment' => $zakat_certificate,
  'WFormAttachment' => $wf_attachment,
 
  'Sales_Person_ID' => $form_data->user_id,
  'Channel' => $form_data->channel,
  'AcTitle' => $customer_details->name,
  'FatherName' => $customer_details->father_name,
  'MotherName' => $customer_details->mother_name,
  'Address' => $customer_details->address,
  'Email' => $customer_details->email,
  'BranchName' => $bank_details->branch_name,
  'AccountTitle' => $bank_details->account_title,
  'IBAN' => $bank_details->iban_number,
  'ZakatEmail' => 'info@hblasset.com',
  'ZakatHOAddress' => '7th Floor, Emerald Tower, G-19, Block 5, Main Clifton Road, Clifton, Karachi',
  'InstrumentNumber' => $investment_details->instrument_number,
  'DOB' => $customer_details->dob,
  'CNICIssueDate' => $customer_details->cnic_issue_date,
  'Cell' => $customer_details->cell,
  'PlaceOfBirth' => $customer_details->pob_country,
  'POBCity' => $customer_details->pob_city,
  'Occupation' => $customer_details->occupation,
  'CountryOfResidence' => $customer_details->country1,
  'CORCity' => $customer_details->city1,
  'ResidentNationalOf' => $customer_details->qq,
  'DAOFBankName' => $bank_details->bank_name,
  'DAOFBankNameInvestment' => $investment_details->bank_name,
  'FundName' => $investment_details->fund_name,
  'AmountInWords' => $investment_details->amount_in_words,
  'AmountRs' => $investment_details->amount,
  'FrontEndLoad' => $investment_details->front_end_load,
  'PaymentMode' => $investment_details->payment_mode,
  'Education' => $customer_details->education,
  'FrequencyofAccountStatement' => $other_details->asf,
  'TypeofUnits' => $other_details->type_of_units,
  'AverageAnnualIncome' => $customer_details->average_annual_income,
  'InvestmentAttachmentExtension' => 'image/'.$inv_ext,
  'designation' => $customer_details->designation,
  'department' => $customer_details->department,
  'WorkingExperience' => $customer_details->working_experience,
  'DividentPayout' => $other_details->dpo,
  'Zakat' => $customer_details->zakat == 'yes' ? true : false,
  'ZakatOptions' => $customer_details->zakat_options,
  'ZakatAttachmentExtension' => 'image/'.$zakat_ext,
  'MultipleNationalities' => $fatca_details->multiple_nat == 'yes' ? true : false,
  'Nationality1' => isset($nats) ? $nats[0]  : '',
  'Nationality2' => isset($nats) ? $nats[1]  : '',
  'Nationality3' => isset($nats) ? $nats[2]  : '',
  'GreenCard' => $fatca_details->green_card == 'yes' ? true : false,
  'TaxResi' => $fatca_details->tax_resi == 'yes' ? true : false,
  'ForCiti' => $fatca_details->for_citi == 'yes' ? true : false,
  'OverseasMailingAddress' => true, // data type should be string nullable
  'OverseasPhoneNumber' => true, // data type should be string nullable
  'AttorneyAddress' => $fatca_details->for_citi == 'yes' ? true : false,
  'AttorneyAddressInput' => $fatca_details->attor_addr = '',
  'TransFund' => $fatca_details->trans_fund == 'yes' ? true : false,
  'Wf' => $fatca_details->wf == 'yes' ? true : false,
  'WForm' => true ,//$fatca_details->wform_options == 'yes' ? true : false, // ?????
  'WFormAttachmentExtension' => 'image/'.$wf_ext,  
  'SourceOfIncome' => $customer_details->soi,
  'SourceofIncomeAttachmentExtension' => 'image/'.$soi_ext,
  'CNIC' => $customer_details->cnic,
  'CNICAttachmentExtension' => 'image/'.$cnic_ext,
  'OccupationName' => '',
  'BusinessJobCategory' => $customer_details->occ_name,
  'OrganizationBusinessEmployeeName' => $customer_details->org_emp_bes_name,
  'AgeofBusiness' => $customer_details->age_of_business ?? 0,
  'MaritalStatus' => $customer_details->marital_status == 'yes' ?  true : false,
  'PublicFigure' => $customer_details->public_figure == 'yes' ? true : false,
  'RefusedAccountbyInstitute' => $customer_details->refused_account_by_institute == 'yes' ? true : false,
  'HighValueItem' => $customer_details->high_value_item == 'yes' ? true : false,
  'NoofDependants' => $customer_details->no_of_dependants,

  'City_POB' => explode('|',$customer_details->pob_city)[1],
  'CityCode_POB' => explode('|',$customer_details->pob_city)[0],

  'Country_POB' => explode('|',$customer_details->pob_country)[1],
  'CountryCode_POB' => explode('|',$customer_details->pob_country)[0],

  'Country_MA' => explode('|',$customer_details->country1)[1],
  'CountryCode_MA' => explode('|',$customer_details->country1)[0],
 
  'City_MA' => explode('|',$customer_details->city1)[1],
  'CityCode_MA' => explode('|',$customer_details->city1)[0],
  
  'ResidentNationalOf' => $customer_details->qq,

  'BankBranch_BK' =>  explode('|',$bank_details->bank_name)[1], 
  'BankBranchCode_BK' =>  explode('|',$bank_details->bank_name)[0],
  'BankName_BK' => explode('|',$bank_details->bank_name)[1],
  'BankNameCode_BK' => explode('|',$bank_details->bank_name)[0],

  'BankName_INV' => explode('|',$investment_details->bank_name)[1],
  'BankNameCode_INV' => explode('|',$investment_details->bank_name)[0], 

);  


//echo json_encode($arr,JSON_UNESCAPED_SLASHES);
// echo '<pre>';
// print_r($arr);
// die;

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => "http://crm-test.hblasset.com:3001/api/dar",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
//CURLOPT_TIMEOUT => 30000,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => json_encode($arr),
CURLOPT_HTTPHEADER => ["content-type: application/json"],
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
echo "cURL Error #:" . $err;
} else {

return back()->with('msg', json_decode($response));

}


}

}
