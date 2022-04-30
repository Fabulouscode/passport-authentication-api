<?php

use App\Http\Controllers\Api\Auth\ActivateAccountController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\SignupController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(static function(){
    Route::prefix('users')->name('user.')->group(static function(){
        Route::post('/signUp', [SignupController::class, 'signup'])->name('signup');
        Route::post('/login', [LoginController::class, 'login'])->name('login');
        Route::patch('/accountActivation', [ActivateAccountController::class, 'accountActivation'])->name('accountActivation');
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    });
});