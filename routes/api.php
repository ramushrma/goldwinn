<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PublicController;
use App\Http\Controllers\api\BetController;
use App\Http\Controllers\api\ResultController;
use App\Http\Controllers\api\ReportController;
use App\Http\Controllers\api\CroneController;
use App\Http\Controllers\api\PdfController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[PublicController::class,'login']);
Route::get('/profile/{id}',[PublicController::class,'profile']);
Route::post('/change_password',[PublicController::class,'change_password']);

Route::get('/result',[ResultController::class,'result']);
Route::post('/result_datewise',[ResultController::class,'result_datewise']);

// in barcode section
Route::get('/result_history/{status}',[ResultController::class,'result_history']);


Route::post('/bet',[BetController::class,'bet']);
Route::post('/cancel_bet',[BetController::class,'cancel_bet']);
Route::post('/claim_bet',[BetController::class,'claim_bet']);
Route::get('/all_claim_bet',[BetController::class,'all_claim_bet']);
Route::get('/fetch', [BetController::class, 'fetch_data']);


Route::post('/report',[ReportController::class,'report']);

Route::get('/result_declare',[CroneController::class,'result_declare']);
Route::get('/update_bet_logs',[CroneController::class,'update_bet_logs']);

Route::get('/bet_pdf/{id}',[PdfController::class,'bet_pdf']);
Route::get('/status_pdf/{id}/{status}',[PdfController::class,'status_pdf']);





