<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardfiveController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/wallet', function () {
    return view('wallet.index');
})->name('wallet.index');


Route::get('/fetch', [CardfiveController::class, 'fetch_data'])->name('fetch_data');
Route::post('/admin_prediction', [CardfiveController::class, 'admin_prediction'])->name('admin_prediction');
Route::get('/',[AdminController::class,'login_page'])->name('login_page');
Route::post('/login',[AdminController::class,'login'])->name('auth.login');
Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
Route::get('/result_history',[CardfiveController::class, 'result_history'])->name('result_history');
Route::get('/logout',[AdminController::class,'logout'])->name('logout');
Route::get('/12card5',[AdminController::class,'cardfive'])->name('prediction.12card5');
Route::get('/bethistory',[CardfiveController::class,'bethistory'])->name('admin.bethistory');
Route::get('/password',[AdminController::class,'password'])->name('admin.password');
Route::post('/update_password',[AdminController::class,'update_password'])->name('update_password');
Route::get('/wallet',[AdminController::class,'wallet'])->name('admin.addmoney');
Route::post('/add_money',[AdminController::class,'add_money'])->name('add_money');

// 26/09/2024 


Route::get('/createrole', [AdminController::class, 'createRole'])->name('createRole');
Route::post('/get-terminals', [AdminController::class, 'getTerminalsByRole'])->name('getTerminals');
Route::post('/store', [AdminController::class, 'store'])->name('store');
route::get('{id}/edit', [AdminController::class, 'editRole']);
Route::any('/stokist', [AdminController::class, 'stokistlist'])->name('stokistlist');
Route::put('/admins/{id}/status', [AdminController::class, 'updateStatus'])->name('admins.updateStatus');
Route::put('/admins/{id}/user', [AdminController::class, 'update'])->name('admins.userupdate');
Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
Route::post('/wallet/{id}', [AdminController::class, 'addwallet'])->name('wallet');
Route::get('/transaction-history/{id}', [AdminController::class, 'history'])->name('transaction.history');
















