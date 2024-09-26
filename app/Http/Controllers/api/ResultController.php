<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use DateTime;



class ResultController extends Controller
{
    public function result(){
            date_default_timezone_set('Asia/Kolkata');
            //$datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
            
       $result = DB::table('results')->select('card_number')->whereDate('created_at',$newdate)->get();
    
       if($result->isNotEmpty()){
          return response()->json(['status'=>200,'message'=>'Bet results found..','result'=>$result]); 
       }else{
           return response()->json(['status'=>400,'message'=>'No record found!','result'=>[]]);
       }
    }
    
            
            public function result_history(?string $status = null) {
                
             date_default_timezone_set('Asia/Kolkata');
             $date = date('Y-m-d');

            /*
                status - 1 - cancelled
                         3 - unclaimed
                         4 - claimed
                         5 - current (all type bet record of current date for a particular game type)
                         6 - rest all - (all type game bet history)
            */
        
            if ($status === null) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Status is required',
                    'data'=>[]
                ]);
            }
        
            $status_array = [];
            if ($status == 1 || $status == 3 || $status == 4) {
                $status_array[] = $status;
            } else if ($status == 5) {
                $status_array = [0, 1, 2, 3, 4];
            } else if ($status == 6) {
                $status_array = [0, 1, 2, 3, 4]; // or any other statuses you want to include
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid status value',
                    'data'=>[]
                ]);
            }
          
          $bet_history = DB::table('bets')->select('id','result_time','game_name','barcode_number','total_points','status')->whereDate('created_at', $date)->whereIn('status', $status_array)->orderByDesc('id')->get();
            
            
         /*    $bet_history = $bet_history->map(function ($bet) {
                $status_messages = [
                    0 => ['status' => 1, 'status_message' => 'pending'],
                    1 => ['status' => 1, 'status_message' => 'cancel'],
                    2 => ['status' => 2, 'status_message' => 'loss'],
                    3 => ['status' => 3, 'status_message' => 'unclaimed'],
                    4 => ['status' => 4, 'status_message' => 'claimed'],
                ];
        
                $dateTime = new DateTime($bet->result_time);
                $bet->date = $dateTime->format('Y-m-d');
                $bet->time = $dateTime->format('H:i:s');
        
                $bet->status = $status_messages[$bet->status]['status'];
                $bet->status_message = $status_messages[$bet->status]['status_message'];
        
                unset($bet->result_time);
                return $bet;
            });           */


           if($bet_history->isNotEmpty()){
            return response()->json([
                'status' => 200,
                'message' =>'pending - 0 , cancel - 1 , loss - 2 , unclaimed - 3, claimed - 4 , current - 5,rest-all - 6 , status value to hit api',
                'data' => $bet_history
            ]);
            
           }else{
               return response()->json(['status'=>400,'message'=>'No record found!','data'=>[]]);
           }
           
        }
        
            public function result_datewise(Request $request){
                
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
            
            $validator = Validator::make($request->all(), [
              'date' => ['required', 'date_format:Y-m-d'],
            ]);
        
            $validator->stopOnFirstFailure();
        
            if($validator->fails()){
                return response()->json(['status' => 400, 'message' => $validator->errors()->first(),'result_history'=>[]]);
            }
            
            $date = $request->date;
            
            //$result_history = DB::table('results')->whereDate('created_at',$date)->get();
            
            $result_history = DB::table('results')
                ->leftJoin('card_infos', 'results.card_number', '=', 'card_infos.id')
                ->whereDate('results.created_at', $date)
                ->select('results.game_name as scheme_name','results.XB as xb','results.created_at as datetime','card_infos.card_name as result') // Adjust the selected columns as needed
                ->get();

            
            if($result_history->isNotEmpty()){
                return response()->json(['status'=>200,'message'=>'Found result for current date','result_history'=>$result_history]);
            }else{
                return response()->json(['status'=>400,'message'=>'No record found!','result_history'=>[]]);
            }
            
            
            }
        
        

    
}