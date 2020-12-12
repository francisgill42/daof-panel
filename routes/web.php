<?php

use Illuminate\Http\Request;

Route::post('/ptc','FormController@push_to_crm');

Route::get('/empty/{table_name}',function($table_name){


    if(\Schema::hasTable($table_name)){
        \DB::table($table_name)->truncate();
        return redirect('/');
    }
    else{
        return 'table not found';
    }

});

Route::get('/test','TestController@test');

Route::post('/assign/{id}','FormController@assign');
Route::post('/send_again/{id}','Fields@send_again');
Route::post('/send_back/{id}','Fields@corrections');
Route::resource('/fields','Fields');

Route::get('cbc/{id}/edit','FormController@cbc_edit');

Route::post('cbc_done/{id}','FormController@cbc_done');

Route::post('send_to_cbc/{id}','FormController@send_to_cbc');

Route::post('preview','FormController@preview');
Route::resource('forms','FormController');

Route::get('/generate_leads','LeadsController@generate_leads');

Route::view('/wipe', 'user.push')->middleware('auth');



Route::view('wipe/{id}','user.push');

// sponser charts
Route::get('/edit-bank-names/{id}','sponsersController@edit_bank_names');
Route::post('/edit-bank-names','sponsersController@update_bank_names');
Route::post('/update_yr_chart','sponsersController@update_yr_chart');

// fund
Route::get('/edit-chart-field/{id}','FundsController@edit_chart_field');
Route::post('/edit_chart_field','FundsController@update_chart_field');

Route::get('/funds','FundsController@index');
Route::get('/add_fund_data/{id}','FundsController@add_fund_data');
Route::get('/add_fund1_data/{id}','FundsController@add_fund1_data');
Route::post('/fabs','FundsController@fabsStore');

Route::get('/edit-features_benefits/{id}','FundsController@edit_features_benefits');
Route::post('/edit_features_benefits','FundsController@update_features_benefits');

Route::post('/add_asset_allocation','FundsController@add_asset_allocation');
Route::get('/edit_asset_allocation/{id}','FundsController@edit_asset_allocation');
Route::post('/edit_asset_allocation','FundsController@update_asset_allocation');
Route::get('/delete_asset_allocation/{id}','FundsController@delete_asset_allocation');


Route::post('/add_investment_avenues','FundsController@add_investment_avenues');
Route::get('/edit_investment_avenues/{id}','FundsController@edit_investment_avenues');
Route::post('/edit_investment_avenues','FundsController@update_investment_avenues');
Route::get('/delete_investment_avenues/{id}','FundsController@delete_investment_avenues');



// fund 1
Route::get('/edit-fab/{id}','FundsController@edit_fab');
Route::post('/edit_fab','FundsController@update_fab');

Route::post('/add_aa1','FundsController@add_aa1');
Route::get('/edit_aa1/{id}','FundsController@edit_aa1');
Route::post('/edit_aa1','FundsController@update_aa1');
Route::get('/delete_aa1/{id}','FundsController@delete_aa1');

Route::post('/add_ia','FundsController@add_ia');
Route::get('/edit_ia/{id}','FundsController@edit_ia');
Route::post('/edit_ia','FundsController@update_ia');
Route::get('/delete_ia/{id}','FundsController@delete_ia');

Route::post('/add_fr1','FundsController@add_fr1');
Route::get('/edit_fr1/{id}','FundsController@edit_fr1');
Route::post('/edit_fr1','FundsController@update_fr1');
Route::get('/delete_fr1/{id}','FundsController@delete_fr1');

Route::get('/edit_fab_data/{id}','FundsController@edit_fab_data');
Route::post('/edit_fab_data','FundsController@update_fab_data');
Route::post('/edit_fi_data','FundsController@update_fi_data');
Route::post('/edit_fi1_data','FundsController@update_fi1_data');
Route::post('/edit_fr_data','FundsController@update_fr_data');
Route::post('/edit_ia_data','FundsController@update_ia_data');
Route::post('/edit_exp_data','FundsController@update_exp_data');
Route::post('/edit_chart_data','FundsController@update_chart_data');
Route::get('/welcome','WelcomeController@index')->middleware('auth');


Route::get('/edit-sc-chart/{id}','sponsersController@edit_sc_chart');
Route::post('/edit-sc-chart','sponsersController@update_sc_chart');

Route::get('/edit-scf/{id}','sponsersController@edit_scf');
Route::post('/edit-scf','sponsersController@update_scf');

Route::get('/edit-aums1-chart/{id}','aums1Controller@edit_aums1_chart');
Route::post('/edit-aums1-chart','aums1Controller@update_aums1_chart');


Route::get('/edit-aums2-chart/{id}','aums2Controller@edit_aums2_chart');
Route::post('/edit-aums2-chart','aums2Controller@update_aums2_chart');




Route::get('/edit_cap_data/{id}','FundsController@edit_cap_data');
Route::post('/edit_cap_data','FundsController@update_cap_data');

Route::get('/edit_aap_data/{id}','FundsController@edit_aap_data');
Route::post('/edit_aap_data','FundsController@update_aap_data');

Route::get('/edit_sap_data/{id}','FundsController@edit_sap_data');
Route::post('/edit_sap_data','FundsController@update_sap_data');



Route::get('/edit_mmsf_data/{id}','FundsController@edit_mmsf_data');
Route::post('/edit_mmsf_data','FundsController@update_mmsf_data');

Route::get('/edit_dsf_data/{id}','FundsController@edit_dsf_data');
Route::post('/edit_dsf_data','FundsController@update_dsf_data');

Route::get('/edit_esf_data/{id}','FundsController@edit_esf_data');
Route::post('/edit_esf_data','FundsController@update_esf_data');




Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::post('/save_risk_profile','ProfilesController@save_risk_profile');

Route::view('/user-dashboard','user');

Route::view('/products','frontend.product');


Route::get('/cf','FundsController@frontend_cf');
Route::get('/maf','FundsController@frontend_maf');
Route::get('/iaaf','FundsController@frontend_iaaf');
Route::get('/immf','FundsController@frontend_immf');
Route::get('/iif','FundsController@frontend_iif');
Route::get('/fpf','FundsController@frontend_fpf');
Route::get('/ifpf','FundsController@frontend_ifpf');
Route::get('/gsf','FundsController@frontend_gsf');
Route::get('/if','FundsController@frontend_if');
Route::get('/mmf','FundsController@frontend_mmf');
Route::get('/pf','FundsController@frontend_pf');
Route::get('/ipf','FundsController@frontend_ipf');
Route::get('/sf','FundsController@frontend_sf');
Route::get('/eqf','FundsController@frontend_eqf');
Route::get('/ieqf','FundsController@frontend_ieqf');
Route::get('/isf','FundsController@frontend_isf');
Route::get('/enf','FundsController@frontend_enf');



/*
|--------------------------------------------------------------------------
| Routes for frontend End
|--------------------------------------------------------------------------
|
*/



/*
|--------------------------------------------------------------------------
| Routes for Admin
|--------------------------------------------------------------------------
|
*/


Route::get('/admin', 'HomeController@index')->name('home');

Route::view('/home','/admin');

Route::get('/users','UsersController@users');
Route::get('/add-user','UsersController@addusersform');
Route::post('/add-user','UsersController@addusers');

Route::get('/user_edit/{id}','UsersController@edit');
Route::post('/update','UsersController@update');

Route::get('/user_delete/{id}','UsersController@delete');

Route::get('/risk-profiles','ProfilesController@profiles');
Route::get('/risk_profile/{id}','ProfilesController@profile_single');


Route::get('/sponsers', 'sponsersController@index');
Route::get('/awards', 'awardsController@index');
Route::get('/hamls', 'hamlsController@index');

Route::get('/aums1', 'aums1Controller@index');
Route::get('/aums2', 'aums2Controller@index');
Route::get('/ytp', 'ytpController@index');

Route::get('/mf', 'mfController@index');
Route::get('/tomf', 'tomfController@index');
Route::get('/iimf', 'iimfController@index');


Route::post('/sponsers', 'sponsersController@store');
Route::post('/awards', 'awardsController@store');
Route::post('/hamls', 'hamlsController@store');

Route::post('/aums1', 'aums1Controller@store');
Route::post('/aums2', 'aums2Controller@store');
Route::post('/ytp', 'ytpController@store');

Route::post('/mf', 'mfController@store');
Route::post('/tomf', 'tomfController@store');
Route::post('/iimf', 'iimfController@store');





Route::get('/welcome-edit', 'WelcomeController@slide_edit');
Route::post('/welcome-edit', 'WelcomeController@slide_update');
Route::get('/sponsers-edit', 'sponsersController@edit');
Route::get('/awards-edit', 'awardsController@edit');
Route::get('/hamls-edit', 'hamlsController@edit');

Route::get('/aums1-edit', 'aums1Controller@edit');
Route::get('/aums2-edit', 'aums2Controller@edit');
Route::get('/ytp-edit', 'ytpController@edit');

Route::get('/mf-edit', 'mfController@edit');
Route::get('/tomf-edit', 'tomfController@edit');
Route::get('/iimf-edit', 'iimfController@edit');



Route::post('/sponsers-edit', 'sponsersController@update');
Route::post('/awards-edit', 'awardsController@update');
Route::post('/hamls-edit', 'hamlsController@update');

Route::post('/aums1-edit', 'aums1Controller@update');
Route::post('/aums2-edit', 'aums2Controller@update');
Route::post('/ytp-edit', 'ytpController@update');

Route::post('/mf-edit', 'mfController@update');
Route::post('/tomf-edit', 'tomfController@update');
Route::post('/iimf-edit', 'iimfController@update');



/*
|--------------------------------------------------------------------------
| Routes for Admin End
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
| Routes for Funds
|--------------------------------------------------------------------------
|
*/

Route::get('/add-fund','FundsController@fund_form');
Route::post('/add-fund','FundsController@store');

Route::get('/fund_edit/{id}','FundsController@edit');
Route::post('/fund_update','FundsController@update');



/*
|--------------------------------------------------------------------------
| Routes for Funds End
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
| Routes for User Dashboard
|--------------------------------------------------------------------------
|
*/

Route::get('/my-risk-profiles/{id}','ProfilesController@my_risk_profiles');
Route::get('/my-risk-profile-single/{id}','ProfilesController@my_risk_profile_single');


/*
|--------------------------------------------------------------------------
| Routes for User Dashboard End
|--------------------------------------------------------------------------
|
*/



/*
|--------------------------------------------------------------------------
| Routes for Fund Categories
|--------------------------------------------------------------------------
|
*/


Route::view('/fi_mmf','frontend.funds.fi_mmf');

Route::view('/ef','frontend.funds.ef');

Route::view('/aaf','frontend.funds.aaf');

Route::view('/fof','frontend.funds.fof');

Route::view('/bf','frontend.funds.bf');

Route::view('/pfs','frontend.funds.pf_cat');

/*
|--------------------------------------------------------------------------
| Routes for Fund Categories End
|--------------------------------------------------------------------------
|
*/





Route::get('make-controller', function () {
    Artisan::call('make:controller', [
        'name' => 'RiskProfileController',
    ]);

    echo 'controller Created.';
});
