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

class BetController extends Controller
{
    
               protected $generatedSequences = [];
            
            protected function generateBarCodeNumber($useFullYear = false, $useResultAnnouncementTime = false,$formattedTime=null) {
              
                $yearFormat = $useFullYear ? 'Ymd' : 'ymd';
                $currentDate = date($yearFormat);
    
                 $currentTime = time();
                 
                //$periodStart = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
                //$resultAnnouncementTime = date('Hi00', $periodStart + 300);
        
                $timePart = $useResultAnnouncementTime ? $formattedTime : date('His', $currentTime);
            
                $barcode_number = $currentDate . $timePart;
            
                do {
                    $unique_sequence = '';
                    $digits = '0123456789';
                    for ($i = 0; $i < 3; $i++) {
                        $unique_sequence .= $digits[rand(0, strlen($digits) - 1)];
                    }
                } while (in_array($unique_sequence, $this->generatedSequences));
            
                $this->generatedSequences[] = $unique_sequence;
                $barcode_number .= $unique_sequence;
            
                return $this->check_exist_barcodeNumber($barcode_number);
            }
            
            protected function check_exist_barcodeNumber($barcode_number){
                $check = DB::table('bets')->where('barcode_number', $barcode_number)->first();
                if ($check) {
                    return $this->generateBarCodeNumber(); // Call the function using $this
                } else {
                    return $barcode_number;
                }
            }


    
        public function bet(Request $request){
            
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d H:i:s');
            $newdate = date('Y-m-d');
            $currentTime = time();
            
            $status = 0;
            
            $validator = Validator::make($request->all(), [
              'time' => ['required', 'date_format:H:i:s'],
              'quantity' => 'required|integer',
              'total_points' => 'required|integer',
              'game_name' => 'required|string',
              'bet_details' => 'required',
            ]);
        
            $validator->stopOnFirstFailure();
        
            if($validator->fails()){
                return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
            }
            
            
            $time = $request->time;
            $formattedTime = str_replace(":", "", $time);
            $result_announce_time = $newdate.' '.$time;
          
            //2024-07-06 12:59:04
           // dd($result_announce_time,$datetime);
           
          // $current_min_time = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
          // $current_max_time = date('Y-m-d H:i:00', $periodStart + 300);
              
             $bet_result_announce_time = strtotime($result_announce_time);
             $current_min_time = $currentTime - ($currentTime % 300);
             $current_announce_time = $current_min_time + 300;
           
               if($bet_result_announce_time<=$current_announce_time){
                   $status = 1;
               }
            
            $quantity = $request->quantity;
            $total_points = $request->total_points;
            $game_name = $request->game_name;
            $bet_detail = $request->bet_details;
            $bet_details = base64_decode($bet_detail);
            $bet_details_array = json_decode($bet_details);
            
           $barcode_with_last_two_digits = $this->generateBarCodeNumber(false, false,null);
           $barcode_with_full_year = $this->generateBarCodeNumber(true, true,$formattedTime);

            
            $barcode_number = $barcode_with_last_two_digits;
            $order_id =  $barcode_with_full_year;
            
         
            
            $wallet = DB::table('admins')->where('id',1)->value('wallet');
            
            if($wallet<$total_points){
                return response()->json(['status'=>400,'message'=>'Insufficient funds!']);
            }
        
            $update_wallet = DB::table('admins')->where('id',1)->update([
                'wallet'=>DB::raw("wallet - $total_points")
                ]);
                
            // $insert_bet = DB::table('bets')->insert([
            //     'result_time'=>$result_announce_time,
            //     'quantity'=>$quantity,
            //     'total_points'=>$total_points,
            //     'game_name' => $game_name,
            //     'bet_details'=>$bet_details,
            //     'created_at' =>$datetime,
            //     'treminal_id'=>7711010603,
            //     'barcode_number' =>$barcode_number,
            //     'order_id' =>$order_id
            //     ]);   
            
            
            
           $insert_bet = DB::table('bets')->insertGetId([
                    'result_time' => $result_announce_time,
                    'quantity' => $quantity,
                    'total_points' => $total_points,
                    'game_name' => $game_name,
                    'bet_details' => $bet_details,
                    'created_at' => $datetime,
                    'treminal_id' => 7711010603,
                    'barcode_number' => $barcode_number,
                    'bet_log_status'=>$status,
                    'order_id' => $order_id
                ]);
                
                if($status==1){
                foreach($bet_details_array as $item){
                    $point = $item->points;
                    $point_value = $point*50;
                    $card_number = $item->card_number;
                    
                     DB::table('bet_logs')->where('id',$card_number)->update([
                        'amount'=>DB::raw("amount + $point_value")
                        ]);
                }
                }
                
               // [{"points": "5","card_number": "3"}, {"points": "3","card_number": "2"}]
    
                if($insert_bet){
                    return response()->json(['status'=>200,'message'=>'Bet placed successfully.','id'=>$insert_bet]);
                }else{
                     return response()->json(['status'=>400,'message'=>'Failed to place bet!']);
                }
            
        }
        
        
        
            public function cancel_bet(Request $request){
                
                $validator = Validator::make($request->all(), [
        			'id' => 'required|exists:bets,id'
        		]);

	        	$validator->stopOnFirstFailure();
        
        		if ($validator->fails()) {
        			return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        		}
        		
        		$id = $request->id;
        		
        		$bet_details = DB::table('bets')->where('id',$id)->first();
        		
        		$status = $bet_details->status;
        		
        		if($status == 0){
        		    $total_points = $bet_details->total_points;
        		    
        		    $wallet_update =  DB::table('admins')->where('id',1)->update([
        		      'wallet'=>DB::raw("wallet + $total_points")
        		      ]);
        		    
        		    if($wallet_update){
        		        
        		    $change_status = DB::table('bets')->where('id',$id)->update([
        		       'status'=>1
        		       ]); 
        		        if($change_status){
        		            return response()->json(['status'=>200,'message'=>'Bet canceled successfully.']);
        		        }else{
        		             return response()->json(['status'=>400,'message'=>'Falied to update bet status!']);
        		        }
        		        
        		    }else{
        		        return response()->json(['status'=>400,'message'=>'Falied to update wallet!']);
        		    }
        		    
        		    
        		    
        		 }else if($status == 1){
        		     return response()->json(['status'=>400,'message'=>'Bet Alredy Cancelled!']);
        	   	}else if($status == 4){
        	   	     return response()->json(['status'=>400,'message'=>'Bet Alredy Claimed!']);
        	   	 }else{
        		     return response()->json(['status'=>400,'message'=>'Cancelation Time Is Over!']);
        		}
            }
            
            
             public function claim_bet(Request $request){
                 
                $validator = Validator::make($request->all(), [
        			'id' => 'required|exists:bets,id'
        		]);

	        	$validator->stopOnFirstFailure();
        
        		if ($validator->fails()) {
        			return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        		}
        		
        		$id = $request->id;
        		
        		$bet_details = DB::table('bets')->where('id',$id)->first();
        		
        		$status = $bet_details->status;
        		
        		
        		if($status == 3){
        		     $total_points = $bet_details->total_points;
        		     
        		     $wallet_update =  DB::table('admins')->where('id',1)->update([
        		      'wallet'=>DB::raw("wallet + $total_points")
        		      ]);
        		    
        		    if($wallet_update){
        		        
        		    $change_status = DB::table('bets')->where('id',$id)->where('status',3)->update([
        		       'status'=>4
        		       ]); 
        		        if($change_status){
        		            return response()->json(['status'=>200,'message'=>'Bet claimed successfully.']);
        		        }else{
        		             return response()->json(['status'=>400,'message'=>'Falied to update bet status!']);
        		        }
        		        
        		    }else{
        		        return response()->json(['status'=>400,'message'=>'Falied to update wallet!']);
        		    }
        		    
        		    
        		}else if($status == 1 || $status == 2){
        		    return response()->json(['status'=>400,'message'=>'Not a winning ticket!']);
        		}else if($status == 4){
        		    return response()->json(['status'=>400,'message'=>'This ticket has been claimed!']);
        		}else if($status == 0){
        		    return response()->json(['status'=>400,'message'=>'Result is pending!']);
        		}else{
        		   return response()->json(['status'=>400,'message'=>'Not a valid status!']);
        		}
        		
             }
             
              public function all_claim_bet(Request $request){
                   date_default_timezone_set('Asia/Kolkata');
                   $datetime = date('Y-m-d H:i:s');
                   $newdate = date('Y-m-d');
                  
                  $bet_details = DB::table('bets')->whereDate('created_at',$newdate)->where('status',3)->get();
                   
                   if($bet_details->isEmpty()){
                       return response()->json(['status'=>400,'message'=>'No Bet to claim!']);
                   }  
                
                foreach($bet_details as $item){
                    $id = $item->id;
                    $total_points = $item->total_points;
        		     
        		     $wallet_update =  DB::table('admins')->where('id',1)->update([
        		      'wallet'=>DB::raw("wallet + $total_points")
        		      ]);

        		    $change_status = DB::table('bets')->where('id',$id)->where('status',3)->update([
        		       'status'=>4
        		       ]); 

                }
                
                return response()->json(['status'=>200,'message'=>'All bet claimed successfully.']);
                
              }
             
             
              public function fetch_data(){
                  date_default_timezone_set('Asia/Kolkata');
                   $datetime = date('Y-m-d H:i:s');
                    $currentTime = time();
                    
             $periodStart = $currentTime - ($currentTime % 300); // 300 seconds = 5 minutes
             $resultAnnouncementTime = date('Y-m-d H:i:00', $periodStart + 300);
             //dd($resultAnnouncementTime);
               $bet_log = DB::table('bet_logs')->get();
               return response()->json(['status'=>200,'bet_log'=>$bet_log,'result_time'=>$resultAnnouncementTime]);
            }
             
             
             
    
    
}