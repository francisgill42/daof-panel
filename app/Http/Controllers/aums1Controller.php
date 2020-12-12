<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class aums1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.aums');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    

        $args = ["title" => $request->title,"f1" => $request->f1,];
        $success = \DB::table('aums1')->insert($args);
        if($success){
            return redirect('aums1');
        }
        else{
            return view('admin');
        }
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
    public function edit()
    {
        $results = \DB::table('aums1')->where('id','=', 1 )->get();
        $charts = \DB::table('aums_1_chart')->get();
        return view('admin.aums-edit',["results"=>$results,'charts'=>$charts]);
    }

    public function edit_aums1_chart($id)
    {
        $ch_fields = \DB::table('aums_1_chart')->where('id','=',$id)->get();
        return view('admin.aums-1-chart-edit',['ch_fields'=>$ch_fields]);
    }

    public function update_aums1_chart(Request $request)
    {
        $success = \DB::table('aums_1_chart')->where('id','=',$request->id)->update([
            'aums_1_key'=>$request->aums_1_key,
            'aums_1_value'=>$request->aums_1_value
            ]);  
        if($success){
        return back()->with('msg','Record has been updated');  
        }
        else{
        return back()->with('err','Oops! something went wrong!!');  
        }

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
        $results = \DB::table('aums1')->where('id','=', 1 )->update([ 
            "title" => $request->title,"f1" => $request->f1,
            ]);
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
