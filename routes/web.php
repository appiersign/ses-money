<?php

use Illuminate\Support\Facades\Route;
Auth::routes();

Route::get('payments/history', 'PaymentController@history')->name('payments.history');
Route::post('payments/history', 'PaymentController@handleHistory')->name('payments.history');
Route::get('payments/history/{from}/{to}', 'PaymentController@search')->name('payments.search');
Route::resource('payments', 'PaymentController');

Route::get('transfers/history', 'TransferController@history')->name('transfers.history');
Route::post('transfers/history', 'TransferController@handleHistory')->name('transfers.history');
Route::get('transfers/history/{from}/{to}', 'TransferController@search')->name('transfers.search');
Route::resource('transfers', 'TransferController');

//Route::get('payments', 'PaymentController@index');
Route::get('merchants/payments/make', 'PaymentController@create');
Route::post('merchants/payments/process', 'PaymentController@process');

Route::get('/', function () {
    $payments = \App\Payment::whereDate('created_at', \Carbon\Carbon::today()->toDateTimeString())->latest('created_at')->get();
    $transfers = \App\Transfer::whereDate('created_at', \Carbon\Carbon::today()->toDateTimeString())->latest('created_at')->get();
    $payment_sum = sum_amount($payments->toArray());
    $transfer_sum = sum_amount($transfers->toArray());
    $total = $payment_sum + $transfer_sum;
    return view('pages.dashboard', compact('payments', 'payment_sum', 'transfers', 'transfer_sum', 'total'));
});

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('merchants', 'MerchantController');
Route::get('merchants/{ses_money_id}/password.reset', 'MerchantController@resetPassword')->name('merchants.reset');
Route::get('merchants/{ses_money_id}/status.toggle', 'MerchantController@toggleStatus')->name('merchants.toggle');
Route::post('merchants/password.update', 'MerchantController@updatePassword')->name('merchants.updatePassword');
