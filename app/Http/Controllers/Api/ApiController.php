<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Mail;
use App\Mail\Forms;
use App\Mail\Welcome;
use Validator;


class ApiController extends Controller
{
	public function ts(Request $req)
	{
		return response()->json(number_format($req->ts));
	}
	
	public function users()
	{
		$data = [
			'user_name' => 'user_name',
			'msg' => 'test'
		];
		$mail = Mail::to('aizazkalwar46@gmail.com')  
					->cc('francisgill1000@gmail.com')      
					->send(new Forms($data));

					//dd($mail);

					return empty($mail) ? 'mail sent' : 'mail not sent'; 

	}
	
    public function save_customer_info(Request $req)
    {

    	$cnic_attachment = null;
    	if($req->hasFile('cnic_attachment')){
			$cnic_attachment = $req->cnic_attachment->getClientOriginalName();
			$req->cnic_attachment->move(public_path('uploads/cnic_attachment/'),$cnic_attachment);		
    	}

    	$soi_attachment = null;
    	if($req->hasFile('soi_attachment')){
			$soi_attachment = $req->soi_attachment->getClientOriginalName();
			$req->soi_attachment->move(public_path('uploads/soi_attachment/'),$soi_attachment);		
    	}

    	$zakat_certificate = 'no_image.png';
    	if($req->hasFile('zakat_certificate')){
			$zakat_certificate = $req->zakat_certificate->getClientOriginalName();
			$req->zakat_certificate->move(public_path('uploads/zakat_certificates/'),$zakat_certificate);		
    	}


		$arr = [
			"name" => $req->name,
			"father_name" => $req->father_name,
			"mother_name" => $req->mother_name, 
			"dob" => $req->dob, 
			"cnic" => $req->cnic,
			"cnic_issue_date" => $req->cnic_issue_date,
			"pob_country" => $req->pob_country,
			"pob_city" => $req->pob_city,
			"email" => $req->email,
			"cell" => $req->cell,    
			"occupation" => $req->occupation,
			"education" => $req->education,
			
			"cnic_attachment" => $cnic_attachment,
			"soi_attachment" => $soi_attachment,
			"zakat_certificate" => $zakat_certificate,	

			"created_at" => now(),
			"updated_at" => now(),
	];

   			$inserted = DB::table('customers')->insertGetId($arr);
   		

   			// $mail_data = [
				// 'customer_name' => $req->name,
				// 'cnic' => $req->cnic,
				// "qq" => $req->qq,
				// "email" => $req->email
			// ];

			// $mail = new Welcome($mail_data);
			// Mail::to($req->email)->send($mail);	  
			//$res = $inserted ? $inserted : 500 ;
			
			return response()->json($inserted);
    }

        public function save_bank_details(Request $req){
 
    	$arr = [
		"bank_name" => $req->bank_name,
		"branch_name" => $req->branch_name,
		"account_title" => $req->account_title, 
		"iban_number" => $req->iban_number, 
		"customer_id" => $req->customer_id,
		"bank_name_id" => $req->bank_name_id,
		"branch_name_id" => $req->branch_name_id,
		"created_at" => now(),
		"updated_at" => now(),
	];
    	$inserted = DB::table('bank_details')->insert($arr);
	    return response()->json(200);
    	
    }



     public function save_investment_details(Request $req){

		$attachment = 'no_iamge.png';
    	if($req->hasFile('attachment')){
			$attachment = $req->attachment->getClientOriginalName();

			$req->attachment->move(public_path('uploads/investment_detail_attachments/'),$attachment);
    	}

    

		$arr = [
		"fund_name" => $req->fund_name,
		"amount" => $req->amount,
		"amount_in_words" => $req->amount_in_words, 
		"front_end_load" => $req->front_end_load,
		"payment_mode" => $req->payment_mode, 
		"attachment" => $attachment,
		"bank_name" => $req->bank_name_inv,
		"instrument_number" => $req->instrument_number,
		"customer_id" => $req->customer_id,
		"fund_name_id" => $req->fund_name_id,
		"payment_mode_id" => $req->payment_mode_id,
		"bank_name_id" => $req->bank_name_inv_id,
		"created_at" => now(),
		"updated_at" => now(),
	];
    	$inserted = DB::table('investment_details')->insert($arr);
	    return response()->json(200);
	}


	    public function save_other_details(Request $req){
 
    	$arr = [
		"asf" => $req->asf,
		"dpo" => $req->dpo,
		"type_of_units" => $req->type_of_units, 
		"customer_id" => $req->customer_id,
		"asf_id" => $req->asf_id,
		"created_at" => now(),
		"updated_at" => now(),
	];
    	$inserted = DB::table('other_details')->insert($arr);
	    return response()->json(200);
    	
    }
	
	public function save_fatca_details(Request $req){

    	$wf_name = 'no_image.png';
    	if($req->hasFile('wform')){
			$wf_name = $req->wform->getClientOriginalName();
			$req->wform->move(public_path('uploads/wforms/'),$wf_name);
					
		}


	  $arr = [
			"multiple_nat" => $req->multiple_nat,
			"nats" => $req->nats,
			"green_card" => $req->green_card, 
			"tax_resi" => $req->tax_resi, 
			"for_citi" => $req->for_citi,
			"co_ma" => $req->co_ma,
			"co_mp" => $req->co_mp,
			"attor" => $req->attor,
			"attor_addr" => $req->attor_addr,	  
			"trans_fund" => $req->trans_fund,
			"wf" => $req->wf,
			"wform" => $wf_name,
			"wform_options" =>  $req->wform_options,
			"customer_id" => $req->customer_id,
			"created_at" => now(),
			"updated_at" => now(),
  ];

	  $inserted = DB::table('fatca_details')->insert($arr);
	  return response()->json(200);
  }

  public function generate_form(Request $req)
  {
  
	$arr = [
	"form_id" => $req->channel.substr(time(),6),
	"channel" => $req->channel,
	"status" => 0, 
	"customer_id" => $req->customer_id,
	"user_id" => $req->user_id,
	"assigned_to" => 0,
	"created_at" => now(),
	"updated_at" => now(),
	];	
	
	$inserted = DB::table('forms')->insert($arr);
	return $inserted ? 200 : 500 ;
			
			

  }
  public function send_mail(Request $req){
  
  			$mail_data = [
			'customer_name' => $req->customer_name,
			'cnic' => $req->cnic,
			"qq" => $req->qq,
			"email" => $req->email
			];
			
			$send = '';
			
			$sales_email = \DB::table('users')->select('email')->where('id',$req->user_id)->first();
			
			//dd($sales_email);

	
			$recipients = [$sales_email,$req->email];
			foreach ($recipients as $recipient) {
			$send = Mail::to($recipient)->send(new Welcome($mail_data));
				if (empty($send)) {
			 		\DB::table('email_activities')->insert([
			 		'status' => 'success',
			 		'msg' => 'mail has been sent successfully',
			 		'action' => 'form creation',
			 		'created_at' => now()
			 		]);
	
			 	} else {			 		
	
			 		\DB::table('email_activities')->insert([
			 		'status' => 'fail',
			 		'msg' => 'mail not sent',
			 		'action' => 'form creation',
			 		'created_at' => now()
			 		]);	
			 	}
			}

			$recipients = \DB::table('users')->select('email')->where('role_id',3)->get();
			foreach ($recipients as $recipient) {
			$send = Mail::to($recipient)->send(new Welcome($mail_data));
				if (empty($send)) {
			 		\DB::table('email_activities')->insert([
			 		'status' => 'success',
			 		'msg' => 'mail has been sent successfully',
			 		'action' => 'form creation',
			 		'created_at' => now()
			 		]);	
			 	} else {			 		
	
			 		\DB::table('email_activities')->insert([
			 		'status' => 'fail',
			 		'msg' => 'mail not sent',
			 		'action' => 'form creation',
			 		'created_at' => now()
			 		]);
			 	}
			}

}
}
