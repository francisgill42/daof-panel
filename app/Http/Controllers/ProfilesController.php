<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Risk;
use DB;

class ProfilesController extends Controller
{

public function __construct()
{
$this->middleware('auth');
}



public function profiles(){
$users = DB::table('risks')
->leftJoin('users', 'users.id', '=', 'risks.user_id')
->select('risks.*', 'users.name')
->get();
return view('admin.profile',['users' => $users]);
}


public function profile_single($id){
$user = DB::table('risks')->where('id', '=', $id )->get();
return view('admin.profile-single',['profiles' => $user]);
}


public function my_risk_profiles($id){
$my_risk_profiles = DB::table('risks')
->where('user_id', '=', $id)
->get();
return view('user.my_risk_profile',['my_risk_profiles' => $my_risk_profiles]);
}

public function my_risk_profile_single($id){
$data = DB::table('risks')
->where('id', '=', $id)
->get();
return view('user.my-risk-profile-single',['data' => $data]);
}



public function save_risk_profile(Request $r){


    $counts = \DB::table('rp_count')->pluck('counts');
    $res = $counts[0];
    if($res > 0){
        \DB::table('rp_count')->where('id','=',1)->update(['counts'=>$res+1]);
    }
    else{
        \DB::table('rp_count')->insert(['counts'=>$res++]);
    }

$args = [
"client_name" => $r->client_name,
"client_email" => $r->client_email,
"client_number" => $r->client_number,
"client_cnic" => $r->client_cnic,
"location" => $r->location,
"choosen_fund" => $r->choosen_fund,
"irf" => $r->irf,
"crf" => $r->crf,
"user_id" => $r->user_id,
"decision" => $r->decision,
"userScore" => array_sum([$r->q1,$r->q2,$r->q3,$r->q4,$r->q5,$r->q6,$r->q7,$r->q8]),
"created_at" => now(),
"updated_at" => now()
];
$success = DB::table('risks')->insert($args);

if($r->decision != 'Or Choose Another Product at My Own Risk'){

//islamic


if($r->crf != null || $r->irf != null ){



if($r->irf == 'Islamic Money Market Fund'){
return redirect('immf');
}


if($r->irf == 'Islamic Income Fund'){
return redirect('iif');
}

if($r->irf == 'Islamic Asset Allocation Fund'){
return redirect('iaaf');
}


if($r->irf == 'IFPF Conservative Plan'){
return redirect('ifpf');
}


if($r->irf == 'IFPF Active Allocation Plan'){
return redirect('ifpf');
}


if($r->irf == 'Islamic Stock Fund'){
return redirect('isf');
}



if($r->irf == 'Islamic Equity Fund'){
return redirect('ieqf');
}

}

    if($r->crf == 'Cash Fund'){
        return redirect('cf');
        }

        if($r->crf == 'Money Market Fund'){
        return redirect('mmf');
        }

        if($r->crf == 'Stock Market Fund'){
        return redirect('sf');
        }

        if($r->crf == 'Income Fund'){
        return redirect('if');
        }

        if($r->crf == 'Government Securities Fund'){
        return redirect('gsf');
        }

        if($r->crf == 'FPF Conservative Plan'){
        return redirect('fpf');
        }


        if($r->crf == 'Multi Asset Fund'){
        return redirect('maf');
        }

        if($r->crf == 'FPF Multi Allocation Plan'){
        return redirect('fpf');
        }


        if($r->crf == 'Energy Fund'){
        return redirect('en');
        }

        if($r->crf == 'Equity Fund'){
        return redirect('eqf');
        }


}

    return redirect('products');

}
}
