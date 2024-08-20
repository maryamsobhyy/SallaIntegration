<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth',[AuthController::class,'auth'])->name('auth');
Route::get('/auth/callback',[AuthController::class,'callback'])->name('callback');
Route::get('/store/info', [AuthController::class, 'showStoreInfo'])->name('store.info');

