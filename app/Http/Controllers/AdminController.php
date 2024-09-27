<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    
    public function login_page(){
        return view('admin.login');
    }
    
    public function login(Request $request){
        
         $validator = Validator::make($request->all(), [
            'terminal_id' => 'required',
            'password' => 'required'
        ]);
		   
        $validator->stopOnFirstFailure();

        if ($validator->fails()) {
            return redirect()->route('login_page')->with('error',$validator->errors()->first());
        }
        
        $login = DB::table('admins')->where('terminal_id',$request->terminal_id)->where('password',$request->password)->first();
        if($login){
            $request->session()->put('id', $login->id);
            Session::put('Auth_id', $login->id);
            return redirect()->route('admin.dashboard');
              
              
        }else{
            return redirect()->route('login_page')->with('error','Invalid Credentials');
        }
    }
    
        public function dashboard(){
        return view('admin.index');
    }
    
    
     public function logout(Request $request){
         $request->session()->forget('id');
        return redirect()->route('login_page');
    }
    
    
         public function cardfive(Request $request){
           return view('prediction.12card5');
          }
    
     public function password(){
           return view('admin.password');
         }
         
    public function update_password(Request $request){
          $validator = Validator::make($request->all(), [
            'terminal_id' => 'required',
            'password' => 'required',
            'new_password'=>'required'
        ]);
        $validator->stopOnFirstFailure();
        if ($validator->fails()) {
            return redirect()->route('admin.password')->with('error',$validator->errors()->first());
        }
         $update_pass = DB::table('admins')->where('id',1)->where('terminal_id',$request->terminal_id)->where('password',$request->password)->first();
        if($update_pass){
         $update_pass = DB::table('admins')->where('id',1)->where('terminal_id',$request->terminal_id)->update([
             'password'=>$request->new_password
             ]);
             if($update_pass){
                 return redirect()->back()->with('success','Password updated successfully.');
             }else{
                  return redirect()->route('admin.password')->with('error','Failed to update password!');
             }
        }else{
            return redirect()->route('admin.password')->with('error','Invalid Credentials');
        }
         }
         
         
        public function wallet(){
            $wallet = DB::table('admins')->where('id',1)->value('wallet');
            $wallet_history = DB::table('add_money')->orderBy('id','desc')->get();
           return view('admin.addmoney')->with('wallet',$wallet)->with('wallet_history',$wallet_history);
         }
         
         public function add_money(Request $request){
          $insert =  DB::table('add_money')->insert(['amount'=>$request->amount]);
          $wallet_update = DB::table('admins')->where('id',1)->update([
              'wallet'=>DB::raw("wallet + $request->amount"),
              'today_add_money'=>DB::raw("today_add_money + $request->amount"),
              ]);
                if($wallet_update && $insert){
                    return redirect()->back()->with('success','Money added successfully.');
                }else{
                    return redirect()->back()->with('error','Something went wrong!');
                }          
            }
   public function createRole()
    {
        // Fetch all roles from the admins table
        $authid = Session::get('Auth_id');
        $roles = DB::table('admins')->select('role_id')->distinct()->get();
        $creator_id = DB::table('admins')->select('terminal_id')->where('id', $authid)->first(); // Use first() to get a single record
        $role = DB::table('admins')->select('role_id')->where('id', $authid)->first(); // Use first() to get a single record
        return view('admin.createrole', compact('authid','roles','role','creator_id'));
    }

 public function getTerminalsByRole(Request $request)
{
    // Validate the request
    $request->validate([
        'role_id' => 'required|integer',
    ]);
   
    $terminals = [];

    // Check role_id and fetch terminals accordingly
    if ($request->role_id == 2) {
        // If Stockist is selected, fetch Admin terminals (role_id = 1)
        $terminals = DB::table('admins')
            ->where('role_id', 1)
            ->pluck('terminal_id');
    } elseif ($request->role_id == 3) {
        // If SubStockist is selected, fetch Stockist terminals (role_id = 2)
        $terminals = DB::table('admins')
            ->where('role_id', 2)
            ->pluck('terminal_id');
    } elseif ($request->role_id == 4) {
        // If User is selected, fetch Stockist and SubStockist terminals (role_id = 2, 3)
        $terminals = DB::table('admins')
            ->whereIn('role_id', [1, 2, 3])
            ->pluck('terminal_id');
    }

    return response()->json($terminals);
}

public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'terminal_id' => 'required|string|unique:admins,terminal_id', // Ensure terminal_id is unique in the 'admins' table
        'password' => 'required|string',
        'role_id' => 'required|integer',
        'under_role_terminal_id' => 'required|string', // Ye line ab update hui hai
        'createdby' => 'required|', // Ye line ab update hui hai
    ]);

    // Get the creator's ID based on under_role_terminal_id
    $creator_id = DB::table('admins')
        ->where('terminal_id', $request->under_role_terminal_id)
        ->value('id'); 

    // Create a new admin entry
    $admin = new Admin();
    $admin->terminal_id = $request->terminal_id;
    $admin->password =$request->password; // Password ko hash karna zaroori hai
    $admin->role_id = $request->role_id;
    $admin->created_inside = $creator_id; // Save the selected creator ID
    $admin->created_by = $request->createdby; // Save the selected creator ID
  
    // Save the admin entry
    $admin->save();

    // Return a success message
    return redirect()->back()->with('success', 'Role added successfully!');
}



}