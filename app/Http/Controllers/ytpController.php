<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ytpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.ytp');
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
        
        $args = [
            "title" => $request->title,
            "sh" => $request->sh,
            "f1" => $request->f1,
            "f2" => $request->f2,
            "f3" => $request->f3,
            "f4" => $request->f4,
            "f5" => $request->f5,
        ];
        $success = \DB::table('ytp')->insert($args);
        if($success){
            return redirect('ytp');
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
        $results = \DB::table('ytp')->where('id','=', 1 )->get();
        return view('admin.ytp-edit',["results"=>$results]);
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
        $results = \DB::table('ytp')->where('id','=', 1 )->update([ 
            "title" => $request->title,
            "sh" => $request->sh,
            "f1" => $request->f1,
            "f2" => $request->f2,
            "f3" => $request->f3,
            "f4" => $request->f4,
            "f5" => $request->f5,
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
