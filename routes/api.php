<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ModifyPasswordController;
use App\Http\Controllers\Auth\RegisterUserController;
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

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/logout',[ LogoutController::class, 'store']);
    Route::get('/user', [RegisterUserController::class, 'user']);
    Route::post('/password/modify', [ ModifyPasswordController::class, 'store']);
});

Route::post('/register',[ RegisterUserController::class, 'store' ]);
Route::post('/login',[ LoginController::class, 'store' ]);

