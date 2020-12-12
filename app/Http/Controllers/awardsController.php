<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class awardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.awards');
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
         //dd($request->all());
         $args = [
            "title" => $request->title, 
            "f1_bold" => $request->f1_bold,
            "f1_normal" => $request->f1_normal,
            "f2_bold" => $request->f2_bold,
            "f2_normal" => $request->f2_normal,
            "f3_bold" => $request->f3_bold,
            "f3_normal" => $request->f3_normal,
            "f4_bold" => $request->f4_bold,
            "f4_normal" => $request->f4_normal,
            "f5_bold" => $request->f5_bold,
            "f5_normal" => $request->f5_normal,
            "f6_bold" => $request->f6_bold,
            "f6_normal" => $request->f6_normal,
            "f7_bold" => $request->f7_bold,
            "f7_normal" => $request->f7_normal,
            "f8_bold" => $request->f8_bold,
            "f8_normal" => $request->f8_normal,
            "f9_bold" => $request->f9_bold,
            "f9_normal" => $request->f9_normal,
            "f10_bold" => $request->f10_bold,
            "f10_normal" => $request->f10_normal,
           
            ];

            $success =  \DB::table('awards')->insert($args);
            if($success){
                return redirect('awards');
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
        $results = \DB::table('awards')->where('id','=', 1 )->get();
        return view('admin.awards-edit',["results"=>$results]);
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
        $results = \DB::table('awards')->where('id','=', 1 )->update([ 
            "title" => $request->title, 
            "f1_bold" => $request->f1_bold,
            "f1_normal" => $request->f1_normal,
            "f2_bold" => $request->f2_bold,
            "f2_normal" => $request->f2_normal,
            "f3_bold" => $request->f3_bold,
            "f3_normal" => $request->f3_normal,
            "f4_bold" => $request->f4_bold,
            "f4_normal" => $request->f4_normal,
            "f5_bold" => $request->f5_bold,
            "f5_normal" => $request->f5_normal,
            "f6_bold" => $request->f6_bold,
            "f6_normal" => $request->f6_normal,
            "f7_bold" => $request->f7_bold,
            "f7_normal" => $request->f7_normal,
            "f8_bold" => $request->f8_bold,
            "f8_normal" => $request->f8_normal,
            "f9_bold" => $request->f9_bold,
            "f9_normal" => $request->f9_normal,
            "f10_bold" => $request->f10_bold,
            "f10_normal" => $request->f10_normal,
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
