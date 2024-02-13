<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([], function () {
    Route::group(['controller' => 'CustomerController'], function () {
        Route::get('customers', 'index');
        Route::post('customer', 'store');
        Route::get('customer/{customers}', 'show');
        Route::put('customer/{customers}', 'update');
        Route::delete('customer/{customers}', 'destroy');
    });

    Route::group(['controller' => 'ProductController'], function () {
        Route::get('products', 'index');
        Route::post('product', 'store');
        Route::get('product/{product}', 'show');
        Route::put('product/{product}', 'update');
        Route::delete('product/{product}', 'destroy');
    });

    Route::group(['controller' => 'OrderController'], function () {
        Route::get('orders', 'index');
        Route::post('discount', 'discount');
        Route::post('order', 'store');
        Route::get('order/{order}', 'show');
        Route::put('order/{order}', 'update');
        Route::delete('order/{order}', 'destroy');
    });
});
