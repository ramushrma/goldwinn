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



class ReportController extends Controller
{
    
    public function report(Request $request){
        
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
            
            $validator = Validator::make($request->all(), [
              'from' => ['required', 'date_format:Y-m-d'],
              'to' =>['required', 'date_format:Y-m-d']
            ]);
        
            $validator->stopOnFirstFailure();
        
            if($validator->fails()){
                return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
            }

            $from = $request->from;
            $to = $request->to;
            
            //$report_data = DB::table('bets')->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)->get();
            
            $report_data = DB::table('bets')
                    ->select(
                        DB::raw('COALESCE(SUM(total_points), 0) as total_points'),
                        DB::raw('COALESCE(SUM(CASE WHEN status = 1 THEN total_points ELSE 0 END), 0) as cancel_points'),
                        DB::raw('COALESCE(SUM(CASE WHEN status = 4 THEN total_points ELSE 0 END), 0) as claimed_points')
                    )
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to)
                    ->first();

                
                 $play_points = $report_data->total_points;
                 $cancel_points = $report_data->cancel_points;
                 $claimed_points = $report_data->claimed_points;
               
                 $net_play_points = $play_points - $cancel_points;
                 $opt_play_points = $net_play_points - $claimed_points;
                
                 $discounted_points = 0;
                 $gross_points = $opt_play_points +  $discounted_points;
                 $bonus_points = 0;
                 $gift_points = 0;
                 $net_pay_points = $gross_points;
               
               $current_report = DB::table('admins')->where('id',1)->first();
               
               if(!$current_report){
                   $open_points = 0;
                   $current_points = 0;
               }
               
                 $open_points = $current_report->day_wallet;
                 $current_points = $current_report->wallet;
                 
                  $add_points =0;
                  $total_points = $open_points + $add_points;
                   $used_points = $current_points - $open_points;
                  
                
             
            $report = [
                'from'=>$from,
                'to'=>$to,
                'play_points'=>$play_points,
                'cancel_points'=>$cancel_points,
                'net_play_points'=>$net_play_points,
                'claim_points'=>$claimed_points,
                'opt_play_points'=>$opt_play_points,
                'discount_points'=>$discounted_points,
                'gross_points'=>$gross_points,
                'bonus_points'=>$bonus_points,
                'gift_points'=>$gift_points,
                'net_pay_points'=>$net_pay_points,
                'open_points'=>$open_points,
                'add_points'=>$add_points,
                'total_points'=>$total_points,
                'used_points'=>$used_points,
                'current_points'=>$current_points
                ];
          
        
            
            if($report_data){
            return response()->json(['status'=>200,'message'=>'Record found.','report'=>$report]);
            }else{
                return response()->json(['status'=>400,'message'=>'No record found!','report'=>[]]);
            }
        
    }
    
}