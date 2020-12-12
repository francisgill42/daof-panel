<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class WelcomeController extends Controller
{
    
    function index(){

        $counts = \DB::table('views')->where('user_id',Auth::user()->id)->first(); 
        if($counts){
        $counts = \DB::table('views')->where('user_id',Auth::user()->id)->get();
        foreach($counts as $count){
        $counts = $count->count;
        }
        \DB::table('views')->where('user_id', '=',Auth::user()->id)->update(['count'=> $counts+1,'update_at'=> date('Y-m-d')]);
       
        }
        else{
        \DB::table('views')->insert(['count'=> $counts+1,'user_id' => Auth::user()->id]);
        }

        
        $users = \DB::table('users')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')            
        ->select('users.*', 'roles.role')
        ->get();
        
        return view('welcome',
        [
        'users' => $users,
        'welcome' => \DB::table('welcome')->where('id', '=', 1 )->get(),
        'sponsers' => \DB::table('sponsers')->where('id', '=', 1 )->get(),
        'awards' => \DB::table('awards')->where('id', '=', 1 )->get(),
        'hamls' => \DB::table('hamls')->where('id', '=', 1 )->get(),
        'aums1' => \DB::table('aums1')->where('id', '=', 1 )->get(),
        'aums2' => \DB::table('aums2')->where('id', '=', 1 )->get(),
        'ytp' => \DB::table('ytp')->where('id', '=', 1 )->get(),
        'mf' => \DB::table('mf')->where('id', '=', 1 )->get(),
        'tomf' => \DB::table('tomf')->where('id', '=', 1 )->get(),
        'iimf' => \DB::table('iimf')->where('id', '=', 1 )->get(),
        'aums1_chart' => \DB::table('aums_1_chart')->get(),
        'aums2_chart' => \DB::table('aums_2_chart')->get(),
        

        ]);

       
    
    }
    public function slide_edit(){

        return view('admin.welcome-edit',['results'=>\DB::table('welcome')->where('id','=',1)->get()]);
    }
    public function slide_update(Request $r){

        \DB::table('welcome')->where('id','=',$r->id)->update(['date'=>$r->date]);
        return back();
    }
}
