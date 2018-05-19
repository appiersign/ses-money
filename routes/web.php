<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('merchants', 'MerchantController');
Route::get('merchants/{ses_money_id}/password.reset', 'MerchantController@resetPassword')->name('merchants.reset');
Route::get('merchants/{ses_money_id}/status.toggle', 'MerchantController@toggleStatus')->name('merchants.toggle');
Route::post('merchants/password.update', 'MerchantController@updatePassword')->name('merchants.updatePassword');
