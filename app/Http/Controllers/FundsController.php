<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FundsController extends Controller
{
/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */

public function __construct()
{
$this->middleware('auth');
}
public function index()
{
//dd(\DB::table('funds')->get());
return view('admin.funds_list',['all'=> \DB::table('funds')->paginate(5)]);
}





public function frontend_cf()
{
    return view('frontend.cf',
    [
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',10)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',10)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',10)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',10)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',10)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',10)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',10)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',10)->get(),
    ]
    );
}
public function frontend_immf()
{

return view('frontend.immf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',11)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',11)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',11)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',11)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',11)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',11)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',11)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',11)->get(),
    ]
);
}

public function frontend_iif()
{

return view('frontend.iif',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',12)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',12)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',12)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',12)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',12)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',12)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',12)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',12)->get(),
    ]
);
}



public function frontend_mmf()
{
return view('frontend.mmf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',13)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',13)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',13)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',13)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',13)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',13)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',13)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',13)->get(),
    ]
);
}


public function frontend_if()
{
return view('frontend.if',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',14)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',14)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',14)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',14)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',14)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',14)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',14)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',14)->get(),
    ]
);
}


public function frontend_gsf()
{
return view('frontend.gsf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',15)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',15)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',15)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',15)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',15)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',15)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',15)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',15)->get(),
    ]
);
}



public function frontend_sf()
{
return view('frontend.sf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',16)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',16)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',16)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',16)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',16)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',16)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',16)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',16)->get(),
    ]
);
}
public function frontend_eqf()
{
return view('frontend.eqf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',17)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',17)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',17)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',17)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',17)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',17)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',17)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',17)->get(),
    ]
);
}

public function frontend_ieqf()
{
    return view('frontend.ieqf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',18)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',18)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',18)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',18)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',18)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',18)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',18)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',18)->get(),
    ]
);
}



public function frontend_isf()
{
return view('frontend.isf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',19)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',19)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',19)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',19)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',19)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',19)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',19)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',19)->get(),
    ]
);
}

public function frontend_enf()
{
return view('frontend.enf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',20)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',20)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',20)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',20)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',20)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',20)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',20)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',20)->get(),
    ]
);
}

public function frontend_iaaf()
{
return view('frontend.iaaf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',21)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',21)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',21)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',21)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',21)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',21)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',21)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',21)->get(),
    ]
);
}
public function frontend_maf()
{
return view('frontend.maf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',22)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',22)->get(),
    'fi'=> \DB::table('fi')->where('fund_id', '=',22)->get(),
    'aa'=> \DB::table('asset_allocation')->where('fund_id', '=',22)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',22)->get(),
    'fr'=>\DB::table('fr')->where('fund_id', '=',22)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',22)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',22)->get(),
    ]
);
}

public function frontend_ifpf()
{
return view('frontend.ifpf',
[
'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',23)->get(),
'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',23)->get(),
'fi1'=> \DB::table('fi1')->where('fund_id', '=',23)->get(),
'aa1'=> \DB::table('aa1')->where('fund_id', '=',23)->get(),
'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',23)->get(),
'fr'=> \DB::table('fr')->where('fund_id', '=',23)->get(),
'exp'=> \DB::table('exp')->where('fund_id', '=',23)->get(),
'charts' => \DB::table('charts')->where('fund_id', '=',23)->get(),

]
);
}
public function frontend_fpf()
{
return view('frontend.fpf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',24)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',24)->get(),
    'fi1'=> \DB::table('fi1')->where('fund_id', '=',24)->get(),
    'aa1'=> \DB::table('aa1')->where('fund_id', '=',24)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',24)->get(),
    'fr'=> \DB::table('fr')->where('fund_id', '=',24)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',24)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',24)->get(),

    ]
);
}

public function frontend_pf()
{
return view('frontend.pf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',25)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',25)->get(),
    'fi1'=> \DB::table('fi1')->where('fund_id', '=',25)->get(),
    'aa1'=> \DB::table('aa1')->where('fund_id', '=',25)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',25)->get(),
    'fr1'=> \DB::table('fr1')->where('fund_id', '=',25)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',25)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',25)->get(),

    ]
);
}

public function frontend_ipf()
{
return view('frontend.ipf',
[
    'fund_title'=> \DB::table('funds')->select('title')->where('id', '=',26)->get(),
    'fabs'=> \DB::table('features_benefits')->where('fund_id', '=',26)->get(),
    'fi1'=> \DB::table('fi1')->where('fund_id', '=',26)->get(),
    'aa1'=> \DB::table('aa1')->where('fund_id', '=',26)->get(),
    'ia'=> \DB::table('investment_avenues')->where('fund_id', '=',26)->get(),
    'fr1'=> \DB::table('fr1')->where('fund_id', '=',26)->get(),
    'exp'=> \DB::table('exp')->where('fund_id', '=',26)->get(),
    'charts' => \DB::table('charts')->where('fund_id', '=',26)->get(),

    ]
);

}
/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/

public function add_fund_data($id)
{
return view('admin.funds.add_fund_data',[
'funds'=>\DB::table('funds')->where('id','=',$id)->get(),
'features_benefits'=>\DB::table('features_benefits')->where('fund_id','=',$id)->get(),
'asset_allocation'=>\DB::table('asset_allocation')->where('fund_id','=',$id)->get(),
'fi'=>\DB::table('fi')->where('fund_id','=',$id)->get(),
'investment_avenues'=>\DB::table('investment_avenues')->where('fund_id','=',$id)->get(),
'fr'=>\DB::table('fr')->where('fund_id','=',$id)->get(),
'exp'=>\DB::table('exp')->where('fund_id','=',$id)->get(),
'charts'=>\DB::table('charts')->where('fund_id', '=',$id)->get(),

]);
}
/////////////////////////
public function edit_cap_data($id)
{
return view('admin.funds.edit_cap',[
'caps'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}

public function update_cap_data(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}



public function edit_aap_data($id)
{
return view('admin.funds.edit_aap',[
'caps'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}

public function update_aap_data(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}

public function edit_sap_data($id)
{
return view('admin.funds.edit_sap',[
'caps'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}

public function update_sap_data(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}




////////////////////////


public function edit_mmsf_data($id)
{
return view('admin.funds.edit_mmsf',[
'caps'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}

public function update_mmsf_data(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}

public function edit_dsf_data($id)
{
return view('admin.funds.edit_dsf',[
'caps'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}

public function update_dsf_data(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}

public function edit_esf_data($id)
{
return view('admin.funds.edit_esf',[
'caps'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}

public function update_esf_data(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}



///////////////////////////

public function edit_chart_field($id)
{
return view('admin.funds.edit-fund',[
'ch_fields'=>\DB::table('charts')->where('id','=',$id)->get(),
]);
}


public function update_chart_field(Request $request)
{
$success = \DB::table('charts')->where('id','=', $request->id)->update(
[
'year'=> $request->year,
'val'=> $request->val,
'ret'=> $request->ret,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops! something went wrong!!');
}
}




public function add_fund1_data($id)
{
return view('admin.funds.add_fund1_data',[
'funds'=>\DB::table('funds')->where('id','=',$id)->get(),
'features_benefits'=>\DB::table('features_benefits')->where('fund_id','=',$id)->get(),
'investment_avenues'=>\DB::table('investment_avenues')->where('fund_id','=',$id)->get(),
'asset_allocation'=>\DB::table('aa1')->where('fund_id','=',$id)->get(),
'fi1'=>\DB::table('fi1')->where('fund_id','=',$id)->get(),
'fr1'=>\DB::table('fr1')->where('fund_id','=',$id)->get(),
'exp'=>\DB::table('exp')->where('fund_id','=',$id)->get(),
'charts'=>\DB::table('charts')->where('fund_id','=',$id)->get(),

]);
}




public function edit_features_benefits(Request $request)
{

    return view('admin.funds.edit-features-benefits',['features_benefits'=>\DB::table('features_benefits')->where('id','=',$request->id)->get()]);

}


public function update_features_benefits(Request $request)
{
    $success = \DB::table('features_benefits')->where('id','=',$request->id)->update([
            'fb_bullets_rt'=>$request->fb_bullets_rt,
            'fb_bullets_lt'=>$request->fb_bullets_lt
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}






public function add_asset_allocation(Request $request)
{
    //dd($request->all());
    $success = \DB::table('asset_allocation')->insert([
            'aa_keys'=>$request->aa_keys,
            'aa_values'=>$request->aa_values,
            'fund_id'=>$request->fund_id
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}
public function edit_asset_allocation(Request $request)
{

    return view('admin.funds.edit-asset-allocation',['asset_allocation'=>\DB::table('asset_allocation')->where('id','=',$request->id)->get()]);

}


public function update_asset_allocation(Request $request)
{
    $success = \DB::table('asset_allocation')->where('id','=',$request->id)->update([
            'aa_keys'=>$request->aa_keys,
            'aa_values'=>$request->aa_values
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}

public function delete_asset_allocation($id)
{
$success = \DB::table('asset_allocation')
->where('id', '=', $id )
->delete();
if($success){
return back();
}
else{
return view('admin');
}

}

public function add_investment_avenues(Request $request)
{
    //dd($request->all());
    $success = \DB::table('investment_avenues')->insert([
            'ai_key'=>$request->ai_key,
            'fund_id'=>$request->fund_id
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}
public function edit_investment_avenues(Request $request)
{

    return view('admin.funds.edit-investment-avenues',[
        'investment_avenues'=>\DB::table('investment_avenues')->where('id','=',$request->id)->get()
        ]);

}
public function update_investment_avenues(Request $request)
{

    //dd($request->all());
    $success = \DB::table('investment_avenues')->where('id','=',$request->id)->update([
            'ai_key'=>$request->ai_key,
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}

public function delete_investment_avenues($id)
{
   // dd();
$success = \DB::table('investment_avenues')
->where('id', '=', $id )
->delete();
if($success){
return back();
}
else{
return view('admin');
}

}



public function edit_fab(Request $request)
{

    return view('admin.funds.edit-fab',['features_benefits'=>\DB::table('features_benefits')->where('id','=',$request->id)->get()]);

}


public function update_fab(Request $request)
{
    $success = \DB::table('features_benefits')->where('id','=',$request->id)->update([
            'fb_bullets_rt'=>$request->fb_bullets_rt,
            'fb_bullets_lt'=>$request->fb_bullets_lt
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}

public function add_aa1(Request $request)
{
    //dd($request->all());
    $success = \DB::table('aa1')->insert([
            'aa1_key'=>$request->aa1_key,
            'aa1_v1'=>$request->aa1_v1,
            'aa1_v2'=>$request->aa1_v2,
            'aa1_v3'=>$request->aa1_v3,
            'fund_id'=>$request->fund_id
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}


public function edit_aa1(Request $request)
{

    return view('admin.funds.edit-aa1',[
        'aa1'=>\DB::table('aa1')->where('id','=',$request->id)->get()
        ]);

}


public function update_aa1(Request $request)
{
    $success = \DB::table('aa1')->where('id','=',$request->id)->update([
        'aa1_key'=>$request->aa1_key,
        'aa1_v1'=>$request->aa1_v1,
        'aa1_v2'=>$request->aa1_v2,
        'aa1_v3'=>$request->aa1_v3,
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}


public function delete_aa1($id)
{
$success = \DB::table('aa1')
->where('id', '=', $id )
->delete();
if($success){
return back();
}
else{
return view('admin');
}

}

public function add_ia(Request $request)
{
    //dd($request->all());
    $success = \DB::table('investment_avenues')->insert([
            'ai_key'=>$request->ai_key,
            'fund_id'=>$request->fund_id
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}

public function edit_ia(Request $request)
{

    return view('admin.funds.edit-ia',[
        'investment_avenues'=>\DB::table('investment_avenues')->where('id','=',$request->id)->get()
        ]);

}
public function update_ia(Request $request)
{

    //dd($request->all());
    $success = \DB::table('investment_avenues')->where('id','=',$request->id)->update([
            'ai_key'=>$request->ai_key,
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}

public function delete_ia($id)
{
   // dd();
$success = \DB::table('investment_avenues')
->where('id', '=', $id )
->delete();
if($success){
return back();
}
else{
return view('admin');
}

}




public function update_fi_data(Request $request)
{

// dd($request->all());

$success = \DB::table('fi')->where('fund_id','=', $request->fund_id )->update(
[
'fi_k_1'=> $request->fi_k_1,
'fi_v_1'=> $request->fi_v_1,
'fi_k_2'=> $request->fi_k_2,
'fi_v_2'=> $request->fi_v_2,
'fi_k_3'=> $request->fi_k_3,
'fi_v_3'=> $request->fi_v_3,
'fi_k_4'=> $request->fi_k_4,
'fi_v_4'=> $request->fi_v_4,
'fi_k_5'=> $request->fi_k_5,
'fi_v_5'=> $request->fi_v_5,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops something went wrong!!');
}
}

public function update_fi1_data(Request $request)
{

// dd($request->all());

$success = \DB::table('fi1')->where('fund_id','=', $request->fund_id )->update(
[
'fi_k1'=> $request->fi_k1,
'fi_k1v1'=> $request->fi_k1v1,
'fi_k1v2'=> $request->fi_k1v2,
'fi_k1v3'=> $request->fi_k1v3,
'fi_k2'=> $request->fi_k2,
'fi_k2v1'=> $request->fi_k2v1,
'fi_k2v2'=> $request->fi_k2v2,
'fi_k2v3'=> $request->fi_k2v3,
'fi_k3'=> $request->fi_k3,
'fi_k3v1'=> $request->fi_k3v1,
'fi_k3v2'=> $request->fi_k3v2,
'fi_k3v3'=> $request->fi_k3v3,
'fi_k4'=> $request->fi_k4,
'fi_k4v1'=> $request->fi_k4v1,
'fi_k4v2'=> $request->fi_k4v2,
'fi_k4v3'=> $request->fi_k4v3,
'fi_k5'=> $request->fi_k5,
'fi_k5v1'=> $request->fi_k5v1,
'fi_k5v2'=> $request->fi_k5v2,
'fi_k5v3'=> $request->fi_k5v3,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops something went wrong!!');
}
}



public function update_aa1_data(Request $request)
{

// dd($request->all());

$success = \DB::table('aa1')->where('fund_id','=', $request->fund_id )->update(
[
'fi_k1'=> $request->fi_k1,
'fi_k1v1'=> $request->fi_k1v1,
'fi_k1v2'=> $request->fi_k1v2,
'fi_k1v3'=> $request->fi_k1v3,
'fi_k2'=> $request->fi_k2,
'fi_k2v1'=> $request->fi_k2v1,
'fi_k2v2'=> $request->fi_k2v2,
'fi_k2v3'=> $request->fi_k2v3,
'fi_k3'=> $request->fi_k3,
'fi_k3v1'=> $request->fi_k3v1,
'fi_k3v2'=> $request->fi_k3v2,
'fi_k3v3'=> $request->fi_k3v3,
'fi_k4'=> $request->fi_k4,
'fi_k4v1'=> $request->fi_k4v1,
'fi_k4v2'=> $request->fi_k4v2,
'fi_k4v3'=> $request->fi_k4v3,
'fi_k5'=> $request->fi_k5,
'fi_k5v1'=> $request->fi_k5v1,
'fi_k5v2'=> $request->fi_k5v2,
'fi_k5v3'=> $request->fi_k5v3,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops something went wrong!!');
}
}

public function update_fr_data(Request $request)
{

// dd($request->all());

$success = \DB::table('fr')->where('fund_id','=', $request->fund_id )->update(
[
'k1'=> $request->k1,
'k1v1'=> $request->k1v1,
'k1v2'=> $request->k1v2,
'k1v3'=> $request->k1v3,
'k1v4'=> $request->k1v4,
'k2'=> $request->k2,
'k2v1'=> $request->k2v1,
'k2v2'=> $request->k2v2,
'k2v3'=> $request->k2v3,
'k2v4'=> $request->k2v4,
'k3'=> $request->k3,
'k3v1'=> $request->k3v1,
'k3v2'=> $request->k3v2,
'k3v3'=> $request->k3v3,
'k3v4'=> $request->k3v4,

]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops something went wrong!!');
}
}


public function add_fr1(Request $request)
{
    //dd($request->all());
    $success = \DB::table('fr1')->insert([
            'fr1_v1'=>$request->fr1_v1,
            'fr1_v2'=>$request->fr1_v2,
            'fr1_v3'=>$request->fr1_v3,
            'fr1_v4'=>$request->fr1_v4,
            'fund_id'=>$request->fund_id
            ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}
public function edit_fr1(Request $request)
{

    return view('admin.funds.edit-fr1',['fr1'=>\DB::table('fr1')->where('id','=',$request->id)->get()]);

}


public function update_fr1(Request $request)
{
    $success = \DB::table('fr1')->where('id','=',$request->id)->update([
        'fr1_v1'=>$request->fr1_v1,
        'fr1_v2'=>$request->fr1_v2,
        'fr1_v3'=>$request->fr1_v3,
        'fr1_v4'=>$request->fr1_v4,
                    ]);
        if($success){
        return back()->with('msg','Record has been updated');
        }
        else{
        return back()->with('err','Oops! something went wrong!!');
        }

}

public function delete_fr1($id)
{
$success = \DB::table('fr1')
->where('id', '=', $id )
->delete();
if($success){
return back();
}
else{
return view('admin');
}

}


public function update_exp_data(Request $request)
{

// dd($request->all());

$success = \DB::table('exp')->where('fund_id','=', $request->fund_id )->update(
[
'shd'=> $request->shd,
'title'=> $request->title,
'desc'=> $request->desc,
]);

if($success){
return back()->with('msg','Record has been updated');
}
else{
return back()->with('err','Oops something went wrong!!');
}
}


}
