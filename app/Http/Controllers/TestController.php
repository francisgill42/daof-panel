<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use App\Mail\Test;
use App\Mail\Forms;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(\DB::table('funds')->get());        
    return view('admin.funds_list',['all'=> \DB::table('funds')->get()]);
    }

    public function test(){

        
        return Mail::to('francisgill1000@gmail.com')  
            ->send(new Test());

//            return (new Test());
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function TestCreate()
    {
        return view('admin.test-form');
    }

    public function fabsCreate()
    {
        return view('admin.funds.fab-form',['funds'=>\DB::table('funds')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function TestStore(Request $request)
    {
        \DB::table('funds')->insert(['title'=>nl2br($request->title)]);
        return back();   
    }

    public function fabsStore(Request $request)
    {
        \DB::table('fabs')->insert(['title'=>$request->title,'fund_id'=> $request->fund_id  ]);
        return back();   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $args = [
            "title" => $request->title,
            "fab" =>$request->fab,
             'fi_k_1'=>$request->fi_k_1,
            'fi_v_1'=>$request->fi_v_1,
            'fi_k_2'=>$request->fi_k_2,
            'fi_v_2'=>$request->fi_v_2,
            'fi_k_3'=>$request->fi_k_3,
            'fi_v_3'=>$request->fi_v_3,
            'fi_k_4'=>$request->fi_k_4,
            'fi_v_4'=>$request->fi_v_4,
            'fi_k_5'=>$request->fi_k_5,
            'fi_v_5'=>$request->fi_v_5,
            'aa_k_1'=>$request->aa_k_1,
            'aa_v_1'=>$request->aa_v_1,
            'aa_k_2'=>$request->aa_k_2,
            'aa_v_2'=>$request->aa_v_2,
            'aa_k_3'=>$request->aa_k_3,
            'aa_v_3'=>$request->aa_v_3,
            'aa_k_4'=>$request->aa_k_4,
            'aa_v_4'=>$request->aa_v_4,
            'aa_k_5'=>$request->aa_k_5,
            'aa_v_5'=>$request->aa_v_5,
        ];
        
        
        \DB::table('funds')->where('id','=', $request->id )->update($args);
        return back();  
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
