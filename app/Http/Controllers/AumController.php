<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AumController extends Controller
{
        public function list(){
        return view('admin.slides',[
            'images' => DB::table('slides')->where('id', '=', 12 )->get(),
            ]);
        }

        public function form(){
            return view('admin.slides-form');
        }


        public function save(Request $r){    
            //dd($r->all());
        $slides = DB::table('aums')
        ->insert([
        "b1" => $r->b1,
        "b2" => $r->b2,
        "b3" => $r->b3,
        "b4" => $r->b4,
        "b5" => $r->b5,
        "b6" => $r->b6,
        "l1" => $r->l1,
        "l2" => $r->l2,
        "l3" => $r->l3,
        "l4" => $r->l4,
        ]);
        if($slides){
        return redirect('slides');
        }
        else{
        return view('admin');
        }
        
        }

        public function edit($id){    
        	$slides = DB::table('slides')->where('id', '=', $id )->get();
            return view('admin.slides-single',['slides' => $slides]);
            
        }

        public function update(Request $r){ 
        // dd($r->all());   
        $slides = DB::table('slides')
        ->where('id', '=', $r->id )
        ->update(['title' => $r->title,'content' => $r->content,]);
        if($slides){
        return redirect('slides');
        }
        else{
        return view('admin');
        }
        
        }
            public function delete($id){ 
                $slides = DB::table('slides')->where('id', '=', $id )->delete();


                if($slides){
                    return redirect('slides');
                    }
                    else{
                    return view('admin');
                    }

                
                }
}
