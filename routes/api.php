<?php

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

Route::middleware('merchant')->group( function() {
    Route::post('debit', function (Request $request){
        return $request->all();
    });

    Route::get('', function (Request $request){
        return $request->all();
    });

    Route::resource('payments', 'PaymentController');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
