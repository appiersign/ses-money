<?php

use Illuminate\Support\Facades\Route;
Auth::routes();

Route::get('/', function () {
    $payments = \App\Payment::latest('created_at')->get();
    $transfers = \App\Transfer::latest('created_at')->get();
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
