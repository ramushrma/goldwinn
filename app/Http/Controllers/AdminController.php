<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        
        $login = DB::table('admins')->where('id',1)->where('terminal_id',$request->terminal_id)->where('password',$request->password)->first();
        if($login){
            $request->session()->put('id', $login->id);
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
        $terminalIds = DB::table('admins')->pluck('terminal_id')->toArray();
        return view('admin.createrole', compact('terminalIds'));
    }

    public function getTerminalsByRole(Request $request)
    {
        // Validate role_id in the request
        $request->validate([
            'role_id' => 'required|integer',
        ]);
        // Fetch terminal_ids based on role_id from the admins table
        $terminals = DB::table('admins')
            ->where('role_id', $request->role_id)
            ->pluck('terminal_id');
        // Return the terminal IDs as JSON response
        return response()->json($terminals);
    }


}