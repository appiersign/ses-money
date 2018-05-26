<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('merchant')->group( function() {
    Route::resource('payments', 'PaymentController');
});

Route::post('payments/response/{provider}', 'PaymentController@response')->name('payments.response');

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
