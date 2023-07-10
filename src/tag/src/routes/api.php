<?php

use Illuminate\Support\Facades\Route;
use Mangosteen\Tag\Http\Controllers\Api\Admin\TagController;

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
    Route::get('/tag', [TagController::class, 'index']);
    Route::post('/tag', [TagController::class, 'store']);
    Route::get('/tag/{id}', [TagController::class, 'show']);
    Route::delete('/tag/{id}', [TagController::class, 'destroy']);
    Route::put('/tag/{id}', [TagController::class, 'update']);
});
