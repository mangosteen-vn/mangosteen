<?php

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

Route::group(['prefix' => 'api/admin',], static function () {
    Route::get('/product', [\Mangosteen\Product\Http\Controllers\Api\Admin\ProductController::class, 'index']);
    Route::post('/product', [\Mangosteen\Product\Http\Controllers\Api\Admin\ProductController::class, 'store']);
    Route::put('/product/{id}', [\Mangosteen\Product\Http\Controllers\Api\Admin\ProductController::class, 'update']);
    Route::delete('/product/{id}', [\Mangosteen\Product\Http\Controllers\Api\Admin\ProductController::class, 'destroy']);
    Route::get('/product/{id}', [\Mangosteen\Product\Http\Controllers\Api\Admin\ProductController::class, 'show']);
});
