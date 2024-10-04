<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Admin;
use App\Models\TransactionHistory;
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
        if($validator->fails()) {
            return redirect()->route('login_page')->with('error',$validator->errors()->first());
        }
       
             $login = DB::table('admins')
           ->where('terminal_id', $request->terminal_id)
           ->where('password', $request->password)
           ->first();
       
       if($login){ 
          $request->session()->put('id', $login->id);
           $request->session()->put('role_id', $login->role_id); 
           Session::put('Auth_id', $login->id);
           $role = Session::get('role_id');
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
    $request->validate([
        'role_id' => 'required|integer', 
        'logged_in_role_id' => 'required|integer', 
        'auth' => 'required|string',
    ]);
     $created_by = $request->input('auth');
    $terminals = []; 
    if ($request->logged_in_role_id == 2 && $request->role_id == 3) {
        $terminals = DB::table('admins')
            ->where('id', auth()->user()->id) 
            ->pluck('terminal_id'); 
    } 
    elseif ($request->logged_in_role_id == 2 && $request->role_id == 4) {
               $terminalsfirst = DB::table('admins')
              ->where('created_by', $created_by) 
              ->where('role_id', 3) 
              ->select('terminal_id');
          // Dusri query
          $terminalssecond = DB::table('admins')
              ->where('id', $created_by) // Jo id hai usko match karna
              ->select('terminal_id');
          $terminals = $terminalsfirst->union($terminalssecond)->pluck('terminal_id');
       }
        elseif ($request->logged_in_role_id == 3 && $request->role_id == 4){
               $terminals = DB::table('admins')
              ->where('id', $created_by) 
              ->pluck('terminal_id');
       }
    elseif ($request->logged_in_role_id == 1) {
        if ($request->role_id == 2) {
            $terminals = DB::table('admins')
                ->where('role_id', 1)
                ->pluck('terminal_id');
        } elseif ($request->role_id == 3) {
            $terminals = DB::table('admins')
                ->where('role_id', 2)
                ->pluck('terminal_id');
        } elseif ($request->role_id == 4) {
            $terminals = DB::table('admins')
                ->where('role_id', 3)
                ->pluck('terminal_id');
        }
    }
    return response()->json($terminals); // Terminals ko JSON response ke through bhejte hain
}

public function store(Request $request)
{
  
    // Validate the request
    $request->validate([
       'terminal_id' => 'required|string|unique:admins,terminal_id|min:6|max:14',
        'password' => 'required|string|min:8',
        'role_id' => 'required|integer',
        'under_role_terminal_id' => 'required|string', // Ye line ab update hui hai, selected terminal id
        'createdby' => 'required|', // Ye line ab update hui hai,login prson id
    ]);
    
        $terminalid = $request->under_role_terminal_id;
        $data = DB::table('admins')->where('terminal_id', $terminalid)->first();
        $ids = $data->id;
        $role_id = $data->role_id;
        $insidestokist =  $data->inside_stockist;
        $insidesubstokist =$data->inside_substockist;
        
        if($role_id == 2){            // yaha uski role_id chech ho rhai hai jiske andar create kiya ja raha hai 
            $insidestokist = $ids;
            $insidesubstokist =null;
        }
        elseif($role_id == 3)
        {
            $insidestokist = $insidestokist;
            $insidesubstokist =$ids;
        }else{
            $insidestokist= null;
            $insidesubstokist = null;
        }
        
       // dd($key);
    // Create a new admin entry
    $admin = new Admin();
    $admin->terminal_id = $request->terminal_id;
    $admin->password =$request->password; 
    $admin->role_id = $request->role_id;
    $admin->created_by = $request->createdby;
    $admin->inside_stockist = $insidestokist;
    $admin->inside_substockist = $insidesubstokist;
  
    // Save the admin entry
    $admin->save();

    // Return a success message
    return redirect()->back()->with('success', 'Role added successfully!');
}

public function editRole($id)
{
    // Role ko fetch karne ke liye $id se related data laate hain
    $authid = Session::get('Auth_id');
    $roles = DB::table('admins')->select('role_id')->distinct()->get();
    $creator_ids = DB::table('admins')->select('terminal_id')->where('id', $authid)->first();
    
    $role = DB::table('admins')->select('role_id')->where('id', $authid)->first();

    $roleToEdit = DB::table('admins')->where('id', $id)->first();
    $created_inside = $roleToEdit->created_inside;
    $creator_id = DB::table('admins')->select('terminal_id')->where('id', $created_inside)->first();
    
    if($roleToEdit->role_id ==3)
    {
         $inside_stockistid = DB::table('admins')->select('inside_stockist')->where('id', $id)->first();
         $inside_stockist_value = $inside_stockistid->inside_stockist;
         $insideid = DB::table('admins')->select('terminal_id')->where('id', $inside_stockist_value)->first();
         
        
    }elseif($roleToEdit->role_id == 4)
    {
        $inside_stockistid = DB::table('admins')->select('inside_substockist')->where('id', $id)->first();
        $inside_substockist_value = $inside_stockistid->inside_substockist;
        $insideid = DB::table('admins')->select('terminal_id')->where('id', $inside_substockist_value)->first();
    }else{
         $insideid = DB::table('admins')->select('terminal_id')->where('id', 1)->first();
    }
    
    return view('admin.editusers')
    ->with('roleToEdit', $roleToEdit)
    ->with('authid', $authid)
    ->with('roles', $roles)
    ->with('role', $role)
    ->with('insideid',$insideid);
}

   public function update(Request $request, $id)
   {
    // Validate the request
   $request->validate([
    'terminal_id' => 'required', 
    'password' => 'required', 
    'role_id' => 'required|integer',
    'under_role_terminal_id' => 'required|string', 
    'createdby' => 'required', 
   ]);
   
    $creator_id = DB::table('admins')->where('terminal_id', $request->under_role_terminal_id)->value('id'); 
    $admin = Admin::findOrFail($id);
    $admin->terminal_id = $request->terminal_id;
    $admin->password = $request->password;
    $admin->role_id = $request->role_id;
    $admin->created_inside = $creator_id; 
    $admin->created_by = $request->createdby;
    $admin->save();
    return redirect()->back()->with('success', 'Role updated successfully!');
   }  
  
  
  public function stokistlist(Request $request) {
    $authid = Session::get('Auth_id');  
    $role_id = Session::get('role_id');  
   $admins = DB::table('admins');
   $admins = DB::table('admins');
   if ($role_id == 2){
       $admins = $admins->where('inside_stockist', $authid);
   }
   $admins = $admins->get();
    return view('admin.stokist')
        ->with('admins', $admins)
        ->with('authid', $authid)
        ->with('roles', $role_id);
}

public function getSubstockists($stockistId)
{
    $substockists = DB::table('admins')
                      ->where('inside_stockist', $stockistId)
                      ->where('role_id', 3) // Role ID for Substockist
                      ->get(['id', 'terminal_id']); // Select ID and terminal_id
    return response()->json($substockists);
}

public function getUsers($substockistId)
{
    $users = DB::table('admins')
               ->where('inside_substockist', $substockistId)
               ->where('role_id', 4) // Role ID for Users
               ->get(['id', 'terminal_id', 'role_id', 'password', 'wallet', 'day_wallet', 'today_add_money', 'created_at', 'status']); // Select required fields

    return response()->json($users);
}

// AdminController.php
public function getUserByTerminal($terminalId)
{
    $user = Admin::where('terminal_id', $terminalId)->first(); // Find user by terminal_id

    if ($user) {
        return response()->json($user); // Return user data as JSON
    } else {
        return response()->json(null); // Return null if user not found
    }
}


public function getTableData(Request $request)
{
    $query = DB::table('admins');

    if ($request->stockist_id) {
        $query->where('inside_stockist', $request->stockist_id);
    }

    if ($request->substockist_id) {
        $query->where('inside_substockist', $request->substockist_id);
    }

    $data = $query->get(['id', 'role_id', 'terminal_id', 'password', 'wallet', 'day_wallet', 'today_add_money', 'created_at', 'status']);

    return response()->json($data);
}



public function updateStatus(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'status' => 'required|boolean',
    ]);

    // Find the admin by ID and update the status
    $admin = Admin::findOrFail($id);
    $admin->status = $request->status;
    $admin->save();

    return redirect()->back()->with('message', 'Status updated successfully!');
}
public function destroy($id)
{
    // Find the admin by ID and delete it
    $admin = Admin::findOrFail($id);
    $admin->delete();

    return redirect()->back()->with('message', 'Admin deleted successfully!');
} 

public function addwallet(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'authid' => 'required',
        'operation' => 'required|string|in:add,deduct',
    ]);

    // Find the admin by ID
    $auth  = $request->authid;
    $roles = DB::table('admins')->select('role_id')->where('id', $auth)->first();
    $admin = Admin::findOrFail($id);

    // Check if operation is 'add' or 'deduct'
    if ($request->operation == 'add') {
        // Add amount to wallet
        $admin->wallet += $request->amount;
        $admin->today_add_money += $request->amount;
        $message = 'Amount added to wallet successfully.';
        $operation = 1;
    } elseif ($request->operation == 'deduct') {
        // Deduct amount from wallet if there is enough balance
        if ($admin->wallet >= $request->amount) {
            $admin->wallet -= $request->amount;
            $admin->today_add_money -= $request->amount;
            $message = 'Amount deducted from wallet successfully.';
            $operation = 2;
        } else {
            // Insufficient balance
            return redirect()->back()->with('error', 'Insufficient wallet balance. Cannot deduct the requested amount.');
        }
    }

    // Record the transaction
    $transaction = new TransactionHistory();
    $transaction->user_id = $id;
    $transaction->transaction_perform_by = $auth;
    $transaction->amount = $request->amount;
    $transaction->result1add2deduct = $operation;
    $transaction->created_at = now(); // Use Laravel's built-in time helper
    $transaction->updated_at = now();
    $transaction->save();

    // Save the updated wallet balance
    $admin->save();

    // Redirect back with success message
    return redirect()->back()->with('success', $message);
}

     public function history($id)
    {
         $transactions = DB::table('admins')
        ->join('TransactionHistory', 'admins.id', '=', 'TransactionHistory.user_id')
        ->select('admins.id as admin_id', 'admins.role_id as role','admins.terminal_id as terminal_id', 'TransactionHistory.id as transaction_id', 'TransactionHistory.amount as transamount','TransactionHistory.result1add2deduct as description','TransactionHistory.created_at as transtime') // Use aliases to avoid conflicts
        ->where('admins.id', $id) // Filter by admin ID
        ->get();
        return view('admin.transactions', compact('transactions'));
    }
}





















