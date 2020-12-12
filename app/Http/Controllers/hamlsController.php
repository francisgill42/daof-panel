<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class hamlsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.hamls');
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
            "title"=>$request->title,
            "b1" => $request->b1,
            "b2" => $request->b2,
            "b3" => $request->b3,
            "b4" => $request->b4,
            "b5" => $request->b5,
            "b6" => $request->b6,
            "f1" => $request->f1,
            "f2" => $request->f2,
            "f3" => $request->f3,
            "f4" => $request->f4,
        ];
        $success = \DB::table('hamls')
                      ->insert($args);
                if($success){
                return redirect('hamls');
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
        $results = \DB::table('hamls')->where('id','=', 1 )->get();
        return view('admin.hamls-edit',["results"=>$results]);
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
       // dd($request->all());
        $results = \DB::table('hamls')->where('id','=', 1 )->update([ 
            "title"=>$request->title,
            "b1" => nl2br($request->b1),
            "b2" => nl2br($request->b2),
            "b3" => nl2br($request->b3),
            "b4" => nl2br($request->b4),
            "b5" => nl2br($request->b5),
            "b6" => nl2br($request->b6),
            "f1" => $request->f1,
            "f2" => $request->f2,
            "f3" => $request->f3,
            "f4" => $request->f4,
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
