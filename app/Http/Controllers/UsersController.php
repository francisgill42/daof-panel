<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use DB;



class UsersController extends Controller
{
public function __construct()
{
$this->middleware('auth');
}


public function addusersform(){
$roles = DB::table('roles')->get();
return view('admin.add-user',['roles' => $roles]);
}


public function users(){


if(Auth::user()->role->role == 'super_admin'){

$users = DB::table('users')
->leftJoin('roles', 'roles.id', '=', 'users.role_id')
->select('users.*', 'roles.role_title')
->orderBy('id','desc')
->paginate(5);
return view('admin.user',['users' => $users]);
}
if(Auth::user()->role->role == 'sales'){

return redirect('welcome');
}

}
public function addusers(Request $r){

    $this->validate($r,[
        'name' => 'required|unique:users|min:6|max:20',
        'email' => 'required|unique:users|min:6|max:255',
        'password' => 'required|min:6',
        ]);

$args = [
'name' => $r->name,
'email' => $r->email,
'password' => bcrypt($r->password),
'role_id' => $r->role,
];

$success = User::create($args);
if($success){
return redirect('users');
}


}

public function edit($id){

$user = DB::table('users')->where('id', '=', $id )->get();
return view('admin.user-edit',['user' => $user]);

}
public function update(Request $r){

if($r->password == ''){
$p =  $r->old_password;
}
else{
$p =  bcrypt($r->password);
}

$user = DB::table('users')
->where('id', '=', $r->id )
->update([
'name'=> $r->name,
'email'=> $r->email,
'password'=> $p,
'role_id'=>$r->role_id]);

return redirect('users');


}

public function delete($id){
$slides = DB::table('users')
->where('id', '=', $id )
->delete();
if($slides){
return redirect('users');
}
else{
return view('admin');
}
}


}
