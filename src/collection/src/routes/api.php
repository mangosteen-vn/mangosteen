<?php

use Illuminate\Support\Facades\Route;
use Mangosteen\File\Http\Controllers\Api\Admin\FileController;

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

Route::group(['prefix' => 'api/admin'], static function () {
    Route::get('collection', [\Mangosteen\Collection\Http\Controllers\Api\Admin\CollectionController::class, 'index']);
    Route::post('collection', [\Mangosteen\Collection\Http\Controllers\Api\Admin\CollectionController::class, 'store']);
});
