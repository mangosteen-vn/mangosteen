<?php

use Illuminate\Support\Facades\Route;
use Mangosteen\User\Http\Controllers\Api\Admin\AuthController;

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

Route::group(['prefix' => 'api'], static function () {
    Route::post('/login/firebase', [AuthController::class, 'handleLoginWithFirebase']);
    Route::post('/connect', [AuthController::class, 'connect']);

});
Route::group(['prefix' => 'api', 'middleware' => ['auth:api']], static function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/check-admin-role', [AuthController::class, 'checkAdminRole']);
});
