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



class PdfController extends Controller
{
    public function bet_pdf(? string $id){
        //$bet_data = DB::table('bets')->orderBy('id','desc')->first();
        $bet_data = DB::table('bets')->where('id',$id)->first();
       // dd($bet_data);
        $order_id = $bet_data->order_id;
        $treminal_id = $bet_data->treminal_id;
        $result_time = $bet_data->result_time;
        $game_name = $bet_data->game_name;
        $quantity = $bet_data->quantity;
        $total_points = $bet_data->total_points;
        $bet_time = $bet_data->created_at;
        $barcode_number = $bet_data->barcode_number;
        
        $bet_details = json_decode($bet_data->bet_details);
        //dd($bet_details);
         $card_info = [
             '1'=>'JC',
             '2'=>'JD',
             '3'=>'JS',
             '4'=>'JH',
             '5'=>'QC',
             '6'=>'QD',
             '7'=>'QS',
             '8'=>'QH',
             '9'=>'KC',
             '10'=>'KD',
             '11'=>'KS',
             '12'=>'KH'
         ];
           $bet_details_array = [];
        foreach($bet_details as $item){
            $points = $item->points;
            $card_number = $item->card_number;
            
            if(array_key_exists($card_number,$card_info)){
                $card_name = $card_info[$card_number];
                $bet_details_array[]=[
                    'points'=>$points,
                    'card_name'=>$card_name
                    ];
            }
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'Bet details for pdf generation.',
            'order_id' =>$order_id,
            'treminal_id' =>$treminal_id,
            'result_time' => $result_time,
            'game_name' => $game_name,
            'quantity' => $quantity,
            'total_points' => $total_points,
            'bet_time' => $bet_time,
            'barcode_number' =>$barcode_number,
            'bet_details_array' =>$bet_details_array
            ]);

    }
    
    public function status_pdf(? string $id,? string $status){
        
          $bet_data = DB::table('bets')->where('id',$id)->where('status',$status)->first();
        if(!$bet_data){
            return response()->json(['status'=>400,'message'=>'No record found!']);
        }
        $order_id = $bet_data->order_id;
        $treminal_id = $bet_data->treminal_id;
        $result_time = $bet_data->result_time;
        $game_name = $bet_data->game_name;
        $quantity = $bet_data->quantity;
        $total_points = $bet_data->total_points;
        $bet_time = $bet_data->created_at;
        $barcode_number = $bet_data->barcode_number;
        
        $bet_details = json_decode($bet_data->bet_details);
        //dd($bet_details);
         $card_info = [
             '1'=>'JC',
             '2'=>'JD',
             '3'=>'JS',
             '4'=>'JH',
             '5'=>'QC',
             '6'=>'QD',
             '7'=>'QS',
             '8'=>'QH',
             '9'=>'KC',
             '10'=>'KD',
             '11'=>'KS',
             '12'=>'KH'
         ];
           $bet_details_array = [];
        foreach($bet_details as $item){
            $points = $item->points;
            $card_number = $item->card_number;
            
            if(array_key_exists($card_number,$card_info)){
                $card_name = $card_info[$card_number];
                $bet_details_array[]=[
                    'points'=>$points,
                    'card_name'=>$card_name
                    ];
            }
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'PDF details for corresponding status!',
            'order_id' =>$order_id,
            'treminal_id' =>$treminal_id,
            'result_time' => $result_time,
            'game_name' => $game_name,
            'quantity' => $quantity,
            'total_points' => $total_points,
            'bet_time' => $bet_time,
            'barcode_number' =>$barcode_number,
            'bet_details_array' =>$bet_details_array
            ]);
    }
    
    
    
    
    
}