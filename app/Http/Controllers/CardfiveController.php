<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CardfiveController extends Controller
{
   
    public function admin_prediction(Request $request){
          $result_time = $request->result_time;
          $number = $request->number;
          $prediction_insert = DB::table('admin_results')->insert(['card_number'=>$number,'result_time'=>$result_time]);
          
          if($prediction_insert){
              return redirect()->back()->with('success','Result Inserted Successfully');
          }else{
              return redirect()->back()->with('error','Result Inserted Successfully');
          }
          
    }
    
    
    public function result_history(){
         $perPage = 10;
        $result_history = DB::table('results')->orderBy('id','desc')->paginate($perPage);;
        return view('admin.resulthistory')->with('results',$result_history);
    }
    
    public function bethistory(){
         $perPage = 10;
        $result_history = DB::table('bets')->orderBy('id','desc')->paginate($perPage);
        return view('admin.bethistory')->with('results',$result_history);
    }
    
    
}