<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tomfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tomf');
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
            "sh1" => $request->sh1,
            "sh1f1" => $request->sh1f1,
            "sh1f2" => $request->sh1f2,
            "sh2" => $request->sh2,
            "sh2f1" => $request->sh2f1,
            "sh2f2" => $request->sh2f2,
            "sh2f3" => $request->sh2f3,
            "sh2f4" => $request->sh2f4,
            "sh2f5" => $request->sh2f5,
            "sh2f6" => $request->sh2f6,
            "sh3" => $request->sh3,
            "sh3f1" => $request->sh3f1,
            "sh3f2" => $request->sh3f2,
            "sh3f3" => $request->sh3f3,
        ];
        $success = \DB::table('tomf')->insert($args);
        if($success){
            return redirect('tomf');
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
        $results = \DB::table('tomf')->where('id','=', 1 )->get();
        return view('admin.tomf-edit',["results"=>$results]);
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
        $results = \DB::table('tomf')->where('id','=', 1 )->update([ 
            "title" => $request->title,
            "sh1" => $request->sh1,
            "sh1f1" => $request->sh1f1,
            "sh1f2" => $request->sh1f2,
            "sh2" => $request->sh2,
            "sh2f1" => $request->sh2f1,
            "sh2f2" => $request->sh2f2,
            "sh2f3" => $request->sh2f3,
            "sh2f4" => $request->sh2f4,
            "sh2f5" => $request->sh2f5,
            "sh2f6" => $request->sh2f6,
            "sh3" => $request->sh3,
            "sh3f1" => $request->sh3f1,
            "sh3f2" => $request->sh3f2,
            "sh3f3" => $request->sh3f3,
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
