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

Route::group(['prefix' => 'api',], static function () {
    Route::post('/upload-image', [FileController::class, 'uploadImage']);
});
//
//Route::group(['prefix' => 'api', 'middleware' => ['auth:api']], static function () {
//    Route::post('/upload-image', [\Mangosteen\File\Http\Controllers\Api\Admin\FileController::class, 'profile']);
//});
