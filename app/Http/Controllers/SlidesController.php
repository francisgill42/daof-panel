<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SlidesController extends Controller
{

    
        public function list(){
        return view('admin.slides',[
            'slides' => DB::table('slides')->get(),
                        ]);
        }

        public function form(){
            return view('admin.slides-form');
        }

        public function save(Request $r){    
        $slides = DB::table('slides')
        ->insert(['title' => $r->title,'content' => $r->content,]);
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


                public function aum_list(){
                    return view('admin.aums',['aums' => DB::table('aums')->get(),]);
                    }

              
                public function aum_form(){
                    return view('admin.aum-form');
                }


                public function aum_save(Request $r){    
                    //dd($r->all());
                $slides = DB::table('aums')
                ->insert(["title"=>$r->title,"b1" => $r->b1,"b2" => $r->b2,"b3" => $r->b3,"b4" => $r->b4,"b5" => $r->b5,"b6" => $r->b6,
                "l1" => $r->l1,"l2" => $r->l2,"l3" => $r->l3,"l4" => $r->l4,]);
                if($slides){
                return redirect('aum-slide');
                }
                else{
                return view('admin');
                }
                
                }

                public function aum_edit($id){    
                    $aums = DB::table('aums')->where('id', '=', $id )->get();
                    return view('admin.aum-form-edit',['aums' => $aums]);
                    
                }
        
                public function aum_update(Request $r){ 
                // dd($r->all());   
                $aums = DB::table('aums')
                ->where('id', '=', $r->id )
                ->update(["title"=>$r->title,"b1" => $r->b1,"b2" => $r->b2,"b3" => $r->b3,"b4" => $r->b4,"b5" => $r->b5,"b6" => $r->b6,
                "l1" => $r->l1,"l2" => $r->l2,"l3" => $r->l3,"l4" => $r->l4,]);
                if($aums){
                return redirect('aums');
                }
                else{
                return view('admin');
                }
                
                }

                public function aum_delete($id){ 
                    $slides = DB::table('aums')->where('id', '=', $id )->delete();
    
    
                    if($slides){
                        return redirect('aums');
                        }
                        else{
                        return view('admin');
                        }
    
                    
                    }
        
        







}
