<?php

use App\Http\Controllers\PayoutsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/bond/{id}/payouts',[PayoutsController::class,'payouts']);
Route::post('/bond/{id}/order',[PayoutsController::class,'orderPost']);
Route::post('/bond/order/{order_id}',[PayoutsController::class,'bondOrder']);
